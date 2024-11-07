@extends('admin.pages.layout')
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
    <ion-button slot="end" href="/admin/profile">
        <ion-icon name="person-circle" mode="ios" size="large"></ion-icon>
    </ion-button>
@endsection

@section('content')
    <div class="">

        <div class="d-sm-flex justify-content-center flex-row gap-3 ion-padding">
            <div class="card card-custom col-sm-6 col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mt-1">Total Balance</h4>
                        <p class="card-balance">${{ number_format($balance, 2) }}</p>
                    </div>
                    <div class="icon bg-success text-white"><ion-icon name="cash-outline"></ion-icon></div>
                </div>
            </div>
            <div class="card card-custom col-sm-6 col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mt-1">Total Users</h4>
                        <p class="card-balance">{{ $users }}</p>
                    </div>
                    <div class="icon bg-danger text-white"><ion-icon name="people-outline"></ion-icon></div>
                </div>
            </div>
        </div>

        <!-- Menu List Section (below the cards) -->
        <div class="">
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
                    <a href="/admin/wallets">
                        <div class="menu-item">
                            <ion-icon name="wallet-outline"></ion-icon>
                            <span>Wallets</span>
                        </div>
                    </a>

                    <a href="/admin/packages">
                        <div class="menu-item">
                            <ion-icon name="bag-outline"></ion-icon>
                            <span>Packages</span>
                        </div>
                    </a>
                     <a href="/admin/task-config">
                        <div class="menu-item">
                            <ion-icon name="cog-outline"></ion-icon>
                            <span>Configs</span>
                        </div>
                    </a>
                    <a href="/admin/referral-config">
                        <div class="menu-item">
                            <ion-icon name="cog-outline"></ion-icon>
                            <span>Referral Setting</span>
                        </div>
                    </a>
                     <a href="/admin/reset-user-task">
                        <div class="menu-item">
                            <ion-icon name="cog-outline"></ion-icon>
                            <span>Reset All Users Task</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <ion-card mode="ios">
            <ion-card-header class="text-center">
                <ion-title>Pending Withdrawals</ion-title>
            </ion-card-header>

            <ion-card-body>
                <ion-list inset="true">
                    @forelse ($withdrawals as $deposit)
                        <ion-item>
                            <ion-grid>
                                <ion-row class="ion-justify-content-between">
                                    <ion-col size="8">
                                        <ion-label>
                                            <p>Deposit ID: {{ $deposit->withdrawal_id }}</p>
                                            <p>Amount: ${{ number_format($deposit->amount, 2) }}</p>

                                        </ion-label>
                                        <a class="btn btn-primary mt-1" id="detail-{{ $deposit->id }}">View Details</a>
                                    </ion-col>
                                    <ion-col size="4">
                                        <ion-row class="ion-justify-content-end">
                                            <ion-col>
                                                <ion-button
                                                    href="/admin/approve-withdrawal?id={{ $deposit->id }}">Approve</ion-button>
                                            </ion-col>
                                            <ion-col>
                                                <ion-button color="danger"
                                                    href="/admin/reject-withdrawal?id={{ $deposit->id }}">Reject</ion-button>
                                            </ion-col>
                                        </ion-row>
                                    </ion-col>
                                </ion-row>
                            </ion-grid>
                        </ion-item>
                        <ion-modal initial-breakpoint="0.9" trigger="detail-{{ $deposit->id }}" mode="ios"
                            id="{{ $deposit->withdrawal_id }}">
                            <ion-header>
                                <ion-toolbar>
                                    <ion-title>Withdrawal Details</ion-title>
                                    <ion-buttons slot="end">
                                        <ion-button
                                            onclick="document.getElementById('{{ $deposit->withdrawal_id }}').dismiss()">
                                            close
                                        </ion-button>
                                    </ion-buttons>
                                </ion-toolbar>
                            </ion-header>

                            <ion-content class="ion-padding">
                                @php
                                    $user = App\Models\User::find($deposit->user_id);
                                @endphp
                                <ion-card>
                                    <ion-card-header>
                                        <ion-title>
                                            User Information
                                        </ion-title>
                                    </ion-card-header>
                                    <ion-card-body>
                                        <ion-item>
                                            <ion-label>Username</ion-label>
                                            <ion-text>{{ $user->username }}</ion-text>
                                        </ion-item>
                                        
                                        <ion-item>
                                            <ion-label>Open Balance</ion-label>
                                            <ion-text>${{ number_format($user->balance, 2) }}</ion-text>
                                        </ion-item>
                                        
                                    </ion-card-body>
                                </ion-card>

                                <ion-card>
                                     <ion-card-header>
                                        <ion-title>
                                            Withdrawal Details
                                        </ion-title>
                                    </ion-card-header>
                                    <ion-card-body>
                                        <ion-item>
                                            <ion-label>Withdrawal ID</ion-label>
                                            <ion-text>{{ $deposit->withdrawal_id }}</ion-text>
                                        </ion-item>
                                        <ion-item>
                                            <ion-label>Amount</ion-label>
                                            <ion-text>${{ number_format($deposit->amount, 2) }}</ion-text>
                                        </ion-item>
                                        <ion-item>
                                            <ion-label>Coin</ion-label>
                                            <ion-text>{{ $deposit->coin }}</ion-text>
                                        </ion-item>
                                        @if($deposit->network)
                                        <ion-item>
                                            <ion-label>Coin Network</ion-label>
                                            <ion-text class="text-wrap">{{ $deposit->network }}</ion-text>
                                        </ion-item>
                                        @endif
                                        <ion-item>
                                            <ion-label>Wallet Address:</ion-label>
                                            <ion-label>{{ $deposit->wallet }}</ion-label>
                                        </ion-item>

                                        {{-- copy wallet address --}}
                                        <div class="mb-2" style="height: 50px;">
                                        <ion-button onclick="copyToClipboard('{{ $deposit->wallet }}')">Copy Wallet Address</ion-button>
                                        </div>
                                </ion-card>
                            </ion-content>
                        </ion-modal>
                    @empty
                        <ion-item class="text-center text-danger">
                            <ion-label>No pending withdrawals</ion-label>
                        </ion-item>
                    @endforelse
                </ion-list>
            </ion-card-body>
        </ion-card>

    </div>
@endsection

@section('script')
    <script>
        const alertCustom = document.querySelector('ion-alert');
        
        document.addEventListener("DOMContentLoaded", function() {
            const cards = document.querySelectorAll('.fa-medal');
            cards.forEach((card, index) => {
                const colors = ['#00BCD4', '#E91E63', '#CDDC39', '#3F51B5', '#FF9800'];
                card.style.color = colors[index % colors.length];
            });
        });

        function copyToClipboard(address) {
            window.navigator.clipboard.writeText(address);

            alertCustom.message = "wallet address copied to clipboard"
            alertCustom.buttons = ['OK'];
            alertCustom.present();
        }

    </script>


@endsection
