@extends('admin.pages.layout')
@section('header')
    <ion-title>Rest all User Tasks</ion-title>
    <ion-button slot="start" href="/admin/dashboard">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection
@section('content')
<ion-card mode="ios" class="ion-padding">
    <ion-card-header>
        <ion-title>
            Rest All User Tasks
        </ion-title>
    </ion-card-header>
    <ion-card-body>
        <p style="text-decoration:underline;">
            This button will help you to reset the users daily task. Note you should only use this once in a day. Using it mutiple times may affect the users task for the day.
        </p>
        <ion-button expand="block" color="dark" id="resetBtn">
            Reset all Users Task
        </ion-button>
    </ion-card-body>
</ion-card>

@endsection

@section('script')
<script>
    const loading = document.querySelector('ion-loading');
    const alertCustom = document.querySelector('ion-alert');

    $('#resetBtn').click(function () {
        loading.message = 'Resetting users tasks...';
        loading.present();

        $.ajax({
            type: "get",
            url: "/rest-user-tasks",
            success: function (response) {
                loading.dismiss();
                alertCustom.message = response.message;
                alertCustom.buttons = ['Ok']
                alertCustom.present();
            }
        });
    });
</script>

@endsection