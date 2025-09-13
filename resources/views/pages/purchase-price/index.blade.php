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

<!-- Modal Input Harga Beli -->
<div class="modal fade" id="modalHargaBeli" tabindex="-1" aria-labelledby="modalHargaBeliLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info text-white">
                <h5 class="modal-title text-white" id="modalHargaBeliLabel">
                    <i class="fas fa-money-bill me-2"></i>Input Harga Beli
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formHargaBeli" method="POST" action="{{ route('purchase-price.store') }}">
                @csrf
                <input type="hidden" name="log_id" id="logIdField">

                <div class="modal-body" id="modalHargaBeliContent" style="max-height: 400px; overflow-y: auto;">
                    <!-- Konten akan diisi via JS -->
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn bg-gradient-info">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Untuk Log Print
    function loadLogPrint(keyword = '', page = 1) {
        fetch(`{{ route('purchase-price.data.log-print') }}?keyword=${encodeURIComponent(keyword)}&page=${page}`)
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
        fetch(`harga-beli/log-print/${logId}`)
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
                                    <th class="text-center">Sat</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Sat</th>
                                    <th class="text-center">Beli</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Total Harga</th>`;
                    html += `</tr></thead><tbody>`;

                    Object.entries(data.grouped).forEach(([komoditi, row], index) => {
                        html += `
                            <tr>
                                <td class="text-center">${index + 1}</td>
                                <td class="text-center commodity">${komoditi}</td>
                                <td class="text-center">${row.unit}</td>
                                <td class="text-center">${row.total}</td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>`;

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
                                    <th class="text-center">Sat</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Sat</th>
                                    <th class="text-center">Beli</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Total Harga</th>
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
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
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
                            body { padding: 0px; font-size: 11px; }
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

    // Modal Input Harga Beli
    $(document).on("click", ".btn-input-harga", function () {
        let logId = $(this).data("log-id");

        $("#logIdField").val(logId);

        // Ambil data dari backend (sama seperti reprintLog)
        fetch(`harga-beli/log-print/${logId}`)
            .then(res => res.json())
            .then(data => {
                let html = `
                    <input type="hidden" name="log_print_id" value="${logId}">
                    <h6 class="mb-4 btn bg-gradient-primary text-white py-2 px-3 rounded-pill">
                        <i class="fas fa-briefcase me-2"></i>
                        ${data.group_name ? `Group : ${data.group_name}` : `Customer : ${data.customer_name}`}
                    </h6>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama Komoditi</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Total Qty</th>
                                <th class="text-center">Satuan Beli</th>
                                <th class="text-center">Qty Beli</th>
                                <th class="text-center">Harga Beli</th>
                                <th class="text-center">Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                if (data.group_name) {
                    // GROUP MODE
                    Object.entries(data.grouped).forEach(([komoditi, row], index) => {
                        html += `
                            <tr>
                                <td class="text-center" style="vertical-align: middle;">${index + 1}</td>
                                <td class="text-center" style="vertical-align: middle;">
                                    ${komoditi}
                                    <input type="hidden" name="goods_id[]" value="${row.goods_id}">
                                    <input type="hidden" name="specification_id[]" value="${row.specification_id ?? ''}">
                                    ${row.order_ids.map(orderId => `<input type="hidden" name="order_id[]" value="${orderId}">`).join('')}
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    ${row.unit}
                                    <input type="hidden" name="unit[]" value="${row.unit}">
                                </td>
                                <td class="text-center" style="vertical-align: middle;">${row.total}</td>
                                <td><input type="text" name="unit_purchase[]" class="form-control" placeholder="Satuan Beli" value="${row.unit_purchase != row.unit ? row.unit_purchase : ''}"></td>
                                <td><input type="number" name="quantity[]" step="any" class="form-control" placeholder="0" value="${row.quantity ? row.quantity : ''}"></td>
                                <td><input type="number" name="purchase_price[]" class="form-control" placeholder="0" value="${row.purchase_price ? row.purchase_price : ''}"></td>
                                <td><input type="number" name="total_price[]" class="form-control" placeholder="0" value="${row.total_price ? row.total_price : ''}"></td>
                            </tr>
                        `;
                    });
                } else {
                    // CUSTOMER MODE
                    Object.entries(data.items).forEach(([name, item], index) => {
                        html += `
                            <tr>
                                <td class="text-center" style="vertical-align: middle;">${index + 1}</td>
                                <td class="text-center" style="vertical-align: middle;">
                                    ${name}
                                    <input type="hidden" name="goods_id[]" value="${item.goods_id}">
                                    <input type="hidden" name="specification_id[]" value="${item.specification_id ?? ''}">
                                    ${item.order_ids.map(orderId => `<input type="hidden" name="order_id[]" value="${orderId}">`).join('')}
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    ${item.unit}
                                    <input type="hidden" name="unit[]" value="${item.unit}">
                                </td>
                                <td class="text-center" style="vertical-align: middle;">${item.quantity}</td>
                                <td><input type="text" name="unit_purchase[]" class="form-control" placeholder="Satuan Beli" value="${item.unit_purchase != item.unit ? item.unit_purchase : ''}"></td>
                                <td><input type="number" name="quantity[]" step="any" class="form-control" placeholder="0" value="${item.quantity_purchase ? item.quantity_purchase : ''}"></td>
                                <td><input type="number" name="purchase_price[]" class="form-control" placeholder="0" value="${item.purchase_price ? item.purchase_price : ''}"></td>
                                <td><input type="number" name="total_price[]" class="form-control" placeholder="0" value="${item.total_price ? item.total_price : ''}"></td>
                            </tr>
                        `;
                    });
                }

                html += `</tbody></table>`;

                // isi konten modal
                $("#modalHargaBeliContent").html(html);

                // tampilkan modal
                let modal = new bootstrap.Modal(document.getElementById("modalHargaBeli"));
                modal.show();
            })
            .catch(err => {
                console.error("Gagal load data:", err);
                alert("Gagal memuat data untuk input harga beli.");
            });
    });

</script>

@endsection