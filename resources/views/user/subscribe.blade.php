@extends('user.layout')

@section('header')
    <ion-title>Subscribe</ion-title>
    <ion-button slot="start" href="/user/dashboard">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection


@section('content')
    <ion-card class="ion-padding">
        <div class="mt-2 text-center">
            <ion-card-heading>
                <ion-title>
                    Package Details
                </ion-title>
            </ion-card-heading>
        </div>

        {{-- create deposit form --}}
        <ion-item mode="ios">
            <ion-label slot="start">Package</ion-label>
            <ion-input type="text" name="package" value="{{ $package->package_name }}" label="" readonly></ion-input>
        </ion-item>

        <ion-item mode="ios">
            <ion-label slot="start">Price($):</ion-label>
            <ion-input type="text" placeholder="Enter amount to deposit" name="amount"
                value="${{ number_format($package->package_price,2) }}" label="" readonly required></ion-input>
        </ion-item>

        <ion-item mode="ios">
            <ion-label slot="start">Percentage Profit:</ion-label>
            <ion-input type="number" name="amount" value="{{ $package->percentage_profit }}" label=""
                readonly required></ion-input>
        </ion-item>
        <ion-item mode="ios">
            <ion-label slot="start">Number of Set:</ion-label>
            <ion-input type="number" placeholder="Enter amount to deposit" name="set"
                value="{{ $package->set }}" label="" label-placement="start" readonly required></ion-input>
        </ion-item>

        <ion-item mode="ios">
            <ion-label slot="start">Number of Orders per Set:</ion-label>
            <ion-input type="number" placeholder="Enter amount to deposit" name="amount"
                value="{{ $package->number_of_orders_per_day }}" label="" label-placement="start" readonly required></ion-input>
        </ion-item>

        <ion-button type="button" expand="block" color="dark" id="confirm">Activate Package</ion-button>
    </ion-card>
@endsection()


@section('script')
    <script>
        const alertCustom = document.querySelector('ion-alert');
        const loading = document.querySelector('ion-loading');
        $('#confirm').click(function() {
            alertCustom.message = "Are you sure You want to activate this package?"
            alertCustom.buttons = [

                {
                    text: 'cancel',
                },
                {
                    text: 'confirm',
                    handler: function() {
                        loading.message = "Processing";
                        loading.present();

                        $.ajax({
                            type: "get",
                            url: "/user/subscribe?activate={{ $package->id }}",
                            success: function(response) {
                                loading.dismiss()
                                if (response === 1) {
                                    alertCustom.message =
                                        "Your Plan Has been Activated Successfully.";
                                    alertCustom.buttons = [{
                                        text: "close",
                                        handler: () => {
                                            loading.message = "Redirecting";
                                            loading.present();

                                            // Redirect to login page
                                            setTimeout(() => {
                                                window.location.href =
                                                    "/user/dashboard";
                                            }, 1000);
                                        }
                                    }];

                                    alertCustom.present();
                                }else if(response == 3){
                                    alertCustom.message =
                                        "You Do Not Have Enough Balance to Access this Package.";
                                    alertCustom.buttons = [{
                                        text: "close",
                                        handler: () => {
                                           
                                        }
                                    }];

                                    alertCustom.present();
                                }

                            },
                        });
                    }
                },
            ];
            alertCustom.present();
        })
    </script>
@endsection
