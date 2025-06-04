@extends('layouts.user_type.auth')

@section('title', 'Purchasing Paskomnas || PISS')

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
            <div class="card py-3">
                <div class="nav-wrapper position-relative d-flex justify-content-center pt-1">
                    <ul class="nav nav-pills bg-transparent" role="tablist">
                        <!-- Tab: purchase All -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active mb-2 me-2" id="purchase-all-tab" data-bs-toggle="tab"
                                href="#purchase-all" role="tab" aria-controls="purchase-all" aria-selected="true">
                                <i class="fas fa-shopping-bag me-1 text-primary"></i> Purchase All
                            </a>
                        </li>

                        <!-- Tab: purchase per Customer -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link mb-2" id="purchase-customer-tab" data-bs-toggle="tab"
                                href="#purchase-customer" role="tab" aria-controls="purchase-customer"
                                aria-selected="false">
                                <i class="fas fa-users me-1 text-primary"></i> Purchase per Customer
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-content">
        <!-- Tab: purchase All -->
        <div class="tab-pane fade show active" id="purchase-all" role="tabpanel" aria-labelledby="purchase-all-tab">
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card mb-4 mx-4">
                        <div class="card-header pb-5">
                            <div class="d-flex flex-row justify-content-between align-items-center">
                                {{-- Kiri --}}
                                <div
                                    class="d-flex align-items-center bg-gradient-secondary ps-3 pe-3 py-2 rounded-2 text-white">
                                    <i class="fa fa-shopping-bag me-2"></i>
                                    <h6 class="mb-0 text-white">Purchase All</h6>
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

                        <div id="purchase-data" style="overflow-x: auto;">
                            {{-- Render Table --}}

                        </div>

                        <div class="d-flex justify-content-center mt-3 mb-2" id="pagination">

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: purchase per Customer -->
        <div class="tab-pane fade" id="purchase-customer" role="tabpanel" aria-labelledby="purchase-customer-tab">
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card mb-4 mx-4">
                        <div class="card-header pb-5">
                            <div class="d-flex flex-row justify-content-between align-items-center">
                                {{-- Kiri --}}
                                <div
                                    class="d-flex align-items-center bg-gradient-secondary ps-3 pe-3 py-2 rounded-2 text-white">
                                    <i class="fa fa-users me-2"></i>
                                    <h6 class="mb-0 text-white">Purchase per Customer</h6>
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
                                    <button class="btn btn btn-primary" onclick="printTableCustomer()">
                                        <i class="fa fa-print me-1"></i> Print
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="purchase-data-customer">
                            {{-- Render Table --}}
                            <div class="empty-data">
                                <div class="text-center">
                                    <img src="{{ asset('assets/img/select.png') }}" alt="No Data" class="img-fluid"
                                        width="450">
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
        
        let tableElement = document.querySelector('#purchase-data');
        let tableHTML = tableElement.outerHTML;

        let printWindow = window.open('', '', 'width=1000,height=800'); 
        printWindow.document.write(`
            <html>
            <head>
                <title>Print Purchasing</title>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
                <style>
                    body {
                        padding: 20px;
                        font-size: 12px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    table th, table td {
                        border: 1px solid #000;
                        padding: 6px;
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

    function printTableCustomer() {
        const hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

        let besok = new Date();
        besok.setDate(besok.getDate() + 1);

        let formattedDate = hari[besok.getDay()] + ', ' + besok.getDate() + ' ' + bulan[besok.getMonth()] + ' ' + besok.getFullYear();
        
        // Ambil nama customer dari select option
        const selectedOption = $('#order-select-customer option:selected');
        const customerName = selectedOption.val() !== 'all' ? selectedOption.text() : '';

        // Jika ada customer, tambahkan ke judul
        const titleText = customerName 
            ? `Purchasing Kirim (${formattedDate}) - ${customerName}` 
            : `Purchasing Kirim (${formattedDate})`;

        let tableElement = document.querySelector('#purchase-data-customer');
        let tableHTML = tableElement.outerHTML;

        let printWindow = window.open('', '', 'width=1000,height=800'); 
        printWindow.document.write(`
            <html>
            <head>
                <title class="text-light">Print Purchasing</title>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
                <style>
                    body {
                        padding: 20px;
                        font-size: 12px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    table th, table td {
                        border: 1px solid #000;
                        padding: 6px;
                        text-align: center;
                    }
                    h4 {
                        margin-bottom: 20px;
                        text-align: center;
                    }
                </style>
            </head>
            <body>
                <h4>${titleText}</h4>
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
                    <th class="text-center">Unit</th>
                    <th class="text-center text-success">Total</th>`;

        // Header customer
        Object.values(customers).forEach(customerName => {
            html += `<th class="text-center text-primary">${customerName}</th>`;
        });

        html += `</tr></thead><tbody>`;

        let i = 1;
        for (const goodsKey in dataTable) {
            const row = dataTable[goodsKey];
            html += `<tr>`;
            html += `<td class="text-center">${i++}</td>`;
            html += `<td class="text-center">${goodsKey}</td>`;
            html += `<td class="text-center">${row.unit}</td>`;
            html += `<td class="text-center">${row.total}</td>`;

            // Untuk setiap customer, tampilkan jumlah atau 0
            Object.keys(customers).forEach(customerId => {
                const qty = row.quantities[customerId] ?? 0;
                html += `<td class="text-center">${qty}</td>`;
            });

            html += `</tr>`;
        }

        html += `</tbody></table>`;

        return html;
    }

    function renderCustomerTable(dataPerCustomer) {
        let html = `<table class="table table-bordered table-striped table-responsive">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama Komoditi</th>
                    <th class="text-center">Qty</th>
                    <th class="text-center">Customer</th>
                </tr>
            </thead>
            <tbody>`;

        let no = 1;
        for (const customerId in dataPerCustomer) {
            const customerData = dataPerCustomer[customerId];
            const items = customerData.items;

            const itemKeys = Object.keys(items);
            itemKeys.forEach((itemName, index) => {
                html += `<tr>`;
                html += `<td class="text-center">${no++}</td>`;
                html += `<td class="text-center">${itemName}</td>`;
                html += `<td class="text-center">${items[itemName]}</td>`;
                if (index === 0) {
                    html += `<td class="text-center text-bold text-primary align-middle" rowspan="${itemKeys.length}">${customerData.customer_name}</td>`;
                }
                html += `</tr>`;
            });

            // Tambahkan total
            // html += `<tr class="bg-light fw-bold">
            //     <td colspan="2" class="text-end">Total ${customerData.customer_name}</td>
            //     <td colspan="2" class="text-start">${customerData.total_quantity}</td>
            // </tr>`;
        }

        html += `</tbody></table>`;
        return html;
    }

    function fetchData(date) {
        $('#purchase-data').html(`
            <div style="display:flex; justify-content:center; align-items:center; height:150px; font-size:1.2rem; color:#555;">
                <i class="fa fa-spinner fa-spin me-2"></i> Loading data...
            </div>
        `);

        $.ajax({
            url: "{{ route('purchase.summary') }}",
            method: 'GET',
            data: { order_date: date },
            success: function(response) {
                // Cek apakah dataTable kosong
                if (Object.keys(response.dataTable).length === 0) {
                    // Tampilkan empty data block
                    $('#purchase-data').html(`
                        <div class="empty-data">
                            <div class="text-center">
                                <img src="{{ asset('assets/img/empty-data2.png') }}" alt="No Data" class="img-fluid" width="450">
                                <h4 class="text-secondary">Maaf, Tidak ada data order</h4>
                            </div>
                        </div>
                    `);
                } else {
                    const html = renderTable(response.customers, response.dataTable);
                    $('#purchase-data').html(html);
                }
            },
            error: function() {
                $('#purchase-data').html('<div class="text-center text-danger">Gagal memuat data.</div>');
            }
        });
    }

    function fetchDataCustomer() {
        const date = $('#order-date-customer').val();
        const customerId = $('#order-select-customer').val();

        if (!date) return;

        $('#purchase-data-customer').html(`
            <div style="display:flex; justify-content:center; align-items:center; height:150px; font-size:1.2rem; color:#555;">
                <i class="fa fa-spinner fa-spin me-2"></i> Loading data...
            </div>
        `);

        $.ajax({
            url: "{{ route('purchase.customer') }}",
            method: 'GET',
            data: {
                order_date: date,
                customer_id: customerId
            },
            success: function (response) {
                if (Object.keys(response.data).length === 0) {
                    $('#purchase-data-customer').html(`
                        <div class="empty-data">
                            <div class="text-center">
                                <img src="{{ asset('assets/img/empty-data2.png') }}" alt="No Data" class="img-fluid" width="450">
                                <h4 class="text-secondary">Maaf, Tidak ada data order</h4>
                            </div>
                        </div>
                    `);
                } else {
                    const html = renderCustomerTable(response.data);
                    $('#purchase-data-customer').html(html);
                }
            },
            error: function () {
                $('#purchase-data-customer').html(`<div class="text-danger text-center">Gagal memuat data.</div>`);
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

        $('#order-date-customer, #order-select-customer').on('change', function () {
            const selectedDate = $('#order-date-customer').val();
            const selectedCustomer = $('#order-select-customer').val();

            // Cek apakah tanggal diisi
            if (selectedDate) {
                fetchDataCustomer();
            }
        });
    });
</script>

@endsection