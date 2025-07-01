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

    <div class="row">
        <div class="col-12">
            <div class="card py-3">
                <div class="nav-wrapper position-relative d-flex justify-content-center pt-1">
                    <ul class="nav nav-pills bg-transparent" role="tablist">
                        <!-- Tab: Per Group Customer -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active mb-2 me-2" id="group-customer-tab" data-bs-toggle="tab"
                                href="#group-customer" role="tab" aria-controls="group-customer" aria-selected="true">
                                <i class="fas fa-users me-1 text-primary"></i> Packing per Group
                            </a>
                        </li>

                        <!-- Tab: Packing per Customer -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link mb-2" id="packing-customer-tab" data-bs-toggle="tab"
                                href="#packing-customer" role="tab" aria-controls="packing-customer"
                                aria-selected="false">
                                <i class="fas fa-briefcase me-1 text-primary"></i> Packing per Customer
                            </a>
                        </li>

                        <!-- Tab: Log Print -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link mb-2" id="log-print-tab" data-bs-toggle="tab" href="#log-print"
                                role="tab" aria-controls="log-print" aria-selected="false">
                                <i class="fas fa-history me-1 text-primary"></i> Log Print
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-content">
        <!-- Tab: Packing per Group -->
        <div class="tab-pane fade show active" id="group-customer" role="tabpanel" aria-labelledby="group-customer-tab">
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card mb-4 mx-4">
                        <div class="card-header pb-5">
                            <div class="d-flex flex-row justify-content-between align-items-center">
                                {{-- Kiri --}}
                                <div
                                    class="d-flex align-items-center bg-gradient-secondary ps-3 pe-3 py-2 rounded-2 text-white">
                                    <i class="fa fa-users me-2"></i>
                                    <h6 class="mb-0 text-white">Packing per Group</h6>
                                </div>

                                {{-- Tengah --}}
                                <div class="mt-2 d-flex justify-content-center align-items-center">
                                    <i class="fa fa-calendar fa-2x me-2"></i>
                                    <input type="date" class="form-control datepicker me-3" id="order-date"
                                        name="order_date" required>

                                    <div style="min-width: 250px; flex-grow: 1;">
                                        <select class="form-select select2-group w-100" id="group-select" disabled>
                                            <option value="" selected>-- Pilih Group --</option>
                                        </select>
                                    </div>
                                    <i class="fa fa-users fa-2x ms-2"></i>
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
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Packing per Customer -->
        <div class="tab-pane fade" id="packing-customer" role="tabpanel" aria-labelledby="packing-customer-tab">
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card mb-4 mx-4">
                        <div class="card-header pb-5">
                            <div class="d-flex flex-row justify-content-between align-items-center">
                                {{-- Kiri --}}
                                <div
                                    class="d-flex align-items-center bg-gradient-secondary ps-3 pe-3 py-2 rounded-2 text-white">
                                    <i class="fa fa-briefcase me-2"></i>
                                    <h6 class="mb-0 text-white">Packing per Customer</h6>
                                </div>

                                {{-- Tengah --}}
                                <div class="mt-2 d-flex justify-content-center align-items-center gap-3">
                                    <i class="fa fa-calendar fa-2x"></i>
                                    <input type="date" class="form-control datepicker" id="order-date-customer"
                                        name="order_date" onchange="fetchDataCustomer()" required>

                                    <div style="min-width: 250px; flex-grow: 1;">
                                        <select class="form-select select2 w-100" id="order-select-customer"
                                            onchange="fetchDataCustomer()" required disabled>
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

                        <div id="packing-data-customer">
                            {{-- Render Table --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Log Print -->
        <div class="tab-pane fade" id="log-print" role="tabpanel" aria-labelledby="log-print-tab">
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card mb-4 mx-4">
                        <div class="card-header pb-5">
                            <div class="d-flex flex-row justify-content-between align-items-center">
                                <div
                                    class="d-flex align-items-center bg-gradient-secondary ps-3 pe-3 py-2 rounded-2 text-white">
                                    <i class="fa fa-history me-2"></i>
                                    <h6 class="mb-0 text-white">Log Print</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <input type="text" class="form-control form-control-sm me-1" id="searchLogPrint"
                                        placeholder="Cari log print..." name="search" style="max-width: 200px;">
                                    <span class="input-group-text">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div id="packing-log-print mt-3">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0 table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                No
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Tanggal Print
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Group / Trading
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="packingLogPrintTableBody">

                                    </tbody>
                                </table>
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
        $('.select2, .select2-group').each(function () {
            $(this).select2({
                theme: 'bootstrap-5',
                placeholder: $(this).find('option:first').text(), // ambil placeholder dari option pertama
                allowClear: true,
                width: '100%'
            });
        });

        // Untuk Summary Group
        const defaultDate = $('#order-date').val();
        if (defaultDate) {
            fetchData(defaultDate);
        }

        // Untuk Packing per Customer
        const today = new Date().toISOString().split('T')[0];
        $('#order-date-customer').val(today);
        // Set select ke "all" agar ambil semua customer tanpa grup
        $('#order-select-customer').val('all');
        $('#order-select-customer').on('change', function () {
            fetchDataCustomer(false); // ← tidak tampilkan loading
        });
        $('#order-date-customer').on('change', function () {
            fetchDataCustomer(true); // ← tampilkan loading
        });
        // Fetch semua customer tanpa grup yang order hari ini
        fetchDataCustomer();
    });

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
    
    function printTable() {
        const hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

        let besok = new Date();
        besok.setDate(besok.getDate() + 1);

        let formattedDate = hari[besok.getDay()] + ', ' + besok.getDate() + ' ' + bulan[besok.getMonth()] + ' ' + besok.getFullYear();
        
        let tableElement = document.querySelector('#packing-data');
        let tableHTML = tableElement.outerHTML;

        // Ambil semua order ID
        const orderIdEls = document.querySelectorAll('.printed-order-id');
        const orderIds = Array.from(orderIdEls).map(el => el.value);
        console.log('Order IDs:', orderIds); // Debug dulu

        // Buka window cetak
        let printWindow = window.open('', '', `left=0,top=0,width=${screen.width},height=${screen.height}`);
        printWindow.document.write(`
            <html>
            <head>
                <title>Print Packing</title>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
                <style>
                    body { padding: 20px; font-size: 12px; }
                    .title-group { font-size: 14px; }
                    table { width: 100%; border-collapse: collapse; }
                    table th, table td { border: 1px solid #000; padding: 6px; text-align: center; }
                    h4 { margin-bottom: 20px; text-align: center; }
                </style>
            </head>
            <body>
                <h4>Packing Kirim (${formattedDate})</h4>
                ${tableHTML}
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();

        // Kirim ke backend hanya kalau ada order
        if (orderIds.length > 0) {
            fetch("{{ route('packing.log-print') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ order_ids: orderIds })
            })
            .then(res => res.json())
            .then(data => console.log('Log print saved:', data))
            .catch(err => console.error('Log error:', err));
        } else {
            console.warn("Tidak ada order_id ditemukan, tidak dikirim ke server");
        }

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
            ? `Packing Kirim (${formattedDate}) - ${customerName}` 
            : `Packing Kirim (${formattedDate})`;

        let tableElement = document.querySelector('#packing-data-customer');
        let tableHTML = tableElement.outerHTML;

        let printWindow = window.open('', '', `left=0,top=0,width=${screen.width},height=${screen.height}`);
        printWindow.document.write(`
            <html>
            <head>
                <title class="text-light">Print Packing</title>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
                <style>
                    body {
                        padding: 20px;
                        font-size: 12px;
                    }
                    .title-customer{
                        font-size: 14px;
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

        const orderIdEls = document.querySelectorAll('.printed-order-id');
        const orderIds = Array.from(orderIdEls).map(el => el.value);
        console.log('Order IDs:', orderIds);

        if (orderIds.length > 0) {
            fetch("{{ route('packing.log-print') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ order_ids: orderIds })
            })
            .then(res => res.json())
            .then(data => console.log('Log print saved:', data))
            .catch(err => console.error('Log error:', err));
        } else {
            console.warn("Tidak ada order_id ditemukan, tidak dikirim ke server");
        }

        printWindow.print();
        printWindow.close();
    }

    function renderGroupTable(groupName, groupData) {
        const customers = groupData.customers;
        const dataTable = groupData.dataTable;

        let html = `
            <div class="text-center">
                <h6 class="text-white d-inline-block bg-gradient-primary mb-5 py-2 px-3 rounded-pill title-group">
                    Group : ${groupName}
                </h6>
            </div>
        `;
        html += `<table class="table table-striped">`;
        html += `
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama Komoditi</th>
                    <th class="text-center">Satuan</th>
                    <th class="text-center text-success">
                        Total
                    </th>`;

        Object.values(customers).forEach(name => {
            html += `<th class="text-center text-primary">${name}</th>`;
        });

        html += `</tr></thead><tbody>`;

        let i = 1;
        Object.entries(dataTable).forEach(([goodsKey, row]) => {
            html += `<tr>`;
            html += `<td class="text-center">${i++}</td>`;
            html += `<td class="text-center">${goodsKey}</td>`;
            html += `<td class="text-center">${row.unit}</td>`;
            html += `<td class="text-center">${row.total}</td>`;

            Object.keys(customers).forEach(custId => {
                html += `<td class="text-center">${row.quantities[custId] ?? 0}</td>`;
            });

            html += `</tr>`;
        });

        html += `</tbody></table>`;

        if (groupData.orderIds && groupData.orderIds.length > 0) {
            groupData.orderIds.forEach(id => {
                html += `<input type="hidden" class="printed-order-id" value="${id}">`;
            });
        }

        $('#packing-data').html(html);
    }

    function renderCustomerTable(items, customerNames, orderIds = []) {
        let hiddenInputs = '';
        if (orderIds.length > 0) {
            orderIds.forEach(id => {
                hiddenInputs += `<input type="hidden" class="printed-order-id" value="${id}">`;
            });
        }

        let html = `
            <div>
                <div class="text-center mb-4">
                    <h6 class="text-white d-inline-block bg-gradient-primary py-2 px-4 rounded-pill title-customer">
                        Customer : ${customerNames.join(', ')}
                    </h6>
                </div>
                <table class="table table-striped table-responsive">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Komoditi</th>
                            <th class="text-center">Satuan</th>
                            <th class="text-center text-success">Total</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        let no = 1;
        for (const key in items) {
            html += `
                <tr>
                    <td class="text-center">${no++}</td>
                    <td class="text-center">${key}</td>
                    <td class="text-center">${items[key].unit}</td>
                    <td class="text-center">${items[key].quantity}</td>
                </tr>
            `;
        }

        html += `</tbody></table>`;
        html += hiddenInputs;
        html += `</div>`; // penutup wrapper utama
        return html;
    }

    function fetchData(date) {
        $('#packing-data').html(`
            <div style="display:flex; justify-content:center; align-items:center; height:150px; font-size:1.2rem; color:#555;">
                <i class="fa fa-spinner fa-spin me-2"></i> Memuat data...
            </div>
        `);

        $.ajax({
            url: "{{ route('packing.group') }}",
            method: 'GET',
            data: { order_date: date },
            success: function(response) {
                const groupSelect = $('#group-select');

                // CASE: Tidak ada data group sama sekali
                if (Object.keys(response).length === 0) {
                    $('#packing-data').html(`
                        <div class="empty-data mb-5">
                            <div class="text-center">
                                <img src="{{ asset('assets/img/empty-data2.png') }}" alt="No Data" class="img-fluid" width="450">
                                <h4 class="text-secondary">Tidak ada data order di tanggal ini</h4>
                            </div>
                        </div>
                    `);
                    groupSelect.html('<option value="none" selected>-- Tidak ada Group --</option>').prop('disabled', true);
                    return;
                } else {
                    // Ada group di tanggal itu
                    let groupOptions = '<option value="">-- Pilih Group --</option>';
                    Object.keys(response)
                        .sort((a, b) => a.localeCompare(b))
                        .forEach(group => {
                            groupOptions += `<option value="${group}">${group}</option>`;
                        });

                    groupSelect.html(groupOptions).prop('disabled', false);

                    // Tampilkan placeholder sampai grup dipilih
                    $('#packing-data').html(`
                        <div class="empty-data">
                            <div class="text-center">
                                <img src="{{ asset('assets/img/select.png') }}" alt="No Data" class="img-fluid" width="450">
                                <h4 class="text-secondary mb-4">Pilih Group</h4>
                            </div>
                        </div>
                    `);

                    // Handler ketika user memilih grup
                    groupSelect.on('change', function () {
                        const selectedGroup = $(this).val();

                        if (selectedGroup && response[selectedGroup]) {
                            renderGroupTable(selectedGroup, response[selectedGroup]);
                        }
                    });
                }
            },
            error: function() {
                $('#packing-data').html('<div class="text-danger text-center">Gagal memuat data</div>');
            }
        });
    }

    function fetchDataCustomer(triggeredByDate = false) {
        const date = $('#order-date-customer').val();
        const customerId = $('#order-select-customer').val();

        if (!date) return;

        if (triggeredByDate) {
            $('#packing-data-customer').html(`
                <div style="display:flex; justify-content:center; align-items:center; height:150px; font-size:1.2rem; color:#555;">
                    <i class="fa fa-spinner fa-spin me-2"></i> Memuat data...
                </div>
            `);
        }

        $.ajax({
            url: "{{ route('packing.customer') }}",
            method: 'GET',
            data: {
                order_date: date,
                customer_id: customerId
            },
            success: function (response) {
                const items = response.items;
                const names = response.customer_names;

                if (Object.keys(items).length === 0) {
                    $('#packing-data-customer').html(`
                        <div class="empty-data mb-5">
                            <div class="text-center">
                                <img src="{{ asset('assets/img/empty-data2.png') }}" alt="No Data" class="img-fluid" width="450">
                                <h4 class="text-secondary">Tidak ada data order ditanggal ini</h4>
                            </div>
                        </div>
                    `);
                    $('#order-select-customer').html(`<option value="all" selected>-- Tidak ada Customer --</option>`);
                    $('#order-select-customer').prop('disabled', true);
                } else {
                    // Isi select jika belum memilih customer
                    if (customerId === 'all' || customerId === null) {
                        let options = `<option value="all">-- Semua Customer --</option>`;
                        response.customer_list
                            .sort((a, b) => a.name.localeCompare(b.name))
                            .forEach(c => {
                                options += `<option value="${c.id}">${c.name}</option>`;
                            });
                        $('#order-select-customer').html(options).prop('disabled', false);
                    }

                    const html = renderCustomerTable(items, names, response.orderIds);
                    $('#packing-data-customer').html(html);
                }
            },
            error: function () {
                $('#packing-data-customer').html(`<div class="text-danger text-center">Gagal memuat data.</div>`);
            }
        });
    }

    // Untuk Log Print
    function loadLogPrint(keyword = '', page = 1) {
        fetch(`{{ route('packing.data.log-print') }}?keyword=${encodeURIComponent(keyword)}&page=${page}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('packingLogPrintTableBody').innerHTML = data.html;
                document.getElementById('pagination').innerHTML = data.pagination;

                // Re-bind pagination click
                document.querySelectorAll('#pagination a').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = new URL(this.href);
                        const page = url.searchParams.get('page');
                        const keyword = document.getElementById('searchLogPrint').value;
                        loadLogPrint(keyword, page);
                    });
                });
            });
    }

    // Untuk Print Ulang di Log Print
    $(document).on('click', '.btn-print-ulang', function() {
        const logId = $(this).data('log-id');
        reprintLog(logId);
    });

    function reprintLog(logId) {
        fetch(`packing/log-print/${logId}`)
            .then(res => res.json())
            .then(data => {
                const hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

                let besok = new Date();
                besok.setDate(besok.getDate() + 1);
                let formattedDate = hari[besok.getDay()] + ', ' + besok.getDate() + ' ' + bulan[besok.getMonth()] + ' ' + besok.getFullYear();

                const title = data.group_name
                    ? `Packing Kirim (${formattedDate})`
                    : `Packing Kirim (${formattedDate})`;

                let html = '';
                if (data.group_name) {
                    // GROUP MODE
                    html += `
                        <div class="text-center">
                            <h5 class="text-white d-inline-block bg-gradient-primary mb-4 py-2 px-3 rounded-pill">
                                Group : ${data.group_name}
                            </h5>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Komoditi</th>
                                    <th class="text-center">Satuan</th>
                                    <th class="text-center">Total</th>`;

                    Object.values(data.customers).forEach(cust => {
                        html += `<th class="text-center">${cust}</th>`;
                    });

                    html += `</tr></thead><tbody>`;

                    Object.entries(data.grouped).forEach(([komoditi, row], index) => {
                        html += `
                            <tr>
                                <td class="text-center">${index + 1}</td>
                                <td class="text-center">${komoditi}</td>
                                <td class="text-center">${row.unit}</td>
                                <td class="text-center">${row.total}</td>`;
                        Object.keys(data.customers).forEach(custId => {
                            html += `<td class="text-center">${row.quantities[custId] ?? 0}</td>`;
                        });
                        html += `</tr>`;
                    });

                    html += `</tbody></table>`;
                } else {
                    // CUSTOMER MODE
                    html += `
                        <div class="text-center mb-4">
                            <h5 class="text-white d-inline-block bg-gradient-primary py-2 px-4 rounded-pill">
                                Customer : ${data.customer_name}
                            </h5>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Komoditi</th>
                                    <th class="text-center">Satuan</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>`;

                    Object.entries(data.items).forEach(([name, item], index) => {
                        html += `
                            <tr>
                                <td class="text-center">${index + 1}</td>
                                <td class="text-center">${name}</td>
                                <td class="text-center">${item.unit}</td>
                                <td class="text-center">${item.quantity}</td>
                            </tr>`;
                    });

                    html += `</tbody></table>`;
                }

                // PRINT WINDOW
                let printWindow = window.open('', '', `left=0,top=0,width=${screen.width},height=${screen.height}`);
                printWindow.document.write(`
                    <html>
                    <head>
                        <title>Print Packing</title>
                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
                        <style>
                            body { padding: 20px; font-size: 12px; }
                            h5 { font-size: 14px; }
                            table { width: 100%; border-collapse: collapse; }
                            table th, table td { border: 1px solid #000; padding: 6px; text-align: center; }
                            h4 { margin-bottom: 20px; text-align: center; }
                        </style>
                    </head>
                    <body>
                        <h4>${title}</h4>
                        ${html}
                    </body>
                    </html>
                `);
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            })
            .catch(err => {
                console.error('Gagal print ulang:', err);
                alert('Gagal memuat data print ulang.');
            });
    }

    document.addEventListener('DOMContentLoaded', () => loadLogPrint());

    document.getElementById('searchLogPrint').addEventListener('keyup', function () {
        loadLogPrint(this.value);
    });
</script>

@endsection