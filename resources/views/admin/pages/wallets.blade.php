@extends('admin.pages.layout')
@section('header')
    <ion-title>Wallets</ion-title>
    <ion-button slot="start" href="/admin/dashboard">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection
@section('content')
    <ion-card mode="ios" class="ion-padding">
        <ion-card-header class="text-center">
            <ion-card-title>Add New Wallet</ion-card-title>
        </ion-card-header>

        <ion-card-body>
            <ion-button id="newWallet">
                Add a New Wallet
            </ion-button>
        </ion-card-body>
    </ion-card>
    <ion-card mode="ios">
        <ion-card-header class="text-center">
            <ion-card-title>Wallet Lists</ion-card-title>
        </ion-card-header>

        <ion-card-body>
            <ion-list inset="true">
                <ion-grid>
                    @forelse ($wallets as $wallet)
                        <ion-row class="ion-justify-content-between ion-align-items-center">
                            <ion-col size="8">
                                <ion-text>
                                    {{ $wallet->name }}
                                </ion-text>
                            </ion-col>
                            <ion-col size="4">
                                <ion-button color="danger"
                                    href="/admin/delete-wallet?id={{ $wallet->id }}">Delete</ion-button>
                            </ion-col>
                        </ion-row>
                    @empty
                        <ion-item class="text-center text-danger">
                            <ion-label>No User to Show</ion-label>
                        </ion-item>
                    @endforelse
                </ion-grid>
            </ion-list>
        </ion-card-body>
    </ion-card>

    <ion-modal trigger="newWallet" initial-breakpoint="0.50" mode="ios">
        <ion-header>
            <ion-toolbar>
                <ion-title>Add New Wallet</ion-title>
            </ion-toolbar>
        </ion-header>
        <ion-content class="ion-padding">
            <form id="addWalletForm">
                @csrf
                <ion-item mode="ios">
                    <ion-icon name="logo-bitcoin" slot="start"></ion-icon>
                    <ion-input type="text" placeholder="Crypto Name" name="name" required></ion-input>
                </ion-item>
                <ion-item mode="ios">
                    <ion-icon name="cash-outline" slot="start"></ion-icon>
                    <ion-input type="text" placeholder="Crypto Short Name" name="short_name" required></ion-input>
                </ion-item>
                <ion-item mode="ios">
                    <ion-icon name="cash-outline" slot="start"></ion-icon>
                    <ion-input type="text" placeholder="Crypto Network" name="network"></ion-input>
                </ion-item>
                <ion-item mode="ios">
                    <ion-icon name="wallet-outline" slot="start"></ion-icon>
                    <ion-input type="text" placeholder="Crypto Wallet Address" name="wallet" required></ion-input>
                </ion-item>

                <ion-button expand="block" type="submit" color="primary" id="save">Save Wallet</ion-button>
            </form>
        </ion-content>
    </ion-modal>
    </ion-modal>
@endsection

@section('script')
    <script>
        const loading = document.querySelector('ion-loading');
        const alertCustom = document.querySelector('ion-alert');
        const modal = document.querySelector('ion-modal');
        $('#addWalletForm').on('submit', function(e) {
            e.preventDefault();
            loading.message = "Saving Wallet...";
            loading.present();

            $.ajax({
                type: "post",
                url: "/admin/wallet/create",
                data: new FormData(document.getElementById("addWalletForm")),
                contentType: false,
                processData: false,
                success: function(response) {
                    loading.dismiss();
                    alertCustom.message = response.message;
                    alertCustom.buttons = [{
                        text: 'OK',
                        handler: function() {
                            modal.dismiss();
                            location.href = '/admin/wallets'
                        }
                    }];
                    alertCustom.present();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    loading.dismiss();
                    alertCustom.message = "Failed to save wallet. Please try again.";
                    alertCustom.buttons = ['OK'];
                    alertCustom.present();
                }
            });
        });
    </script>
@endsection
