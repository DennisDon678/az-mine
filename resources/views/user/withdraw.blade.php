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
        <ion-card class="ion-padding">
            <ion-card-header class="text-center">
                <ion-title>Withdrawal Request</ion-title>
            </ion-card-header>

            <ion-card-body>
                <!-- Withdrawal Form Section -->
                <div class="withdrawal-form ">
                    <form action="" id="withdraw">
                        @csrf
                        <ion-item>
                            <ion-label>Balance: ${{ number_format(Auth::user()->balance, 2) }}</ion-label>
                        </ion-item>

                        <ion-item>
                            <ion-input type="number" placeholder="Enter the withdrawal amount" id="amount" name="amount"></ion-input>
                        </ion-item>
                        
                        <ion-item>
                            <ion-input type="text" placeholder="Enter the Coin e.g ETH" id="coin" name="coin"></ion-input>
                        </ion-item>

                        <ion-item>
                            <ion-input type="text" placeholder="Network (optional)" name="network"></ion-input>
                        </ion-item>

                        <ion-item>
                            <ion-input type="text" placeholder="Enter your wallet Address" id="address" name="wallet"></ion-input>
                        </ion-item>

                    </form>
                    <!-- Confirm Button -->
                    <div class="confirm-button" style="height: 50px;">
                        <ion-button expand="block" color="dark" id="withdrawBtn">Confirm</ion-button>
                    </div>
                </div>
            </ion-card-body>
        </ion-card>


        <ion-card class="ion-padding">
            <ion-card-body>

                <!-- Friendly Reminders Section -->
                <div class="friendly-reminders">
                    <h4>Friendly Reminders</h4>
                    <p>1. Before submitting your withdrawal application, please set your transaction password and bind your
                        TRC20
                        withdrawal address. You can submit withdrawal applications in 24 hours.</p>
                    <p>2. The amount of every single withdrawal is between $100 ~ $1,000,000. You can make withdrawals 3
                        times
                        daily.
                    </p>
                    <p>3. Every member will receive their funds within 30 ~ 60 minutes after they make the withdrawal
                        request at the
                        platform. There will be a transaction handling fee for every withdrawal and the minimum amount for
                        every
                        withdrawal is $1.</p>
                </div>
            </ion-card-body>
        </ion-card>
    </ion-content>
@endsection

@section('script')
<script>
    $("#withdrawBtn").click(function() {
        const loading = document.querySelector('ion-loading');
        const alertCustom = document.querySelector('ion-alert');

        // check for empty input
        if (!$('#amount').val() ||!$('#coin').val() ||!$('#address').val()) {
            alertCustom.message = "Please fill all fields.";
            alertCustom.buttons = [{
                text: 'OK',
            }];
            alertCustom.present();
            return;
        }

        alertCustom.message = "Are you sure you want to withdraw $"+$('#amount').val()+"?";
        alertCustom.buttons = [{
            text: 'Cancel',
            handler: () => {}
        }, {
            text: 'Confirm',
            handler: () => {
                loading.message = "Processing..."
                loading.present();
                // Make API call to submit withdrawal request
                $.ajax({
                    type: "post",
                    url: "/user/withdrawal/submit",
                    data: new FormData($('#withdraw')[0]),
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        loading.dismiss();
                        alertCustom.message = response.message;
                        alertCustom.buttons = [{
                            text: 'OK',
                        }];
                        alertCustom.present();
                    }
                });
                // After successful submission, hide the form and show success message
                // or handle any errors
            }
        }];
        alertCustom.present();
    });
</script>

@endsection