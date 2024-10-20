@extends('user.layout')

@section('title', 'My Profile')

@section('header')
    <ion-title>My Profile</ion-title>
    <ion-button slot="start" href="/user/dashboard">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection
<style>
    .profile-card {
        border-radius: 20px;
        position: relative;
        height: 30vh;
        background-image: url('{{ asset('users/imgs/profile-card-bg.jpg') }}');
        background-position: center;
        background-size: cover;
    }

    .over {
        background: black;
        height: 100%;
        opacity: 0.75;
    }

    img {
        border-radius: 50%;
        height: 160px;
    }

    .content-profile {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #ffffff;
    }
</style>

@section('content')

    <div class="profile-card p-3 m-3">
        <div class="over"></div>
        <div class="content-profile text-center">
            <img alt="Silhouette of a person's head" src="https://ionicframework.com/docs/img/demos/avatar.svg" />
            <h3>Hello, {{ Auth::user()->username }}</h3>
        </div>
    </div>

    <ion-card mode="ios">
        <ion-card-header>
            <h4 class="text-center">Wallet Information</h4>
        </ion-card-header>
        <ion-card-content>
            <ion-row>
                <ion-col>
                    <ion-text class="ion-text-nowrap"><strong>Open Balance:</strong></ion-text>
                </ion-col>
                <ion-col>
                    <ion-text>${{ number_format(Auth::user()->balance, 2) }}</ion-text>
                </ion-col>
            </ion-row>
            <ion-row>
                <ion-col>
                    <ion-text class="ion-text-nowrap"><strong>Referral Balance:</strong></ion-text>
                </ion-col>
                <ion-col>
                    <ion-text>${{ number_format(Auth::user()->referral_balance, 2) }}</ion-text>
                </ion-col>
            </ion-row>
    </ion-card>

    <ion-card mode="ios">
        <ion-card-header>
            <h4 class="text-center">More Information</h4>
        </ion-card-header>
        <ion-card-content>
            <ion-grid>
                <ion-row>
                    <ion-col>
                        <ion-text class="ion-text-nowrap"><strong>Current Package:</strong></ion-text>
                    </ion-col>
                    <ion-col>
                        <ion-text>VIP 1</ion-text>
                    </ion-col>
                </ion-row>

                <ion-row>
                    <ion-col>
                        <ion-text class="ion-text-nowrap"><strong>Invitation Code:</strong></ion-text>
                    </ion-col>
                    <ion-col>
                        <ion-text>{{ Auth::user()->referral_id }} <ion-icon name="clipboard-outline" color="success"
                                style="cursor: pointer;" id="copy"></ion-icon></ion-text>
                    </ion-col>
                </ion-row>

                {{-- FOR EMAIL --}}
                <ion-row>
                    <ion-col>
                        <ion-text class="ion-text-nowrap"><strong>Email:</strong></ion-text>
                    </ion-col>
                    <ion-col>
                        <ion-text>{{ Auth::user()->email }}</ion-text>
                    </ion-col>
                </ion-row>

                {{-- Username --}}
                <ion-icon name="person-circle-outline" slot="start"></ion-icon>
                <ion-row>
                    <ion-col>
                        <ion-text class="ion-text-nowrap"><strong>Username:</strong></ion-text>
                    </ion-col>
                    <ion-col>
                        <ion-text>{{ Auth::user()->username }}</ion-text>
                    </ion-col>
                </ion-row>
            </ion-grid>
        </ion-card-content>
    </ion-card>
    <ion-list lines="none">
        <ion-item>
            <form action="" id="csrf">
                @csrf
            </form>
            <ion-button style="width: 100%;" color="danger" id="logout">
                <ion-icon name="log-out-outline" slot="end"></ion-icon>
                Log Out</ion-button>
        </ion-item>
    </ion-list>

    <div class="mt-5"></div>
@endsection

@section('script')
    <script>
        const loading = document.querySelector('ion-loading');
        const alertCustom = document.querySelector('ion-alert');
        $('#logout').click(() => {
            alertCustom.message = "Are you sure you want to logout?";
            alertCustom.buttons = [{
                text: 'Cancel',
                handler: () => {}
            }, {
                text: 'Logout',
                handler: () => {
                    loading.message = "Logging Out..."
                    loading.present();

                    $.ajax({
                        type: "POST",
                        url: "/auth/logout",
                        data: new FormData($('#csrf')[0]),
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            loading.dismiss();
                            alertCustom.message = response.message;
                            alertCustom.buttons = [{
                                text: 'OK',
                                handler: () => {
                                    loading.message =
                                        "redirecting...";
                                    loading.present();

                                    setTimeout(() => {
                                        window.location.href =
                                            "/auth/login"
                                    }, 2000);
                                }
                            }]
                            alertCustom.present();
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            loading.dismiss();
                            var error = xhr.responseJSON;
                            alertCustom.message = JSON.stringify(error);
                            alertCustom.buttons = [{
                                text: 'OK',
                                handler: () => {

                                }
                            }]
                            alertCustom.present();
                        }
                    });
                }
            }]
            alertCustom.present();
        })

        $('#copy').click(() => {
            loading.message = 'Copying...';
            loading.present();

            navigator.clipboard.writeText("{{ Auth::user()->referral_id }}");

            loading.dismiss();
            alertCustom.message = 'Referral code copied!';
            alertCustom.buttons = ['OK'];
            alertCustom.present();
        })
    </script>
@endsection
