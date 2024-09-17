@extends('user.layout')

@section('title', 'history')

@section('header')
    <ion-title>History</ion-title>
    <ion-button slot="start" href="/user/dashboard">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection

<style>
    .nav .nav-item .active {
        background-color: black !important;
        color: white;
        border: none;
    }
</style>

@section('content')
    <div class="container">
        <!-- Button Container for Deposit and Withdraw -->
        <div class="mt-3 nav nav-pills">
            <div class="col nav-item text-center rounded">
                <a class=" btn-dark nav-link active " data-bs-toggle="pill" data-bs-target="#deposit">Deposits</a>
            </div>
            <div class="col nav-item text-center rounded">
                <a class=" nav-link " data-bs-toggle="pill" data-bs-target="#withdraw">Withdraws</a>
            </div>
        </div>

        <div class="tab-content mt-2" id="pills-tabContent">
            <div class="tab-pane fade show active" id="deposit" role="tabpanel" aria-labelledby="pills-home-tab">
                @php
                    $deposits = App\Models\Transactions::where('type', 'deposit')->get();
                    $withdrawals = App\Models\Transactions::where('type', 'withdrawal')->get();
                @endphp
                <ion-card>
                    <ion-card-header>
                        <ion-card-subtitle>Deposit History</ion-card-subtitle>
                        <ion-card-title>Total Deposits: ${{ number_format($deposits->sum('amount'), 2) }}</ion-card-title>
                    </ion-card-header>
                    <ion-card-content>
                        <ion-lists>
                            @forelse ($deposits as $deposit)
                                <ion-item>
                                    <ion-label>Deposit Transaction</ion-label>
                                    <ion-label
                                        color="{{ $deposit->status == 'success' ? 'success' : '' }}">
                                        {{ $deposit->status }}</ion-label>
                                    <ion-label>${{number_format($deposit->amount,2)}}</ion-label>
                                </ion-item>
                            @empty
                                <ion-item>
                                    <ion-label>No deposit history found.</ion-label>
                                </ion-item>
                            @endforelse
                        </ion-lists>
                    </ion-card-content>
                </ion-card>

            </div>
            <div class="tab-pane fade" id="withdraw" role="tabpanel" aria-labelledby="pills-profile-tab">
                <ion-card>
                    <ion-card-header>
                        <ion-card-subtitle>Withdraw History</ion-card-subtitle>
                        <ion-card-title>Total Withdraws: ${{number_format($withdrawals->sum('amount'),2)}}</ion-card-title>
                    </ion-card-header>
                    <ion-card-content>
                        <ion-lists>
                            @forelse ($withdrawals as $withdrawal)
                                <ion-item>
                                    <ion-label>Withdrawal Transaction</ion-label>
                                    <ion-label
                                        color="{{ $withdrawal->status == 'success' ? 'success' : '' }} {{ $withdrawal->status == 'processing' ? 'warning' : '' }} {{ $withdrawal->status == 'failed' ? 'danger' : '' }}">
                                        {{ $deposit->status }}</ion-label>
                                    <ion-label>${{number_format($withdrawal->amount,2)}}</ion-label>
                                </ion-item>
                            @empty
                                <ion-item>
                                    <ion-label>No withdraw history found.</ion-label>
                                </ion-item>
                            @endforelse
                        </ion-lists>
                    </ion-card-content>
                </ion-card>

            </div>
        </div>
    </div>
@endsection
