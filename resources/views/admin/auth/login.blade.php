@extends('auth.layout')

@section('title', 'Admin login page')

<style>
    .main {
        max-width: 650px;
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
                        <ion-card-header mode="md">
                            <ion-card-title>
                                <div class="h2 text-center ">
                                    <h2 class="font-weight-bold" style="font-weight:bold!important;"> Welcome To Admin</h2>
                                </div>
                            </ion-card-title>
                            <p class="text-center">Login and configure and monitor your site.</p>

                        </ion-card-header>

                        <ion-card-content mode="ios">
                            <div class="input-system">
                                <form action="" id="login">
                                    @csrf
                                    <ion-item>
                                        <ion-icon name="mail-outline" slot="start"></ion-icon>
                                        <ion-input placeholder="Email" name="username" fill=""></ion-input>
                                    </ion-item>
                                    <br>
                                    <ion-item>
                                        <ion-icon name="lock-closed-outline" slot="start"></ion-icon>
                                        <ion-input type="password" placeholder="Your Password" name="password"
                                            fill=""></ion-input>
                                    </ion-item>


                                    <div class="log-btn mt-5">
                                        <ion-item>
                                            <ion-button type="submit" style="width: 100%;">Login
                                                <ion-icon name="caret-forward-outline" slot="end"></ion-icon>
                                            </ion-button>
                                        </ion-item>
                                    </div>
                                </form>
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

            alertCustom.message = "Welcome To Admin, Login Now!";
            alertCustom.buttons = [{
                text: 'OK',
            }]
            alertCustom.present();

            $('#login').submit(function(e) {
                e.preventDefault();
                loading.message = "Logging In..."
                loading.present();

                $.ajax({
                    type: "post",
                    url: "/auth/admin",
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
                                    window.location.href = "/admin/dashboard";
                                }, 2000);

                            }
                        }]
                        alertCustom.present();
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON)
                        loading.dismiss();
                        alertCustom.message = xhr.responseJSON.message;
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
