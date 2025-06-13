@extends('layouts.user_type.auth')

@section('title', 'Surat Jalan Paskomnas || PISS')

@section('content')

<div>
    @if($errors->any())
    <div class="mx-4 alert alert-primary alert-dismissible fade show" role="alert">
        <span class="alert-text text-white">
            {{$errors->first()}}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <i class="fa fa-close" aria-hidden="true"></i>
        </button>
    </div>
    @endif
    @if(session('success'))
    <div class="mx-4 alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
        <span class="alert-text text-white">
            {{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <i class="fa fa-close" aria-hidden="true"></i>
        </button>
    </div>
    @endif


    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-5">
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        {{-- Kiri --}}
                        <div
                            class="d-flex align-items-center bg-gradient-secondary ps-3 pe-3 py-2 rounded-2 text-white">
                            <i class="fa fa-clipboard-list me-2"></i>
                            <h6 class="mb-0 text-white">Surat Jalan</h6>
                        </div>

                        {{-- Tengah --}}
                        <div class="mt-2 d-flex justify-content-center align-items-center gap-3">
                            <i class="fa fa-calendar fa-2x"></i>
                            <input type="date" class="form-control datepicker" id="order-date-customer"
                                name="order_date" required>

                            <div style="min-width: 250px; flex-grow: 1;">
                                <select class="form-select select2 w-50" id="order-select-customer" required>
                                    <option value="all" selected>-- Pilih Customer --</option>
                                    @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <i class="fa fa-briefcase fa-2x"></i>
                        </div>

                        {{-- Kanan (Tombol Print) --}}
                        <div class="d-flex align-items-center">
                            <button class="btn btn btn-primary mb-0" onclick="printDocument()">
                                <i class="fa fa-print me-1"></i> Print
                            </button>
                        </div>
                    </div>
                </div>

                <div id="document-data">
                    {{-- Render Table --}}
                    <div class="empty-data">
                        <div class="text-center">
                            <img src="{{ asset('assets/img/select.png') }}" alt="No Data" class="img-fluid" width="450">
                            <h4 class="text-secondary">Pilih Tanggal dan Customer</h4>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-3 mb-2" id="pagination">

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const userName = @json(auth()->user()->name);
    const selectImageUrl = "{{ asset('assets/img/empty-data2.png') }}";

    $(document).ready(function () {
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: "-- Pilih Customer --",
            allowClear: true,
            width: '100%'
        });

        $('#order-date-customer, #order-select-customer').on('change', function () {
            let orderDate = $('#order-date-customer').val();
            let customerId = $('#order-select-customer').val();

            if (orderDate && customerId && customerId !== 'all') {
                $.ajax({
                    url: "{{ route('document.data') }}",
                    method: 'GET',
                    data: {
                        order_date: orderDate,
                        customer_id: customerId
                    },
                    success: function (response) {
                        $('#document-data').html(renderDocument(response));
                    },
                    error: function () {
                        $('#document-data').html(`
                            <div class="empty-data">
                                <div class="text-center">
                                    <img src="${selectImageUrl}" alt="No Data" class="img-fluid" width="450">
                                    <h4 class="text-secondary">Maaf, customer ini tidak memiliki order</h4>
                                </div>
                            </div>
                        `);
                    }
                });
            } else {
                $('#document-data').html(`
                    <div class="empty-data">
                        <div class="text-center">
                            <img src="{{ asset('assets/img/select.png') }}" alt="No Data" class="img-fluid" width="450">
                            <h4 class="text-secondary">Pilih Tanggal dan Customer</h4>
                        </div>
                    </div>
                `);
            }
        });
    });

    flatpickr(".datepicker", {
        locale: "id",
        altInput: true,
        altFormat: "l, d F Y",
        dateFormat: "Y-m-d",
        defaultDate: "{{ \Carbon\Carbon::today()->toDateString() }}", // Hari ini
        onReady: function (selectedDates, dateStr, instance) {
            if (instance.altInput) {
                instance.altInput.setAttribute("style", "background-color: white; cursor: text;");
            }
        },
        onChange: function(selectedDates, dateStr, instance) {
            if (dateStr) {
                $('#order-date-customer').trigger('change'); // tetap sinkron dengan AJAX listener
            }
        }
    });

    $('body').on('change', '#po-selector', function () {
        const selectedOrderId = $(this).val();
        const customerId = $('#order-select-customer').val();
        const orderDate = $('#order-date-customer').val();

        if (!customerId || !orderDate) return;

        $.ajax({
            url: "{{ route('document.data') }}",
            method: 'GET',
            data: {
                customer_id: customerId,
                order_date: orderDate,
                order_id: selectedOrderId
            },
            success: function (response) {
                $('#document-data').html(renderDocument(response)); // render ulang
            }
        });
    });

    function renderDocument(data) {
        const bulan = ['JANUARI','FEBRUARI','MARET','APRIL','MEI','JUNI','JULI','AGUSTUS','SEPTEMBER','OKTOBER','NOVEMBER','DESEMBER'];
        let currentDate = new Date();
        currentDate.setDate(currentDate.getDate() + 1); // besok

        const tanggal = `Surabaya, ${currentDate.getDate().toString().padStart(2, '0')} ${bulan[currentDate.getMonth()]} ${currentDate.getFullYear()}`;

        let dropdownOptions = '';
        for (const po of data.po_list) {
            dropdownOptions += `<option value="${po.id}" ${po.id === data.order_id ? 'selected' : ''}>${po.code}</option>`;
        }

        let html = `
        <div class="col-12 d-flex justify-content-center mb-3 pe-4">
            <div class="d-flex align-items-center gap-1">
                <div class="position-relative d-inline-block" style="min-width: 150px;">
                    <label class="btn bg-gradient-dark text-white w-100 d-flex align-items-center justify-content-center rounded-pill shadow-sm" style="height: 38px;">
                        <i class="fa fa-file-alt me-2"></i> Pilih Order <i class="fa fa-caret-down ms-2"></i>
                    </label>
                    <select id="po-selector"
                        class="form-select form-select-sm position-absolute top-0 start-3 w-100 h-100 bg-dark text-white"
                        style="opacity: 0; cursor: pointer; border-radius: 50px;">
                        ${dropdownOptions}
                    </select>
                </div>
            </div>
        </div>
        <div id="surat-jalan-print" style="font-family: Arial, sans-serif; font-size: 14px; padding: 20px;">
            <div class="title" style="text-align: center;">
                <strong>PT. SELARAS MAKMUR PANGAN JAYA</strong><br>
                SURABAYA
            </div>
            <hr>
            <div style="margin-top: 10px;">
                <div style="float: left; width: 60%;">
                    <div style="font-size: 14px; line-height: 1.4;">
                        <div><strong>SURAT JALAN</strong></div><br>   
                        <div><span style="display: inline-block; min-width: 65px;">Nota No</span>: ${data.order_code}</div>
                        <div><span style="display: inline-block; min-width: 65px;">No. PO</span>: ${data.reference_customer ? data.reference_customer : '-'}</div>
                        <div><span style="display: inline-block; min-width: 65px;">Sales</span>: Mikael Candra ES</div>
                        <div> <span style="display: inline-block; min-width: 65px;">PO</span>: ${data.ppn_status}</div>
                    </div>
                </div>
                <div style="float: right; width: 40%; text-align: left;">
                    <div style="font-size: 14px; line-height: 1.4;">
                        <div>${tanggal}</div>
                        <div>Kepada Yth:</div>
                        <div><strong>${data.customer}</strong></div>
                        <div>${data.address}</div>
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div>

            <table class="table table-striped table-responsive" style="width: 100%; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 6px; text-align: center; width: 20%;">BANYAKNYA</th>
                        <th style="padding: 6px; text-align: center; width: 50%;">NAMA BARANG</th>
                        <th style="padding: 6px; text-align: center; width: 30%;">SPESIFIKASI</th>
                    </tr>
                </thead>
                <tbody>`;

        for (const item of data.data) {
            html += `
                <tr>
                    <td style="padding: 6px; text-align: center; font-size: 14px;"
                        data-original-qty="${item.qty}"
                        data-unit="${item.unit}"
                        data-converted="false">
                        <span class="qty-val" style="display: inline-block; min-width: 40px; text-align: right;">
                            ${Number(item.qty).toLocaleString('id-ID')}
                        </span>
                        <span class="qty-unit" style="display: inline-block; min-width: 30px; text-align: left;">
                            ${item.unit}
                        </span>
                    </td>
                    <td class="td-name" style="padding: 6px; text-align: center; font-size: 14px;">${item.name}</td>
                    <td style="padding: 6px; text-align: center; font-size: 14px;">${item.specification}</td>
                </tr>`;
        }

        html += `
                </tbody>
            </table>

            <div style="margin-top: 80px; display: flex; justify-content: space-around; text-align: center; font-size: 14px;">
                <div style="min-width: 150px;">
                    <div>Diterima kondisi baik</div><br><br><br>
                    <div style="height: 150px;">(                                )</div>
                </div>
                <div style="min-width: 150px;">
                    <div>Pengirim,</div><br><br><br>
                    <div style="height: 150px;">(                                )</div>
                </div>
                <div style="min-width: 150px;">
                    <div>Hormat Saya,</div><br><br><br>
                    <div style="height: 150px;">( ${userName} )</div>
                </div>
            </div>
        </div>`;

        return html;
    }

    function printDocument() {
        const printElement = document.getElementById("surat-jalan-print");

        const printWindow = window.open('', '', 'width=1000,height=800');
        printWindow.document.write(`
            <html>
            <head>
                <title>Print Surat Jalan</title>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
                <style>
                    html, body {
                        height: 100%;
                        margin: 0;
                        padding: 0;
                    }
                    body {
                        font-family: Arial, sans-serif;
                        font-size: 12px;
                        box-sizing: border-box;
                    }
                    .print-wrapper {
                        border: 2px solid black;
                        padding: 20px;
                        height: calc(100% - 40px); /* 100% minus padding top+bottom */
                        box-sizing: border-box;
                    }
                    .title {
                        font-size: 14px;
                        margin-top: -25px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    table th, table td {
                        border: 1px solid #000;
                        padding: 6px;
                        text-align: center;
                        font-size: 12px;
                    }
                    table td.td-name {
                        text-align: left !important;
                    }
                    hr {
                        border: 1px solid black;
                    }
                </style>
            </head>
            <body>
                <div class="print-wrapper">
                    ${printElement.outerHTML}
                </div>
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }
</script>

@endsection