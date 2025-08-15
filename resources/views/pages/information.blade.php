@extends('layouts.user_type.auth')

@section('title', 'Informasi Paskomnas || PISS')

@section('content')

<div class="row">
    <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row align-items-center justify-content-between">
                    <div class="col-md-4 d-flex align-items-center mb-2 mb-md-0">
                        <div class="icon icon-shape bg-info shadow text-center border-radius-md me-3">
                            <i class="fa fa-info-circle text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Informasi</p>
                            <h5 class="font-weight-bolder mb-0">
                                <span class="text-sm font-weight-bolder">SISTEM PASKOMNAS</span>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="accordion mt-5 mb-5 border rounded" id="accordionExample">
    <div class="accordion-item border">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Request Order
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">
                Menu <strong><i>Request Order</i></strong> digunakan untuk menyimpan bukti pre-order dari customer yang
                nantinya akan digunakan untuk membuat data order. Menu ini bersifat <strong>opsional</strong> yaitu bisa
                dipakai
                ataupun tidak sebelum membuat data order.
                <div class="video mt-5">
                    <video width="50%" class="border border-dark border-5"
                        style="border-radius: 10px; overflow: hidden;" controls>
                        <source src="{{ asset('assets/video/request-order.mp4') }}" type="video/mp4">
                        Browser Anda tidak mendukung tag video.
                    </video>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item border">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                Order
            </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">
                Menu <strong><i>Order</i></strong> digunakan untuk membuat pesanan dari customer. Dalam menu order ini,
                admin bisa membuat pesanan dari awal ataupun menggunakan template pesanan yang sudah ada. Jika ada yang
                ingin diganti pada pesanan yang sudah terlanjur dibuat, admin bisa mengedit/mengubah pesanan tersebut.
                <div class="video mt-5">
                    <video width="50%" class="border border-dark border-5"
                        style="border-radius: 10px; overflow: hidden;" controls>
                        <source src="{{ asset('assets/video/order.mp4') }}" type="video/mp4">
                        Browser Anda tidak mendukung tag video.
                    </video>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item border">
        <h2 class="accordion-header" id="headingThree">
            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Proses Order
            </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">
                Menu <strong><i>Proses Order</i></strong> digunakan untuk melakukan pemrosesan order atau pesanan yang
                sudah dibuat di menu order. Admin bisa melakukan pembatalan jika customer membatalkan pesanan, atau
                admin juga bisa melakukan selesaikan pesanan jika proses order sudah sampai dimenu pencetakan invoice.
                <div class="video mt-5">
                    <video width="50%" class="border border-dark border-5"
                        style="border-radius: 10px; overflow: hidden;" controls>
                        <source src="{{ asset('assets/video/proses-order.mp4') }}" type="video/mp4">
                        Browser Anda tidak mendukung tag video.
                    </video>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item border">
        <h2 class="accordion-header" id="headingFour">
            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                Purchase
            </button>
        </h2>
        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">
                Menu <strong><i>Purchase</i></strong> digunakan untuk melakukan print atau pencetakan order yang sudah
                dibuat. Admin bisa melakukan cetak sesuai kondisi, bisa per group customer maupun per customer yang
                tidak memiliki group. Dan jika cetak pertama selesai dan ingin melakukan cetak lagi maka admin bisa
                mencari hasil cetak tadi di log print dan kemudian bisa dicetak kembali.
                <div class="video mt-5">
                    <video width="50%" class="border border-dark border-5"
                        style="border-radius: 10px; overflow: hidden;" controls>
                        <source src="{{ asset('assets/video/purchase.mp4') }}" type="video/mp4">
                        Browser Anda tidak mendukung tag video.
                    </video>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item border">
        <h2 class="accordion-header" id="headingFive">
            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                Packing
            </button>
        </h2>
        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">
                Menu <strong><i>Packing</i></strong> digunakan untuk melakukan print atau pencetakan order yang sudah
                dibuat. Admin bisa melakukan cetak sesuai kondisi, bisa per group customer maupun per customer yang
                tidak memiliki group. Dan jika cetak pertama selesai dan ingin melakukan cetak lagi maka admin bisa
                mencari hasil cetak tadi di log print dan kemudian bisa dicetak kembali.
                <div class="video mt-5">
                    <video width="50%" class="border border-dark border-5"
                        style="border-radius: 10px; overflow: hidden;" controls>
                        <source src="{{ asset('assets/video/packing.mp4') }}" type="video/mp4">
                        Browser Anda tidak mendukung tag video.
                    </video>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item border">
        <h2 class="accordion-header" id="headingSix">
            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                Surat Jalan
            </button>
        </h2>
        <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">
                Menu <strong><i>Surat Jalan</i></strong> digunakan untuk melakukan print atau pencetakan order yang
                sudah
                dibuat sesuai dengan kode order. Pada menu surat jalan ini admin bisa melakukan convert quantity, contoh
                seperti dari KG ingin diubah menjadi Gram.
                <div class="video mt-5">
                    <video width="50%" class="border border-dark border-5"
                        style="border-radius: 10px; overflow: hidden;" controls>
                        <source src="{{ asset('assets/video/surat-jalan.mp4') }}" type="video/mp4">
                        Browser Anda tidak mendukung tag video.
                    </video>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item border">
        <h2 class="accordion-header" id="headingSeven">
            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                Invoice
            </button>
        </h2>
        <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">
                Menu <strong><i>Invoice</i></strong> digunakan untuk melakukan print atau pencetakan order yang
                sudah
                dibuat sesuai dengan kode order. Pada menu invoice ini berisi semua detail barang yang dipesan dan juga
                total harganya.
                <div class="video mt-5">
                    <video width="50%" class="border border-dark border-5"
                        style="border-radius: 10px; overflow: hidden;" controls>
                        <source src="{{ asset('assets/video/invoice.mp4') }}" type="video/mp4">
                        Browser Anda tidak mendukung tag video.
                    </video>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item border">
        <h2 class="accordion-header" id="headingEight">
            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                Customer Group
            </button>
        </h2>
        <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">
                Menu <strong><i>Customer Group</i></strong> digunakan untuk membuat data customer group, dimana
                customer group ini akan menampung dari beberapa customer.
                <div class="video mt-5">
                    <video width="50%" class="border border-dark border-5"
                        style="border-radius: 10px; overflow: hidden;" controls>
                        <source src="{{ asset('assets/video/customer-group.mp4') }}" type="video/mp4">
                        Browser Anda tidak mendukung tag video.
                    </video>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item border">
        <h2 class="accordion-header" id="headingNine">
            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                Customer
            </button>
        </h2>
        <div id="collapseNine" class="accordion-collapse collapse" aria-labelledby="headingNine"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">
                Menu <strong><i>Customer</i></strong> digunakan untuk membuat data customer. Admin bisa mendaftarkan
                atau membuat data customer sesuai dengan formulir pendaftaran customer. Pada menu customer ini admin
                juga bisa membuat daftar harga semua komoditi sesuai dengan harga customer.
                <div class="video mt-5">
                    <video width="50%" class="border border-dark border-5"
                        style="border-radius: 10px; overflow: hidden;" controls>
                        <source src="{{ asset('assets/video/customer.mp4') }}" type="video/mp4">
                        Browser Anda tidak mendukung tag video.
                    </video>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .accordion-button {
        background-color: #fff !important;
        color: #000;
    }

    .accordion-button::after {
        content: "";
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23212529' stroke-width='2'%3e%3cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7' /%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-size: 1.25rem;
        width: 1.25rem;
        height: 1.25rem;
        margin-left: auto;
        transition: transform 0.2s ease-in-out;
    }

    .accordion-button:not(.collapsed)::after {
        transform: rotate(-180deg);
    }

    /* Saat accordion terbuka */
    .accordion-button:not(.collapsed) {
        background-color: #0dcaf0 !important;
        /* Bootstrap info color */
        color: white !important;
    }

    /* Biar icon arrow tetap putih saat aktif */
    .accordion-button:not(.collapsed)::after {
        filter: brightness(0) invert(1);
    }
</style>

@endsection