@extends('admin.pages.layout')

@section('header')
    <ion-title>Profile</ion-title>
    <ion-button slot="start" href="/admin/dashboard">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection

@section('content')
    <ion-card mode="ios">
        <ion-card-header class="text-center">
            <ion-title>Admin Profile</ion-title>
        </ion-card-header>

        <ion-card-body>
            <form id="profile">
                @csrf
                <ion-list>
                    <ion-item>
                        {{-- <ion-icon name="whatsapp-outline"></ion-icon> --}}
                        <ion-input label-placement="floating" placeholder="Whatsapp Link for customer service."
                            value="{{ Auth::guard('admin')->user()->email }}" name="email">
                            <ion-label slot="label">
                                Admin Email
                            </ion-label>
                        </ion-input>
                    </ion-item>
                </ion-list>
                <ion-button expand="block" class="mt-3" type="submit">
                    Update Email
                    <ion-icon name="save-outline" slot="end"></ion-icon>
                </ion-button>
            </form>
        </ion-card-body>
    </ion-card>

    <ion-card mode="ios">
        <ion-card-header class="text-center">
            <ion-title>Admin Security</ion-title>
        </ion-card-header>

        <ion-card-body>
            <ion-button expand="block" class="mt-3" type="button" id="reset">
                Reset Password
                <ion-icon name="refresh-outline" slot="end"></ion-icon>
            </ion-button>
            <ion-button expand="block" class="mt-3" type="button" id="change">
                Change Password
                <ion-icon name="key-outline" slot="end"></ion-icon>
            </ion-button>

            <ion-button expand="block" color="danger" href="/admin/logout" id="logout">
                <ion-icon name="log-out-outline" slot="end"></ion-icon>
                Log Out</ion-button>

        </ion-card-body>
    </ion-card>


    <ion-modal trigger="reset" id="reset-password" initial-breakpoint="0.90" mode="ios">
        <ion-header>
            <ion-toolbar>
                <ion-title>Reset Password</ion-title>
                <ion-buttons slot="end">
                    <ion-button onclick="document.getElementById('reset-password').dismiss()">
                        close
                    </ion-button>
                </ion-buttons>
            </ion-toolbar>
        </ion-header>

        <ion-content class="ion-padding">
            <form id="reset-form">
                @csrf
                <ion-input type="password" label-placement="floating" placeholder="New Password" name="password">
                    <ion-label slot="label">
                        New Password
                    </ion-label>
                </ion-input>
                <ion-input type="password" label-placement="floating" placeholder="Confirm Password"
                    name="password_confirmation">
                    <ion-label slot="label">
                        Confirm Password
                    </ion-label>
                </ion-input>
                <ion-button expand="block" class="mt-3" type="submit">
                    Reset Password
                    <ion-icon name="refresh-outline" slot="end"></ion-icon>
                </ion-button>
            </form>

        </ion-content>
    </ion-modal>

    <ion-modal trigger="change" id="change-password" initial-breakpoint="0.90" mode="ios">
        <ion-header>
            <ion-toolbar>
                <ion-title>Change Password</ion-title>
                <ion-buttons slot="end">
                    <ion-button onclick="document.getElementById('change-password').dismiss()">
                        close
                    </ion-button>
                </ion-buttons>
            </ion-toolbar>
        </ion-header>
        <ion-content class="ion-padding">
            <form id="change-form">
                @csrf
                <ion-input type="password" label-placement="floating" placeholder="Current Password"
                    name="current_password">
                    <ion-label slot="label">
                        Current Password
                    </ion-label>
                </ion-input>
                <ion-input type="password" label-placement="floating" placeholder="New Password" name="password">
                    <ion-label slot="label">
                        New Password
                    </ion-label>
                </ion-input>
                <ion-input type="password" label-placement="floating" placeholder="Confirm Password"
                    name="password_confirmation">
                    <ion-label slot="label">
                        Confirm Password
                    </ion-label>
                </ion-input>
                <ion-button expand="block" class="mt-3" type="submit">
                    Change Password
                    <ion-icon name="key-outline" slot="end"></ion-icon>
                </ion-button>
            </form>
        </ion-content>
    </ion-modal>
@endsection

@section('script')
    <script>
        const loading = document.querySelector('ion-loading');
        const alertCustom = document.querySelector('ion-alert');

        $('#profile').on('submit', function(e) {
            e.preventDefault();
            loading.message = 'Updating Email...';
            loading.present();

            $.ajax({
                type: 'post',
                url: '/admin/profile/update',
                data: new FormData($('#profile')[0]),
                contentType: false,
                processData: false,
                success: function(response) {
                    loading.dismiss();
                    alertCustom.message = response.message;
                    alertCustom.buttons = [{
                        text: 'OK',
                    }];
                    alertCustom.present();
                },
                error: function(xhr) {
                    loading.dismiss();
                    alertCustom.message = 'Failed to update email. Please try again.';
                    alertCustom.buttons = [{
                        text: 'OK',
                    }];
                    alertCustom.present();
                }
            });
        });

        $("#reset-form").submit(function(event) {
            event.preventDefault();
            loading.message = 'Resetting Password...';
            loading.present();

            // Check if password matches password confirmation
            if ($("#reset-form input[name='password']").val() !== $(
                    "#reset-form input[name='password_confirmation']").val()) {
                loading.dismiss();
                alertCustom.message = 'Passwords do not match. Please try again.';
                alertCustom.buttons = [{
                    text: 'OK',
                }];
                alertCustom.present();
                return;
            }

            // check if empty
            if (!$("#reset-form input[name='password']").val() || !$(
                    "#reset-form input[name='password_confirmation']").val()) {
                loading.dismiss();
                alertCustom.message = 'Please fill all fields.';
                alertCustom.buttons = [{
                    text: 'OK',
                }];
                alertCustom.present();
                return;
            }

            $.ajax({
                type: 'post',
                url: '/admin/profile/reset',
                data: new FormData($('#reset-form')[0]),
                contentType: false,
                processData: false,
                success: function(response) {
                    loading.dismiss();
                    alertCustom.message = response.message;
                    alertCustom.buttons = [{
                        text: 'OK',
                    }];
                    alertCustom.present();
                },
                error: function(xhr) {
                    loading.dismiss();
                    alertCustom.message = 'Failed to reset password. Please try again.';
                    alertCustom.buttons = [{
                        text: 'OK',
                    }];
                    alertCustom.present();
                }
            });
        })

        $("#change-form").submit(function(event) {
            event.preventDefault();
            loading.message = 'Changing Password...';
            loading.present();


            // Check if password matches password confirmation
            if ($("#change-form input[name='password']").val() !== $(
                    "#change-form input[name='password_confirmation']").val()) {
                loading.dismiss();
                alertCustom.message = 'Passwords do not match. Please try again.';
                alertCustom.buttons = [{
                    text: 'OK',
                }];
                alertCustom.present();
                return;
            }

            // check if empty
            if (!$("#change-form input[name='current_password']").val() || !$("#change-form input[name='password']")
                .val() || !$("#change-form input[name='password_confirmation']").val()) {
                loading.dismiss();
                alertCustom.message = 'Please fill all fields.';
                alertCustom.buttons = [{
                    text: 'OK',
                }];
                alertCustom.present();
                return;
            }

            $.ajax({
                type: 'post',
                url: '/admin/profile/change',
                data: new FormData($('#change-form')[0]),
                contentType: false,
                processData: false,
                success: function(response) {
                    loading.dismiss();
                    alertCustom.message = response.message;
                    alertCustom.buttons = [{
                        text: 'OK',
                    }];
                    alertCustom.present();
                },
                error: function(xhr) {
                    loading.dismiss();
                    alertCustom.message = xhr.responseJSON.message;
                    alertCustom.buttons = [{
                        text: 'OK',
                    }];
                    alertCustom.present();
                }
            });
        })
    </script>
@endsection
