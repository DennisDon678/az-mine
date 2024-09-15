@extends('auth.layout')

@section('title', 'Forgot Password page')

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
                <div class="mt-5">
                    <ion-card>
                        <ion-card-header>
                            <ion-card-title>
                                <div class="h2 text-center ">
                                    <h2 class="font-weight-bold" style="font-weight:bold!important;">Reset Password</h2>
                                </div>
                            </ion-card-title>
                        </ion-card-header>

                        <ion-card-content mode="ios">
                            <div class="input-system">
                                <form action="" id="login">
                                    @csrf
                                    <ion-item>
                                        <ion-icon name="mail-outline" slot="start"></ion-icon>
                                        <ion-input placeholder="Enter Your Account Email" name="username" fill=""></ion-input>
                                    </ion-item>
                                    
                                    <div class="log-btn mt-5">
                                        <ion-item>
                                            <ion-button type="submit" style="width: 100%;">Send Recovery Mail
                                                <ion-icon name="caret-forward-outline" slot="end"></ion-icon>
                                            </ion-button>
                                        </ion-item>
                                    </div>
                                </form>
                            </div>
                            <div class="forgot text-center mt-3">
                                <a href="/auth/login" class="text-decoration-none">Remembered Password?</a>
                            </div>
                            <div class="or text-center mt-2">
                                <h3>OR</h3>
                            </div>
                            <div class="register text-center mt-2">
                                <a href="/auth/register" class="text-decoration-none">Register An Account</a>
                            </div>
                        </ion-card-content>
                    </ion-card>
                </div>
            </div>
        </div>
    </ion-content>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            const loading = document.querySelector('ion-loading');
            const alertCustom = document.querySelector('ion-alert');

            $('#login').submit(function(e) {
                e.preventDefault();
                loading.message = "Logging In..."
                loading.present();

                $.ajax({
                    type: "post",
                    url: "/auth/login",
                    data: new FormData($('#login')[0]),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        loading.dismiss();

                        alertCustom.message = response.message;
                        alertCustom.buttons = [{
                            text: 'Close',
                            handler: function() {
                                // Clear form inputs
                                $('#username').val('');
                                $('#password').val('');
                                // Redirect to dashboard if successful
                                loading.message = "Redirecting..."
                                loading.present();

                                // Redirect to login page
                                setTimeout(() => {
                                    window.location.href = "/user/dashboard";
                                }, 2000);

                            }
                        }]
                        alertCustom.present();
                    },
                    error: function(xhr, status, error) {
                        loading.dismiss();
                        alertCustom.message = JSON.stringify(xhr.responseJSON.message);
                        alertCustom.buttons = [{
                            text: 'Close',
                        }]
                        alertCustom.present();
                    }
                });
            })
        });
    </script>

@endsection
