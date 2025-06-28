@extends('layouts.user_type.auth')

@section('title', 'Invoice Paskomnas || PISS')

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
                            <i class="fa fa-file-invoice me-2"></i>
                            <h6 class="mb-0 text-white">Invoice</h6>
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
                            <button class="btn btn btn-primary" onclick="printInvoice()">
                                <i class="fa fa-print me-1"></i> Print
                            </button>
                        </div>
                    </div>
                </div>

                <div id="invoice-data">
                    {{-- Render Table --}}
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

    $(document).ready(function () {
        $('.select2').select2({ 
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Customer --', 
            allowClear: true, 
            width: '100%' 
        });

        let initialDate = $('#order-date-customer').val();

        // Trigger AJAX pertama saat halaman pertama kali dibuka
        if (initialDate) {
            $.ajax({
                url: "{{ route('invoice.index') }}",
                method: 'GET',
                data: { order_date: initialDate },
                success: function (response) {
                    let options = '<option value="all">-- Pilih Customer --</option>';

                    if (response.customers && Array.isArray(response.customers) && response.customers.length > 0) {
                        response.customers
                            .sort((a, b) => a.name.localeCompare(b.name))
                            .forEach(c => {
                                options += `<option value="${c.id}">${c.name}</option>`;
                            });

                        $('#order-select-customer').html(options).prop('disabled', false);
                    } 
                }
            });
        }

        $('#order-date-customer').on('change', function () {
            $('#invoice-data').html(`
                <div style="display:flex; justify-content:center; align-items:center; height:150px; font-size:1.2rem; color:#555;">
                    <i class="fa fa-spinner fa-spin me-2"></i> Memuat data...
                </div>
            `);

            const orderDate = $(this).val();
            if (!orderDate) return;

            $.get("{{ route('invoice.index') }}", { order_date: orderDate }, function (response) {
                if (response.customers?.length > 0) {
                    let options = '<option value="">-- Pilih Customer --</option>';
                    response.customers.forEach(c => options += `<option value="${c.id}">${c.name}</option>`);
                    $('#order-select-customer').html(options).prop('disabled', false);
                    $('#invoice-data').html(`
                        <div class="empty-data text-center">
                            <img src="{{ asset('assets/img/select.png') }}" width="450">
                            <h4 class="text-secondary">Pilih Customer</h4>
                        </div>
                    `);
                } else {
                    $('#order-select-customer').html('<option>-- Tidak ada Customer --</option>').prop('disabled', true);
                    $('#invoice-data').html(`
                        <div class="empty-data text-center">
                            <img src="{{ asset('assets/img/empty-data2.png') }}" width="450">
                            <h4 class="text-secondary">Tidak ada order di tanggal ini</h4>
                        </div>`);
                }
            });
        });

        $('#order-select-customer').on('change', function () {
            const orderDate = $('#order-date-customer').val();
            const customerId = $(this).val();
            if (!orderDate || !customerId) return;

            $.get("{{ route('invoice.index') }}", {
                order_date: orderDate,
                customer_id: customerId
            }, function (response) {
                currentOrderData = response;
                $('#invoice-data').html(renderDocument(response));
            });
        });

        $('body').on('change', '#po-selector', function () {
            const orderDate = $('#order-date-customer').val();
            const customerId = $('#order-select-customer').val();
            const orderId = $(this).val();

            $.get("{{ route('invoice.index') }}", {
                order_date: orderDate,
                customer_id: customerId,
                order_id: orderId
            }, function (response) {
                currentOrderData = response;
                $('#invoice-data').html(renderDocument(response));
            });
        });

        $('body').on('click', '[data-bs-target="#ubahOrderModal"]', function () {
            if (currentOrderData) {
                openUbahOrderModal(currentOrderData);
            }
        });

        if (initialDate) {
            // Tampilkan indikator sementara sebelum customer muncul
            $('#invoice-data').html(`
                <div style="display:flex; justify-content:center; align-items:center; height:150px; font-size:1.2rem; color:#555;">
                    <i class="fa fa-spinner fa-spin me-2"></i> Memuat data..
                </div>
            `);
                        
            $('#order-date-customer').trigger('change');
        }
    });

    function renderDocument(data) {
        const bulan = ['JANUARI','FEBRUARI','MARET','APRIL','MEI','JUNI','JULI','AGUSTUS','SEPTEMBER','OKTOBER','NOVEMBER','DESEMBER'];
        let currentDate = new Date();
        currentDate.setDate(currentDate.getDate() + 1); // besok

        const tanggal = `Surabaya, ${currentDate.getDate().toString().padStart(2, '0')} ${bulan[currentDate.getMonth()]} ${currentDate.getFullYear()}`;

        let dropdownOptions = '';
        if (Array.isArray(data.po_list)) {
            for (const po of data.po_list) {
                dropdownOptions += `<option value="${po.id}" ${po.id === data.order_id ? 'selected' : ''}>${po.code}</option>`;
            }
        }

        let html = `
        <div class="col-12 d-flex justify-content-center mb-3 pe-4">
            <div class="d-flex align-items-center gap-2">
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
        <div id="invoice-print" style="font-family: Arial, sans-serif; font-size: 14px; padding: 20px;">
            <div class="title" style="text-align: center;">
                <strong>PT. SELARAS MAKMUR PANGAN JAYA</strong><br>
                SURABAYA
            </div>
            <hr>
            <div style="margin-top: 10px;">
                <div style="float: left; width: 60%;">
                    <div style="font-size: 14px; line-height: 1.4;">
                        <div><strong>INVOICE</strong></div><br>   
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
                        <div>NPWP : ${data.npwp ? data.npwp : ''}</div>
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div>

            <table class="table table-striped table-responsive" style="width: 100%; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="padding: 6px; text-align: center; width: 20%;">BANYAKNYA</th>
                        <th style="padding: 6px; text-align: center; width: 40%;">NAMA BARANG</th>
                        <th style="padding: 6px; text-align: center; width: 10%;">SPESIFIKASI</th>
                        <th style="padding: 6px; text-align: center; width: 15%;">HARGA</th>
                        <th style="padding: 6px; text-align: center; width: 15%;">JUMLAH</th>
                    </tr>
                </thead>
                <tbody>`;

        for (const item of data.data) {
            html += `
                <tr>
                    <td style="padding: 6px; text-align: center; font-size: 14px;"
                        data-original-qty="${item.qty}"
                        data-unit="${item.unit}"
                        data-converted="false"
                        ">
                        <span class="qty-val" style="display: inline-block; min-width: 40px; text-align: right;">
                            ${Number(item.qty).toLocaleString('id-ID')}
                        </span>
                        <span class="qty-unit" style="display: inline-block; min-width: 30px; text-align: left;">
                            ${item.unit}
                        </span>
                    </td>
                    <td class="td-name" style="padding: 6px; text-align: center; font-size: 14px;">${item.name}</td>
                    <td style="padding: 6px; text-align: center; font-size: 14px;">${item.specification}</td>
                    <td class="td-price" style="padding: 6px; text-align: center; font-size: 14px;">${item.price_formatted}</td>
                    <td class="td-price" style="padding: 6px; text-align: center; font-size: 14px;">${item.total_price_formatted}</td>
                </tr>`;
        }

        html += `
                    <tr>
                        <td class="noborder" colspan="4" style="padding: 6px; text-align: center; font-size: 14px;">
                                Jumlah Rp
                        </td>
                        <td class="td-price" style="padding: 6px; text-align: center; font-size: 14px;">
                                ${data.grand_total}
                        </td>
                    </tr>
                    <tr>
                        <td class="noborder" colspan="4" style="padding: 6px; text-align: center; font-size: 14px;">
                                PPN
                        </td>
                        <td class="td-price" style="padding: 6px; text-align: center; font-size: 14px;">
                                ${data.ppn_status === 'Dengan PPN' ? '11%' : '-'}
                        </td>
                    </tr>
                    <tr>
                        <td class="noborder" colspan="4" style="padding: 6px; text-align: center; font-size: 14px;">
                                Total Rp
                        </td>
                        <td class="td-price" style="padding: 6px; text-align: center; font-size: 14px;">
                                ${data.grand_total_with_ppn}
                        </td>
                    </tr>
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
            <div style="font-size: 14px; color: #555;">
                <div>Pembayaran Harap Transfer ke Rek :</div>
                <div><strong>MANDIRI NO.Rek 140-002-302-0531</strong></div>
                <div><strong>PT. Selaras Makmur Pangan Jaya</strong></div>
            </div>
        </div>`;

        return html;
    }

    function printInvoice() {
        const printElement = document.getElementById("invoice-print");

        let printWindow = window.open('', '', `left=0,top=0,width=${screen.width},height=${screen.height}`);
        printWindow.document.write(`
            <html>
            <head>
                <title>Print Invoice</title>
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
                    table td.td-price {
                        text-align: right !important;
                    }
                    table td.noborder {
                        border-left: none !important;
                        border-top: none !important;
                        border-bottom: none !important;
                        text-align: right !important;
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