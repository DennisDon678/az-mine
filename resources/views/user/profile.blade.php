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
        height: 33vh;
        background-image: url('{{ asset('users/imgs/profile-card-bg.jpg') }}');
        background-position: center;
        background-size: cover;
    }

    .over {
        background: black;
        height: 112%;
        opacity: 0.75;
        margin-top: -15px;
    }

    img {
        border-radius: 50%;
        height: 160px;
        width: 160px;
    }

    .content-profile {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #ffffff;
    }

    .border-custom {
        border: 1px solid grey;
        border-radius: 10px;
        margin-bottom: 10px;
    }
</style>

@section('content')

    <div class="profile-card p-3 m-3">
        <div class="over"></div>
        <div class="content-profile text-center">
            <img alt="Silhouette of a person's head" src="{{Auth::user()->profile_picture?asset('/images/profile_pictures/'.Auth::user()->profile_picture):'https://ionicframework.com/docs/img/demos/avatar.svg'}}"
                id="preview-image" />
            <input type="file" name="profile" id="profile" hidden>
            <ion-button id="change-profile">
                <ion-icon name="create-outline"></ion-icon>
                change
            </ion-button>
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
                    <ion-text>${{ number_format(Auth::user()->referral_earning, 2) }}</ion-text>
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
    <ion-card>
        <ion-card-header class="text-center">
            <ion-title>
                Actions
            </ion-title>
        </ion-card-header>
        <ion-card-body class="ion-padding">
            <ion-item button href="/user/bind-wallet" class="mx-3 border-custom">
                {{-- <ion-button> --}}
                <ion-icon name="wallet-outline" slot="start"></ion-icon>
                <ion-icon name="chevron-forward-outline" slot="end"></ion-icon>
                Wallet Binding
                {{-- </ion-button> --}}
            </ion-item>
            <ion-item button id="security" class="mx-3 border-custom">
                <ion-icon name="lock-open-outline" slot="start"></ion-icon>
                Security
                <ion-icon name="chevron-forward-outline" slot="end"></ion-icon>
            </ion-item>
            <ion-item button href="/user/contact" class="mx-3 border-custom">
                {{-- <ion-button> --}}
                <ion-icon name="mic-outline" slot="start"></ion-icon>
                <ion-icon name="chevron-forward-outline" slot="end"></ion-icon>
                Customer Service
                {{-- </ion-button> --}}
            </ion-item>
        </ion-card-body>
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
        const toast = document.querySelector('ion-toast');
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
        });

        $('#security').click(() => {
            alertCustom.message = "Set or Update Transaction pin";
            alertCustom.inputs = [{
                    placeholder: 'New Transaction PIN',
                    type: 'number',
                    id: 'pin',
                },
                {
                    placeholder: 'Confirm Transaction PIN',
                    type: 'number',
                    id: 'confirm',
                }
            ];
            alertCustom.buttons = [{
                text: "Save",
                role: 'confirm',
                handler: () => {
                    alertCustom.inputs = [];
                    alertCustom.isOpen = false;
                    loading.message = "Updating PIN..."
                    loading.present();
                    const pin = $("#pin").val();
                    const confirm = $("#confirm").val();

                    if (pin === '' || confirm === '') {
                        loading.dismiss();
                        toast.message = "Both PINs are required";
                        toast.position = 'top';
                        toast.duration = 3000;
                        toast.present();

                        return
                    }
                    // pin should be 4 digits
                    if (pin.length != 4) {
                        loading.dismiss();
                        toast.message = "PIN must be 4 digits";
                        toast.position = 'top';
                        toast.duration = 3000;
                        toast.present();
                        return;
                    }

                    if (pin != confirm) {
                        loading.dismiss();
                        toast.message = "Both PINs must match";
                        toast.position = 'top';
                        toast.duration = 3000;
                        toast.present();
                        return;
                    }
                    const data = new FormData();
                    data.append('pin', pin);
                    data.append('_token', '{{ csrf_token() }}');

                    $.ajax({
                        type: "post",
                        url: "/user/update-transaction-pin",
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            loading.dismiss()
                            toast.message = response.message;
                            toast.position = 'top';
                            toast.duration = 3000;
                            toast.present();
                            return;
                        },
                        error: function(xhr) {
                            loading.dismiss();
                            var error = xhr.responseJSON;
                            toast.message = error.message;
                            toast.position = 'top';
                            toast.duration = 3000;
                            toast.present();
                        }
                    });
                }
            }];
            alertCustom.present();
        })

        $('#change-profile').click(function() {
            alertCustom.message = "Change Profile Picture";
            alertCustom.buttons = [{
                    text: 'Cancel',
                    role: 'cancel',
                    handler: () => {
                        alertCustom.inputs = [];
                        alertCustom.isOpen = false;
                    }
                },
                {
                    text: 'Choose from Library',
                    handler: () => {
                        alertCustom.inputs = [];
                        alertCustom.isOpen = false;
                        $('#profile').click();
                    }
                }
            ]
            alertCustom.present();
        });

        $('#profile').on('change', () => {
            loading.message = "Uploading...";
            loading.present();
            const data = new FormData();
            data.append('profile_picture', $('#profile')[0].files[0]);
            data.append('_token', '{{ csrf_token() }}');
            $.ajax({
                type: "post",
                url: "/user/update-profile-picture",
                data: data,
                processData: false,
                contentType: false,
                success: function(response) {
                    loading.dismiss()
                    alertCustom.message = response.message;
                    alertCustom.buttons = [
                        {
                            text: 'OK',
                            handler: () => {
                                loading.message = 'reloading profile picture';
                                // reload the page
                                setTimeout(() => {
                                    location.href = '/user/profile';
                                }, 2000);
                            }
                        }
                    ]
                    toast.present();
                    return;
                },
                error: function(xhr) {
                    loading.dismiss();
                    var error = xhr.responseJSON;
                    toast.message = error.message;
                    toast.position = 'top';
                    toast.duration = 3000;
                    toast.present();
                }
            });
        });
    </script>

    @if (Session::has('error'))
        <script>
            // console.log('error');
            alertCustom.message = "Set Transaction PIN First.";
            alertCustom.buttons = ['Ok']
            alertCustom.isOpen = true;
        </script>
    @endif

@endsection
