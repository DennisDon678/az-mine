@extends('admin.pages.layout')

@section('title',$user->username)
@section('header')
    <ion-title>{{ strtoupper($user->username) }}</ion-title>
    <ion-button slot="start" href="/admin/users">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection

@section('content')
    <ion-card mode="ios">
        <ion-card-header>
            <ion-title class="text-center">User Information</ion-title>
        </ion-card-header>

        <ion-card-body>
            <ion-item>
                <ion-label>Username:</ion-label>
                <ion-text><strong>{{ $user->username }}</strong></ion-text>
            </ion-item>
            {{-- <ion-item>
                <ion-label>Email:</ion-label>
                <ion-text><strong>{{ $user->email }}</strong></ion-text>
            </ion-item> --}}
            <ion-item>
                <ion-label>Created At:</ion-label>
                <ion-text><strong>{{ $user->created_at->format('d/m/Y H:i:s') }}</strong></ion-text>
            </ion-item>
            <ion-item>
                <ion-label>Current Package:</ion-label>
                <ion-text><strong>{{ $package->package_name }}</strong></ion-text>
            </ion-item>
            <ion-item>
                <ion-label>Open Balance:</ion-label>
                <ion-text><strong>${{ number_format($user->balance, 2) }}</strong></ion-text>
            </ion-item>
            <ion-item>
                <ion-label>Referral Balance:</ion-label>
                <ion-text><strong>${{ number_format($user->referral_earning, 2) }}</strong></ion-text>
            </ion-item>
        </ion-card-body>
    </ion-card>

    <ion-card class="ion-padding" mode="ios">
        <ion-card-header>
            <ion-title class="text-center">Action</ion-title>
        </ion-card-header>
        <ion-card-body>
            <ion-button expand="block" color="primary" id="edit">
                <ion-icon name="create-outline" slot="start"></ion-icon>
                Edit</ion-button>
            <ion-button expand="block" color="danger" id="delete">
                <ion-icon name="trash-outline" slot="start"></ion-icon>
                Delete</ion-button>
            <ion-button expand="block" color="dark" href="/admin/user/{{$user->id}}/reset-password">
                <ion-icon name="refresh-outline" slot="start"></ion-icon>
                Reset Password
            </ion-button>
        </ion-card-body>
    </ion-card>

    <ion-modal trigger="edit" initial-breakpoint="0.5" mode="ios">
        <ion-header>
            <ion-toolbar>
                <ion-title>Edit User</ion-title>
            </ion-toolbar>
        </ion-header>

        <ion-content class="ion-padding">
            <ion-item mode="ios">
                <ion-icon name="list-circle-outline" slot="start"></ion-icon>
                <ion-select placeholder="Select action to take" name="choice2">
                    <ion-select-option value="credit">Credit</ion-select-option>
                    <ion-select-option value="debit">Debit</ion-select-option>
                </ion-select>
            </ion-item>
            <ion-item mode="ios">
                <ion-icon name="cash-outline" slot="start"></ion-icon>
                <ion-input type="number" placeholder="Enter amount to deposit" name="amount" required></ion-input>
            </ion-item>

            <ion-button expand="block" color="primary" id="save">Save</ion-button>
        </ion-content>
    </ion-modal>
@endsection

@section('script')
    <script>
        const editBtn = document.querySelector('#edit');
        const deleteBtn = document.querySelector('#delete');
        const alertCustom = document.querySelector('ion-alert');
        const loadings = document.querySelector('ion-loading');
        const saveBtn = document.querySelector('#save');

        deleteBtn.addEventListener('click', function() {
            alertCustom.message = "You are about to delete this user from the database.";
            alertCustom.buttons = [{
                    text: 'Cancel',
                    handler: () => {}
                },
                {
                    text: 'Delete',
                    handler: () => {
                        loadings.message = "Deleting User...";
                        loadings.present();

                        $.ajax({
                            type: "get",
                            url: "/admin/delete-user?id={{ $user->id }}",

                            success: function(response) {
                                loadings.dismiss();
                                alertCustom.message = response.message;
                                alertCustom.buttons = ['OK'];
                                alertCustom.present();
                                window.location.href = '/admin/users';
                            },
                            error: () => {
                                loadings.dismiss();
                                alertCustom.message = "Failed to delete user.";
                                alertCustom.buttons = [{
                                    text: 'OK',
                                    handler: () => {
                                        window.location.href = '/admin/users'
                                    }
                                }];
                                alertCustom.present();
                            }
                        });
                    }
                }
            ]
            alertCustom.present();
        });

        saveBtn.addEventListener('click', function() {
            const choice = document.querySelector('ion-select[name="choice2"]').value;
            const amount = document.querySelector('ion-input[name="amount"]').value;

            loadings.message = "Updating User...";
            loadings.present();

            $.ajax({
                type: "get",
                url: "/admin/edit-user?id={{ $user->id }}&amount=" + amount + "&choice=" + choice,
                success: function(response) {
                    loadings.dismiss();
                    alertCustom.message = response.message;
                    alertCustom.buttons = [{
                        text: 'OK',
                        handler: () => {
                            window.location.href = '/admin/user/{{ $user->id }}'
                        }
                    }];
                    alertCustom.present();
                },
                error: () => {
                    loadings.dismiss();
                    alertCustom.message = "Failed to update user.";
                    alertCustom.buttons = ['OK'];
                    alertCustom.present();
                }
            });
        });
    </script>
@endsection
