@extends('admin.pages.layout')

@section('title','Reset User password')
@section('header')
    <ion-title>{{ strtoupper($user->username) }}</ion-title>
    <ion-button slot="start" href="/admin/user/{{$user->id}}">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection

@section('content')
<ion-card mode="ios" class="ion-padding">
    <ion-card-header class="text-center">
        <ion-title>Reset Password</ion-title>
    </ion-card-header>
    <ion-card-body>
        <input type="hidden" id="user" value="{{$user->id}}">
        <p>Your are about to change this user's login password. Please make sure to copy the new password before closing the page.</p>
        <div class="mt-2">
            <ion-button expand="block" id="generate" color="dark">
                <ion-icon name="reload-outline" slot="start"></ion-icon>
                Generate new password
            </ion-button>
            <div class="mt-2 text-center d-none" id="newPass">
                <h4 id="pass"></h4>
                <ion-button id="copyPass">
                    <ion-icon name="copy-outline" slot="start"></ion-icon>
                    copy password
                </ion-button>
            </div>
        </div>
    </ion-card-body>
</ion-card>
@endsection

@section('script')
<script>
    const loading = document.querySelector('ion-loading');
    const alertCustom = document.querySelector('ion-alert');
    var aux;
    $('#generate').click(function () {
        loading.message = 'Generating new password...';
        loading.present();

        $.ajax({
            type: "get",
            url: "/admin/generate-new-password?user="+$('#user').val(),
            success: function (response) {
                loading.dismiss();
                $('#pass').text(response.new_password);
                aux = document.createElement("input");
                aux.setAttribute("value", response.new_password);
                document.body.appendChild(aux);
                $('#newPass').removeClass('d-none');

                alertCustom.message = 'New password generated successfully.';
                alertCustom.buttons = ['OK'];
                alertCustom.present();
            }
        });
    })

    $('#copyPass').click(function () {
        aux.select();
        document.execCommand("copy");
        alertCustom.message = 'Password copied to clipboard.';
        alertCustom.buttons = ['OK'];
        alertCustom.present();
    });
</script>
@endsection