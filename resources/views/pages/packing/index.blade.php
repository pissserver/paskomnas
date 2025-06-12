@extends('layouts.user_type.auth')

@section('title', 'Packing Paskomnas || PISS')

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

    <div class="tab-content">
        <div class="tab-pane fade show active" id="packing-all" role="tabpanel" aria-labelledby="packing-all-tab">
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card mb-4 mx-4">
                        <div class="card-header pb-5">
                            <div class="d-flex flex-row justify-content-between align-items-center">
                                {{-- Kiri --}}
                                <div
                                    class="d-flex align-items-center bg-gradient-secondary ps-3 pe-3 py-2 rounded-2 text-white">
                                    <i class="fa fa-people-carry me-2"></i>
                                    <h6 class="mb-0 text-white">Packing</h6>
                                </div>

                                {{-- Tengah --}}
                                <div class="mt-2 d-flex justify-content-center align-items-center">
                                    <i class="fa fa-calendar fa-2x me-2"></i>
                                    <input type="date" class="form-control datepicker" id="order-date" name="order_date"
                                        required>
                                </div>

                                {{-- Kanan (Tombol Print) --}}
                                <div class="d-flex align-items-center">
                                    <button class="btn btn btn-primary" onclick="printTable()">
                                        <i class="fa fa-print me-1"></i> Print
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="packing-data" style="overflow-x: auto;">
                            {{-- Render Table --}}

                        </div>

                        <div class="d-flex justify-content-center mt-3 mb-2" id="pagination">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: "-- Pilih Customer --",
            allowClear: true,
            width: '100%'
        });
    });

    function printTable() {
        const hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

        let besok = new Date();
        besok.setDate(besok.getDate() + 1);

        let formattedDate = hari[besok.getDay()] + ', ' + besok.getDate() + ' ' + bulan[besok.getMonth()] + ' ' + besok.getFullYear();
        
        let tableElement = document.querySelector('#packing-data');
        let tableHTML = tableElement.outerHTML;

        let printWindow = window.open('', '', 'width=1000,height=800'); 
        printWindow.document.write(`
            <html>
            <head>
                <title>Print Purchasing</title>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
                <style>
                    @page {
                        size: auto;
                        margin: 5mm 3mm; /* atas-bawah 10mm, kanan-kiri 10mm */
                    }
                    body {
                        padding: 10px;
                        font-size: 12px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    table th, table td {
                        border: 1px solid #000;
                        padding: 6px;
                        font-size: 10px;
                        text-align: center;
                    }
                    h4 {
                        margin-bottom: 20px;
                        text-align: center;
                    }
                </style>
            </head>
            <body>
                <h4>Purchasing Kirim (${formattedDate})</h4>
                ${tableHTML}
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }

    flatpickr(".datepicker", {
        locale: "id",
        altInput: true,
        altFormat: "l, d F Y",
        dateFormat: "Y-m-d",
        defaultDate: "{{ $selectedDate ?? \Carbon\Carbon::today()->toDateString() }}",
        onReady: function (selectedDates, dateStr, instance) {
            if (instance.altInput) {
                instance.altInput.setAttribute("style", "background-color: white; cursor: text;");
            }
        },
        onChange: function(selectedDates, dateStr, instance) {
            if (dateStr) {
                fetchData(dateStr);
            }
        }
    });

    function renderTable(customers, dataTable) {
        let html = `<table class="table table-striped table-responsive">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama Komoditi</th>
                    <th class="text-center">Unit</th>`;

        // Header per customer
        Object.values(customers).forEach(customerName => {
            html += `<th class="text-center text-primary">${customerName}</th>`;
        });

        html += `<th class="text-center text-success">Total</th></tr></thead><tbody>`;

        let i = 1;
        for (const goodsKey in dataTable) {
            const row = dataTable[goodsKey];
            html += `<tr>`;
            html += `<td class="text-center align-middle">${i++}</td>`;
            html += `<td class="text-center align-middle">${goodsKey}</td>`;
            html += `<td class="text-center align-middle">${row.unit}</td>`;

            // Kolom untuk setiap customer
            Object.keys(customers).forEach(customerId => {
                const orders = row.quantities[customerId] ?? [];

                if (orders.length > 0) {
                    const formatted = orders.map(entry =>
                        `${entry.qty} (${entry.order_code})`
                    ).join('<br>');
                    html += `<td class="text-center align-middle">${formatted}</td>`;
                } else {
                    html += `<td class="text-center align-middle">-</td>`;
                }
            });

            html += `<td class="text-center align-middle">${row.total}</td>`;
            html += `</tr>`;
        }

        html += `</tbody></table>`;

        return html;
    }

    function fetchData(date) {
        $('#packing-data').html(`
            <div style="display:flex; justify-content:center; align-items:center; height:150px; font-size:1.2rem; color:#555;">
                <i class="fa fa-spinner fa-spin me-2"></i> Loading data...
            </div>
        `);

        $.ajax({
            url: "{{ route('packing.summary') }}",
            method: 'GET',
            data: { order_date: date },
            success: function(response) {
                // Cek apakah dataTable kosong
                if (Object.keys(response.dataTable).length === 0) {
                    // Tampilkan empty data block
                    $('#packing-data').html(`
                        <div class="empty-data">
                            <div class="text-center">
                                <img src="{{ asset('assets/img/empty-data2.png') }}" alt="No Data" class="img-fluid" width="450">
                                <h4 class="text-secondary">Maaf, Tidak ada data order</h4>
                            </div>
                        </div>
                    `);
                } else {
                    const html = renderTable(response.customers, response.dataTable);
                    $('#packing-data').html(html);
                }
            },
            error: function() {
                $('#packing-data').html('<div class="text-center text-danger">Gagal memuat data.</div>');
            }
        });
    }

    $(document).ready(function() {
        const defaultDate = $('#order-date').val();

        fetchData(defaultDate);

        $('#form-date').on('submit', function() {
            const selectedDate = $('#order-date').val();
            if (selectedDate) {
                fetchData(selectedDate);
            }
        });
    });
</script>

@endsection