@extends('user.layout')
@section('title', 'Dashboard')

<style>
    /* Main Content */
    .main-content {
        padding-top: 20px;
        /* Reduced spacing between navbar and cards to 20px */
        width: calc(100% - 600px);
        /* Apply the same margin */
        margin: 0 auto;
    }

    /* Custom Cards (smaller size) */
    .card-custom {
        width: 300px;
        /* Smaller fixed width */
        height: 200px;
        /* Adjust height to fit dropdown */
        margin-bottom: 20px;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    /* Card Icon */
    .card-custom .icon {
        font-size: 24px;
        background-color: #f5f5f5;
        padding: 10px;
        border-radius: 50%;
        display: inline-block;
    }

    /* Circular Progress Indicator (Smaller) */
    .progress-circle {
        position: relative;
        display: inline-block;
        width: 50px;
        /* Smaller circle */
        height: 50px;
        background: conic-gradient(#007bff 80%, #e5e5e5 80%);
        border-radius: 50%;
    }

    .progress-circle span {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 12px;
        /* Smaller text */
        font-weight: bold;
    }

    /* Styling for text */
    .card-title {
        font-size: 14px;
        font-weight: bold;
    }

    .card-balance {
        font-size: 22px;
        /* Smaller font for balance */
        font-weight: bold;
    }

    .dropdown-footer {
        font-size: 12px;
        color: gray;
    }

    /* Card layout */
    .card-container {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
        /* Add spacing between cards */
    }

    /* Menu List Section */
    .menu-list {
        margin-top: 40px;
        /* Added more margin for spacing */
        padding: 30px;
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .menu-list-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .menu-list-header h5 {
        margin: 0;
        font-weight: bold;
    }

    .menu-list-header a {
        font-weight: bold;
        color: purple;
    }

    .menu-item {
        display: inline-block;
        min-width: 120px;
        min-height: 100px;
        text-align: center;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin: 7px;
        padding: 15px;
    }

    .menu-item ion-icon {
        font-size: 24px;
        margin-bottom: 10px;
    }

    .menu-item span {
        display: block;
        font-size: 14px;
        margin-top: 5px;
    }

    /* Added spacing between the menu list and the bottom navbar */
    .menu-list-container {
        margin-bottom: 100px;
    }

    /* Bottom Navbar */
    .bottom-nav-custom {
        background-color: #007bff;
        color: white;
        height: 80px;
        margin: 0 auto;
        width: calc(100% - 570px);
        /* Add side margins */
        border-radius: 5px;
    }

    .bottom-nav-custom .nav-link {
        color: white;
        text-align: center;
    }

    .bottom-nav-custom .nav-link:hover {
        background-color: #0056b3;
    }

    /* Icons and spacing for bottom nav */
    .bottom-nav-custom .nav-link ion-icon {
        font-size: 28px;
        /* Adjust size of Ionicons */
    }

    .bottom-nav-custom .nav-link span {
        display: block;
        font-size: 14px;
        margin-top: 5px;
        /* Space between icon and label */
    }

    /* Custom styling for the user icon in the top navbar */
    .navbar-custom .user-icon {
        width: 35px;
        height: 35px;
        background-color: white;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
    }

    .navbar-custom .user-icon ion-icon {
        font-size: 22px;
        color: #007bff;
    }

    .menu_list a {
        color: black;
    }

    span {
        text-wrap: nowrap;
    }

    .vip-card {
        border: none;
        /* Remove default card border */
        border-radius: 10px;
        /* Add rounded corners */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        /* Add a subtle shadow */
    }

    .card_plan {
        background-image: url('/users/imgs/back.jpg');
        background-position: center;
        background-size: cover;
    }

    .vip-card .card-body {
        padding: 2rem;
        /* Adjust padding as needed */
    }
</style>

@section('header')
    <ion-title>{{ strtoupper(env('APP_NAME')) }}</ion-title>
    <ion-button slot="end" href="/user/profile">
        <ion-icon name="person-circle" mode="ios" size="large"></ion-icon>
    </ion-button>
@endsection

@section('content')
    <div class="" mode="ios">

        <div class="d-sm-flex justify-content-center flex-row gap-3 ion-padding">
            <div class="card card-custom col-sm-8 col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mt-1">Wallet Balance</h4>
                        <p class="card-balance">${{ number_format(Auth::user()->balance, 2) }}</p>
                    </div>
                    <div class="icon bg-success text-white"><ion-icon name="cash-outline"></ion-icon></div>
                </div>
            </div>
            <div class="card card-custom col-sm-6 col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mt-1">Referral Balance</h4>
                        <p class="card-balance">${{ number_format(Auth::user()->referral_earning, 2) }}</p>
                    </div>
                    <div class="icon bg-danger text-white"><ion-icon name="people-outline"></ion-icon></div>
                </div>
            </div>
            {{-- <div class="card card-custom col-sm-4 col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mt-1">Order Wallet</h4>
                        <p class="card-balance">${{ number_format(Auth::user()->order_balance, 2) }}</p>
                    </div>
                    <div class="icon bg-primary text-white"><ion-icon name="cart-outline"></ion-icon></div>
                </div>
            </div> --}}
        </div>

        <!-- Menu List Section (below the cards) -->
        <div class="" mode="ios">
            <div class="pb-2" style="background-color: rgba(182, 179, 179, 0.39)">
                <div class="d-flex px-2 justify-content-between align-items-center">
                    <div class="col-4">
                        <h5>Menu <span style="color:rgb(98, 0, 255);">List</span></h5>
                    </div>
                    <div class="col-8 mt-3">
                        <p class="text-end">Power Digital Marketing</p>
                    </div>
                </div>
                <div class="d-flex horizontal-scrollable overflow-auto menu_list" style="-webkit-scrollbar:0px;">
                    <a href="/user/contact">
                        <div class="menu-item">
                            <ion-icon name="people-outline"></ion-icon>
                            <span>Customer Service</span>
                        </div>
                    </a>
                     <a href="/user/deposit">
                        <div class="menu-item">
                            <ion-icon name="wallet-outline"></ion-icon>
                            <span>Deposit</span>
                        </div>
                    </a>
                   
                    <a href="/user/withdraw">
                        <div class="menu-item">
                            <ion-icon name="cash-outline"></ion-icon>
                            <span>Withdrawal</span>
                        </div>
                    </a>
                    <a href="/user/transfer">
                        <div class="menu-item">
                            <ion-icon name="cloud-upload-outline"></ion-icon>
                            <span>Transfer</span>
                        </div>
                    </a>
                   
                    <a href="/user/terms-and-conditions">
                        <div class="menu-item">
                            <ion-icon name="document-text-outline"></ion-icon>
                            <span>T&C</span>
                        </div>
                    </a>
                    
                    <a href="">
                        <div class="menu-item">
                            <ion-icon name="help-circle-outline"></ion-icon>
                            <span>FAQs</span>
                        </div>
                    </a>
                    <a href="">
                        <div class="menu-item">
                            <ion-icon name="information-circle-outline"></ion-icon>
                            <span>About</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>


        <div class="" mode="ios">
            <div class="pb-2 pt-3">
                <div class=" mt-3 px-3">
                    <h5>Available <span style="color:rgb(98, 0, 255);">Packages</span></h5>
                </div>

                <div class="container text-light">
                    <div class="row flex-nowrap overflow-auto">
                        @foreach ($package as $package)
                            <div class=" col-6 col-md-4 mb-4">
                                <div class="card card_plan h-100 vip-card">
                                    <div class="card-body text-center text-light">
                                        <i class="fas fa-medal" style="font-size: 3rem;"></i>
                                        <h5 class="card-title mt-3"><strong>{{ $package->package_name }}</strong></h5>
                                        <h5 class="card-title mt-1">
                                            <strong>${{ number_format($package->package_price, 2) }}</strong></h5>
                                        <p class="card-text"><strong>{{ number_format($package->percentage_profit,1) }}%</strong> Profit
                                            rate. <br><strong>{{ $package->number_of_orders_per_day }}</strong> orders per
                                            day. <br><strong>{{number_format($package->daily_profit,1)}}%</strong> daily Profit</p>
                                            

                                        @if ($active)
                                            @if ($active->package_id === $package->id)
                                                <div style="height: 20px;">
                                                    <ion-button expand="block">
                                                        Active
                                                    </ion-button>
                                                </div>
                                            @else
                                                <div style="height: 20px;">
                                                    <ion-button expand="block"
                                                        href="/user/subscribe?package={{ $package->id }}">
                                                        <ion-icon name="cart-outline" slot="start"></ion-icon>
                                                        Activate
                                                    </ion-button>
                                                </div>
                                            @endif
                                        @else
                                            <div style="height: 20px;">
                                                <ion-button expand="block"
                                                    href="/user/subscribe?package={{ $package->id }}">
                                                    <ion-icon name="cart-outline" slot="start"></ion-icon>
                                                    Activate
                                                </ion-button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cards = document.querySelectorAll('.fa-medal');
            cards.forEach((card, index) => {
                const colors = ['#00BCD4', '#E91E63', '#CDDC39', '#3F51B5', '#FF9800'];
                card.style.color = colors[index % colors.length];
            });
        });
    </script>


@endsection
