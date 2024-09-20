@extends('user.layout')

@section('header')
    <ion-title>Fund Wallet</ion-title>
    <ion-button slot="start" href="{{ $amount ? '/user/deposit' : '/user/dashboard' }}">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection


@section('content')
    @if ($amount)
        <ion-card class="ion-padding">
            <div class="mt-2 text-center">
                <ion-card-heading>
                    <ion-title>
                        Funding Details
                    </ion-title>
                </ion-card-heading>
            </div>

            {{-- create deposit form --}}
            <form action="" method="post" enctype="multipart/form-data">
                @csrf
                <ion-item mode="ios">
                    <ion-input type="number" placeholder="Enter amount to deposit" name="amount"
                        value="{{ $amount }}" label="Amount($):" readonly required></ion-input>
                </ion-item>
                <ion-item mode="ios">
                    <ion-input type="text" name="coin"
                        value="{{ $wallet->name }} {{ $wallet->network ? ' - ' . $wallet->network : '' }}"
                        label="Crypto Coin:" readonly></ion-input>
                </ion-item>
                <ion-item mode="ios">
                    <ion-input type="text" name="coin" value="{{ $wallet->wallet }}" label="Wallet Address:"
                        readonly></ion-input>
                </ion-item>

                <div class="px-3 mt-3 mb-1">
                    <ion-label position="float">Wallet QR Code <small class="text-success">scan into any exchange to
                            automatically fill your wallet address</small></ion-label>
                </div>
                <ion-item mode="ios">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $wallet->wallet }}"
                        alt="QR Code" width="200">
                </ion-item>

                <ion-item mode="ios">
                    <ion-input type="file" label="Payment Screenshot:" mode="ios" name="prove"
                        required></ion-input>
                </ion-item>

                <ion-button type="submit" expand="block" color="dark">Deposit</ion-button>
            </form>
        </ion-card>
    @else
        <ion-card class="ion-padding">
            <div class="mt-2 text-center">
                <ion-card-heading>
                    <ion-title>
                        Fund Your Wallet
                    </ion-title>
                </ion-card-heading>
            </div>

            {{-- create deposit form --}}
            <form action="" method="get">
                <ion-item mode="ios">
                    <ion-icon name="cash-outline" slot="start"></ion-icon>
                    <ion-input type="number" placeholder="Enter amount to deposit" name="amount" required></ion-input>
                </ion-item>
                {{-- Select Deposit Method From cash or crypto --}}
                <ion-item mode="ios">
                    <ion-icon name="list-outline" slot="start"></ion-icon>
                    <ion-select placeholder="Select Deposit Method" id="method" name="method">
                        {{-- <ion-select-option value="cash">Cash</ion-select-option> --}}
                        <ion-select-option value="crypto">Crypto</ion-select-option>
                    </ion-select>
                </ion-item>
                @php
                    $cryptos = App\Models\crypto::all();
                @endphp
                <div class="crypto" style="display: none;"">
                    <ion-item mode="ios">
                        <ion-icon name="list-circle-outline" slot="start"></ion-icon>
                        <ion-select placeholder="Select Crypto" name="choice1">
                            @forelse ($cryptos as $crypto)
                                <ion-select-option value="{{ $crypto->id }}">{{ strtoupper($crypto->short_name) }}
                                    {{ $crypto->network ? ' - ' . $crypto->network : '' }}</ion-select-option>
                            @empty
                            @endforelse
                        </ion-select>
                    </ion-item>
                </div>
                <div class="cash" style="display: none;">
                    <ion-item mode="ios">
                        <ion-icon name="list-circle-outline" slot="start"></ion-icon>
                        <ion-select placeholder="Select Cash" name="choice2">
                            <ion-select-option value="cash">Usdt</ion-select-option>
                            <ion-select-option value="crypto">ETH</ion-select-option>
                        </ion-select>
                    </ion-item>
                </div>
                {{-- user email --}}
                <ion-item>
                    <ion-icon name="mail-outline" slot="start"></ion-icon>
                    <ion-input type="email" placeholder="Enter your email address" disabled
                        value="{{ Auth::user()->email }}"></ion-input>
                </ion-item>

                {{-- username --}}
                <ion-item>
                    <ion-icon name="person-circle-outline" slot="start"></ion-icon>
                    <ion-input type="text" placeholder="Your username" disabled
                        value="{{ Auth::user()->username }}"></ion-input>
                </ion-item>
                <ion-button type="submit" expand="block" color="dark">Continue</ion-button>
            </form>
        </ion-card>
    @endif
@endsection


@section('script')
    @if (!$amount)
        <script>
            const cash = document.querySelector('.cash');
            const crypto = document.querySelector('.crypto');
            const method = document.querySelector('#method');

            method.addEventListener('ionChange', (e) => {
                if (e.target.value == 'cash') {
                    cash.style.display = "block";
                    crypto.style.display = "none";
                } else {
                    cash.style.display = "none";
                    crypto.style.display = "block";
                }
            });
        </script>
    @endif
@endsection
