@extends('auth.layout')

@section('title', 'Register page')

<style>
    .main {
        max-width: 950px;
        margin: auto;
    }
</style>

@section('content')
    <ion-content>
        <div class="main">
            <div>
                <ion-header>
                    <ion-toolbar color="primary">
                        <ion-title>{{ env('APP_NAME') }}</ion-title>
                    </ion-toolbar>
                </ion-header>
            </div>

            <div class="ion-padding">
                <div class="mt-3">
                    <ion-card>
                        <ion-card-header mode="md">
                            <ion-card-title>
                                <div class="h2 text-center ">
                                    <h2 class="font-weight-bold" style="font-weight:bold!important;"> Register
                                        {{ env('APP_NAME') }} Account</h2>
                                </div>
                            </ion-card-title>
                            <p class="text-center">Register and claim your reward by helping our algorithm process orders
                                faster.</p>

                        </ion-card-header>

                        <ion-card-content mode="ios">
                            <div class="input-system">
                                <form action="" id="register">
                                    @csrf
                                    <ion-item>
                                        <ion-icon name="person-circle-outline" slot="start"></ion-icon>
                                        <ion-input placeholder="Create a Unique Username" name="username" id="username"
                                            fill=""></ion-input>
                                    </ion-item>
                                    <br>
                                    <ion-item>
                                        <ion-icon name="call-outline" slot="start"></ion-icon>
                                        <ion-input type="tel" placeholder="Enter Your Phone Number" name="phone"
                                            id="phone" fill=""></ion-input>
                                    </ion-item>
                                    <br>
                                    <ion-item>
                                        <ion-icon name="lock-closed-outline" slot="start"></ion-icon>
                                        <ion-input type="password" placeholder="Create Your Password" name="password"
                                            fill="" id="password"></ion-input>
                                    </ion-item>
                                    <br>
                                    <ion-item>
                                        <ion-icon name="lock-closed-outline" slot="start"></ion-icon>
                                        <ion-input type="password" placeholder="Confirm Your Password"
                                            name="password_confirmation" fill=""
                                            id="password_confirmation"></ion-input>
                                    </ion-item>
                                    <br>
                                    <ion-item>
                                        <ion-icon name="enter-outline" slot="start"></ion-icon>
                                        <ion-input type="text" placeholder="Enter Invitation code (optional)"
                                            fill="" id="referral_code" name="referred_by"></ion-input>
                                    </ion-item>
                                    <br>
                                    <ion-item>
                                        <ion-icon name="shield-checkmark-outline" slot="start"></ion-icon>
                                        <ion-input type="number" placeholder="Enter Verification Code" fill=""
                                            id="captcha"></ion-input>
                                    </ion-item>

                                    <div class="code mt-2 text-center">
                                        <h4>Verification Code is:</h4>
                                        <h2 class="text-success" id="verificationCode">{{ $code }}</h2>
                                    </div>

                                    <div class="log-btn mt-3">
                                        <ion-item>
                                            <ion-button type="submit" style="width: 100%;" id="register">Register
                                                <ion-icon name="caret-forward-outline" slot="end"></ion-icon>
                                            </ion-button>
                                        </ion-item>
                                    </div>
                                </form>
                            </div>
                            <div class="or text-center mt-2">
                                <h3>OR</h3>
                            </div>
                            <div class="register text-center mt-2">
                                <a href="/auth/login" class="text-decoration-none">Login to Your Account</a>
                            </div>
                        </ion-card-content>
                    </ion-card>
                </div>
            </div>
        </div>

    </ion-content>
@endsection

@section('script')

    <script type="module">
        $(document).ready(function() {
            const loading = document.querySelector('ion-loading');
            const alertCustom = document.querySelector('ion-alert');
            $('#register').submit(function(e) {
                e.preventDefault();
                loading.message = "Registering..."
                loading.present();

                // Check inputs
                const username = $('#username').val();
                // const email = $('#email').val();
                const phone = $('#phone').val();
                const password = $('#password').val();
                const password_confirmation = $('#password_confirmation').val();
                const referral_code = $('#referral_code').val();
                const captcha = $('#captcha').val();

                // for empty input
                if (!username || !phone || !password || !password_confirmation || !captcha) {
                    loading.dismiss();
                    alertCustom.message = "Please enter all required fields"
                    alertCustom.buttons = [{
                        text: 'OK',
                        handler: () => {
                            console.log('Alert closed');
                        }
                    }]
                    alertCustom.present();
                    return;
                }
                // // Check if email is valid
                // const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                // if (!emailRegex.test(email)) {
                //     loading.dismiss();
                //     alertCustom.message = "Please enter a valid email address"
                //     alertCustom.buttons = [{
                //         text: 'OK',
                //         handler: () => {
                //             console.log('Alert closed');
                //         }
                //     }]
                //     alertCustom.present();
                //     return;
                // }

                // Check if phone number is valid
                const phoneRegex = /^\+?[1-9]\d{1,14}$/;
                if (!phoneRegex.test(phone)) {
                    loading.dismiss();
                    alertCustom.message = "Please enter a valid phone number e.g. +1234567890"
                    alertCustom.buttons = [{
                        text: 'OK',
                        handler: () => {
                            console.log('Alert closed');
                        }
                    }]
                    alertCustom.present();
                    return;
                }

                // Check if password is same as password_confirmation
                if (password !== password_confirmation) {
                    loading.dismiss();
                    alertCustom.message = "Passwords do not match"
                    alertCustom.buttons = [{
                        text: 'OK',
                        handler: () => {
                            console.log('Alert closed');
                        }
                    }]
                    alertCustom.present();
                    return;
                }

                // check if captcha is same as the text in the element with id confirmationCode
                const confirmationCodeElement = document.getElementById('verificationCode');
                if (captcha.toLowerCase() !== confirmationCodeElement.textContent.toLowerCase()) {
                    loading.dismiss();
                    alertCustom.message = "Verification code is incorrect"
                    alertCustom.buttons = [{
                        text: 'OK',
                        handler: () => {
                            console.log('Alert closed');
                        }
                    }]
                    alertCustom.present();
                    return;
                }

                // Send data to server for registration
                var form = document.getElementById('register');
                $.ajax({
                    type: "post",
                    url: "/auth/register",
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status == 'success') {

                            loading.dismiss();
                            alertCustom.message = response.message
                            alertCustom.buttons = [{
                                text: "Close",
                                handler: () => {
                                    // Clear form inputs
                                    $('#username').val('');
                                    $('#email').val('');
                                    $('#phone').val('');
                                    $('#password').val('');
                                    $('#password_confirmation').val('');
                                    $('#referral_code').val('');
                                    $('#captcha').val('');
                                    confirmationCodeElement.textContent = '';
                                    console.log(
                                        'Alert closed after a successful response'
                                        );

                                    loading.message = "Redirecting..."
                                    loading.present();

                                    // Redirect to login page
                                    setTimeout(() => {
                                        window.location.href =
                                            "/user/dashboard";
                                    }, 2000);
                                }
                            }]
                            alertCustom.present();
                        } else {
                            loading.dismiss();
                            alertCustom.message = response.message
                            alertCustom.buttons = [{
                                text: 'OK',
                                handler: () => {
                                    console.log('Alert closed');
                                }
                            }]
                            alertCustom.present();
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        loading.dismiss();
                        var error = xhr.responseJSON;
                        alertCustom.message = JSON.stringify(error);
                        alertCustom.buttons = [{
                            text: 'OK',
                            handler: () => {
                                console.log('Alert closed');
                            }
                        }]
                        alertCustom.present();
                    }
                });
            });

        });
    </script>

@endsection
