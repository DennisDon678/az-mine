@extends('user.layout')

@section('header')
    <ion-title>Withdraw</ion-title>
    <ion-button slot="start" href="/user/dashboard">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection

<style>

</style>


@section('content')
    <ion-content class="ion-padding">

        <!-- Withdrawal Form Section -->
        <div class="withdrawal-form ">
            <h2>Withdrawal amount: <small style="color: red;" >Handling fee 0%</small></h2>
            <ion-item>
                <ion-input type="number" placeholder="Enter the withdrawal amount"></ion-input>
            </ion-item>

            <ion-item>
                <ion-label>Balance: ${{number_format(Auth::user()->balance,2)}}</ion-label>
            </ion-item>

            <ion-item>
                <ion-label>Wallet address:</ion-label>
            </ion-item>

            <ion-item>
                <ion-label>Method: Eth</ion-item>

            <ion-item>
                <ion-input type="password" placeholder="Withdrawal Password"></ion-input>
            </ion-item>

            <!-- Confirm Button -->
            <div class="confirm-button" style="height: 50px;">
                <ion-button expand="block" color="dark">Confirm</ion-button>
            </div>
        </div>

        <!-- Friendly Reminders Section -->
        <div class="friendly-reminders">
            <h4>Friendly Reminders</h4>
            <p>1. Before submitting your withdrawal application, please set your transaction password and bind your TRC20
                withdrawal address. You can submit withdrawal applications in 24 hours.</p>
            <p>2. The amount of every single withdrawal is between $100 ~ $1,000,000. You can make withdrawals 3 times
                daily.
            </p>
            <p>3. Every member will receive their funds within 30 ~ 60 minutes after they make the withdrawal request at the
                platform. There will be a transaction handling fee for every withdrawal and the minimum amount for every
                withdrawal is $1.</p>
        </div>
    </ion-content>
@endsection
