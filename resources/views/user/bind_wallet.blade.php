@extends('user.layout')

@section('title', 'Wallet')
@section('header')
    <ion-title>Your Wallet</ion-title>
    <ion-button slot="start" href="/user/profile">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection

@section('content')

    <ion-card class="ion-padding" mode="ios">
        <ion-card-header class="text-center">
            <ion-title>
                Withdrawal Wallet
            </ion-title>
        </ion-card-header>
        <ion-card-body>
            <p>Dear users, in order to protect the security of your funds, please do not enter your bank card password. Our
                staff will not ask you to enter your bank card PIN.</p>
            <br>
            <ion-list>
                <form action="" id="walletForm">
                    @csrf
                    <ion-input placeholder="Wallet" class="form-control p-1 mb-2" value="USDT TRC20" readonly></ion-input>
                    <ion-input placeholder="Enter USDTTRC20 Address" class="form-control p-1 mb-2" id="wallet" name="wallet"></ion-input>

                    <ion-button type="submit" expand="block">Modify Wallet</ion-button>
                </form>
            </ion-list>
        </ion-card-body>
    </ion-card>

@endsection

@section('script')
    <script>
        const loading = document.querySelector('ion-loading');
        const alertCustom = document.querySelector('ion-alert');
        const toast = document.querySelector('ion-toast');

        $('#walletForm').submit((e) => {
            e.preventDefault();
            loading.message = "Binding Wallet..."
            loading.present();

            if($('#wallet').val() == "") {
                loading.dismiss();
                alertCustom.message = "Please enter a wallet address.";
                alertCustom.buttons = [{
                        text:"Ok",
                        handler: ()=>{
                            alertCustom.isOpen = false;
                        }
                    }];
                alertCustom.present();
                return false;
            }
            // Check if User has wallet information
            $.ajax({
                type: "get",
                url: "/user/check-withdraw-wallet",
                success: function(response) {
                    if (response.wallet_exists == true) {
                        loading.dismiss();
                        alertCustom.message =
                            "You have a wallet bounded already. By Continuing we will update your wallet with the new information";
                        alertCustom.buttons = [{
                                text: "Cancel",
                                role: "cancel",
                                handler: () => {}
                            },
                            {
                                text: 'Confirm',
                                role: 'confirm',
                                handler: () => {
                                    loading.message = "Processing please wait...";
                                    loading.present();
                                    bindWallet();
                                }
                            }
                        ]
                        alertCustom.present();
                    } else {
                        bindWallet();
                    }

                }
            });
        });

        const bindWallet = function() {
            $.ajax({
                type: "post",
                url: "/user/bind-wallet",
                data: new FormData($('#walletForm')[0]),
                contentType: false,
                processData: false,
                success: function (response) {
                    loading.dismiss();
                    alertCustom.message = response.message;
                    alertCustom.buttons = [{
                        text:"Ok",
                        handler: ()=>{
                            alertCustom.isOpen = false;
                        }
                    }];
                    alertCustom.isOpen = true;
                },
                error: function(xhr, status, error) {
                    loading.dismiss();
                    alertCustom.message = xhr.responseJSON.message;
                    alertCustom.buttons = [{
                        text:"Ok",
                        handler: ()=>{
                            alertCustom.isOpen = false;
                        }
                    }];
                    alertCustom.isOpen = true;
                }
            });
        };
    </script>
@endsection
