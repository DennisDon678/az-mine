@extends('user.layout')
@section('header')
    <ion-title>Transfer</ion-title>
    <ion-button slot="start" href="/user/dashboard">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection

@section('content')
    <ion-card mode="ios" class="ion-padding">
        <ion-card-header class="text-center">
            <ion-title>InterWallet Transfer</ion-title>
        </ion-card-header>

        <ion-card-body class="ion-padding">
            <form action="" id="transfer">
                @csrf
                <ion-list>
                    <ion-item>
                        <ion-select placeholder="Select From Wallet" name="from" id="from" interface="action-sheet">
                            <ion-select-option value="0">Open Wallet ${{number_format(Auth::user()->balance,2)}}</ion-select-option>
                            <ion-select-option value="1">Referral Wallet ${{number_format(Auth::user()->referral_earning,2)}}</ion-select-option>
                            <ion-select-option value="2">Order Wallet ${{number_format(Auth::user()->order_balance,2)}}</ion-select-option>
                        </ion-select>
                    </ion-item>

                    <div class="text-center my-3">
                        <ion-icon name="arrow-down-outline" size="large"></ion-icon>
                    </div>

                    <ion-item>
                        <ion-select placeholder="Select To Wallet" name="to" id="to" interface="action-sheet">
                            <ion-select-option value="0">Open Wallet ${{number_format(Auth::user()->balance,2)}}</ion-select-option>
                            <ion-select-option value="2">Order Wallet ${{number_format(Auth::user()->order_balance,2)}}</ion-select-option>
                        </ion-select>
                    </ion-item>

                    <ion-item class="mt-2">
                        <ion-input placeholder="Amount to transfer e.g 100" type="number" name="amount" id="amount"></ion-input>
                    </ion-item>
                </ion-list>
                <div class="mt-3"></div>

                <ion-button type="submit" expand="block" color="dark">
                    <ion-icon name="return-down-forward-outline" slot="start"></ion-icon>
                    CONVERT
                </ion-button>
            </form>
        </ion-card-body>
    </ion-card>
@endsection

@section('script')
<script>
    const loading = document.querySelector('ion-loading');
    const alertCustom = document.querySelector('ion-alert');
    $('#transfer').submit(function(e){
        e.preventDefault();
        loading.message = "Transfering...";
        loading.present();

        // Check if from is equal to wallet
        if ($('#from').val() == $('#to').val()) {
            loading.dismiss();
            alertCustom.message = 'From and To Wallets cannot be the same.';
            alertCustom.buttons = [{
                text: 'OK',
                handler: () => {
                    console.log('Alert closed');
                }
            }];
            alertCustom.present();
            return false;
        }

        // check if amount is empty
        if (!$('#amount').val()) {
            loading.dismiss();
            alertCustom.message = "Please enter amount to transfer.";
            alertCustom.buttons = [{
                text: 'OK',
                handler: () => {
                    console.log('Alert closed');
                }
            }];
            alertCustom.present();
            return false;
        }

        // perform ajax request
        $.ajax({
            url: '/user/transfer',
            type: 'POST',
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function(response) {
                loading.dismiss();
                alertCustom.message = response.message;
                alertCustom.buttons = [{
                    text: 'OK',
                    handler: () => {
                        location.href ="/user/dashboard"
                    }
                }];
                alertCustom.present();
            }
        });
    })
</script>

@endsection
