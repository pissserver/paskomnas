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

    <div class="row mt-5">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-5">
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <div
                            class="d-flex align-items-center bg-gradient-secondary ps-3 pe-3 py-2 rounded-2 text-white">
                            <i class="fa fa-money-bill me-2"></i>
                            <h6 class="mb-0 text-white">Harga Beli</h6>
                        </div>
                        <div class="d-flex align-items-center">
                            <input type="text" class="form-control form-control-sm me-1" id="searchLogPrint"
                                placeholder="Cari data purchase" name="search" style="max-width: 200px;">
                            <span class="input-group-text">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div id="purchase-log-print mt-3">
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
                            <tbody id="purchaseLogPrintTableBody">

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

<script>
    // Untuk Log Print
    function loadLogPrint(keyword = '', page = 1) {
        fetch(`{{ route('purchase.data.log-print') }}?keyword=${encodeURIComponent(keyword)}&page=${page}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('purchaseLogPrintTableBody').innerHTML = data.html;
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
        fetch(`purchase/log-print/${logId}`)
            .then(res => res.json())
            .then(data => {
                const hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

                let besok = new Date();
                besok.setDate(besok.getDate() + 1);
                let formattedDate = hari[besok.getDay()] + ', ' + besok.getDate() + ' ' + bulan[besok.getMonth()] + ' ' + besok.getFullYear();

                const title = data.group_name
                    ? `Purchasing Kirim (${formattedDate})`
                    : `Purchasing Kirim (${formattedDate})`;

                let html = '';
                if (data.group_name) {
                    // GROUP MODE
                    const sortedCustomerEntries = Object.entries(data.customers)
                    .sort((a, b) => a[1].localeCompare(b[1]));

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

                    sortedCustomerEntries.forEach(([_, custName]) => {
                        html += `<th class="text-center">${custName}</th>`;
                    });

                    html += `</tr></thead><tbody>`;

                    Object.entries(data.grouped).forEach(([komoditi, row], index) => {
                        html += `
                            <tr>
                                <td class="text-center">${index + 1}</td>
                                <td class="text-center commodity">${komoditi}</td>
                                <td class="text-center">${row.unit}</td>
                                <td class="text-center">${row.total}</td>`;
                        sortedCustomerEntries.forEach(([custId, _]) => {
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
                                <td class="text-center commodity">${name}</td>
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
                        <title>Print Purchasing</title>
                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
                        <style>
                            body { padding: 20px; font-size: 11px; }
                            h5 { font-size: 14px; }
                            table { width: 100%; border-collapse: collapse; }
                            table th, table td { border: 1px solid #000; padding: 6px; text-align: center; }
                            table td.commodity { text-align: left; }
                            h4 { margin-bottom: 20px; text-align: center; font-size: 14px; }
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