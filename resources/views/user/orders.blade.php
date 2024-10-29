@extends('user.layout')

@section('title','orders');
@section('header')
    <ion-title>Order History</ion-title>
    <ion-button slot="start" href="/user/dashboard">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection
@section('content')
<div class="container">
        <!-- Button Container for Deposit and Withdraw -->
        <div class="mt-3 nav nav-pills">
            <div class="col nav-item text-center rounded">
                <a class=" btn-dark nav-link active " data-bs-toggle="pill" data-bs-target="#deposit">Pending</a>
            </div>
            <div class="col nav-item text-center rounded">
                <a class=" nav-link " data-bs-toggle="pill" data-bs-target="#withdraw">Completed</a>
            </div>
        </div>

        <div class="tab-content mt-2" id="pills-tabContent">
            <div class="tab-pane fade show active" id="deposit" role="tabpanel" aria-labelledby="pills-home-tab">
                @php
                    $pending = App\Models\TaskLog::where('user_id', Auth::user()->id)->where('completed',false)->get();
                    $completed = App\Models\TaskLog::where('user_id', Auth::user()->id)->where('completed',true)->get();
                @endphp
                <ion-card>
                    
                    <ion-card-content>
                        <ion-lists>
                            @forelse ($pending as $deposit)
                                <ion-item>
                                    <ion-label>Deposit Transaction</ion-label>
                                    <ion-label
                                        color="{{ $deposit->status == 'success' ? 'success' : '' }}{{ $deposit->status == 'failed' ? 'danger' : ''}}">
                                        {{ $deposit->status }}</ion-label>
                                    <ion-label>${{number_format($deposit->amount,2)}}</ion-label>
                                </ion-item>
                            @empty
                                <ion-item>
                                    <ion-label>No Pending Order found.</ion-label>
                                </ion-item>
                            @endforelse
                        </ion-lists>
                    </ion-card-content>
                </ion-card>

            </div>
            <div class="tab-pane fade" id="withdraw" role="tabpanel" aria-labelledby="pills-profile-tab">
                <ion-card>
                   
                    <ion-card-content>
                        <ion-lists>
                            @forelse ($completed as $withdrawal)
                                <ion-item>
                                    <ion-label>Withdrawal Transaction</ion-label>
                                    <ion-label
                                        color="{{ $withdrawal->status == 'success' ? 'success' : '' }} {{ $withdrawal->status == 'processing' ? 'warning' : '' }} {{ $withdrawal->status == 'failed' ? 'danger' : '' }}">
                                        {{ $withdrawal->status }}</ion-label>
                                    <ion-label>${{number_format($withdrawal->amount,2)}}</ion-label>
                                </ion-item>
                            @empty
                                <ion-item>
                                    <ion-label>No completed order found.</ion-label>
                                </ion-item>
                            @endforelse
                        </ion-lists>
                    </ion-card-content>
                </ion-card>

            </div>
        </div>
    </div>
@endsection