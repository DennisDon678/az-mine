@extends('auth.layout')

@section('title', 'Register page')

<style>
    .main{
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
                                    <h2 class="font-weight-bold" style="font-weight:bold!important;"> Register {{env('APP_NAME')}} Account</h2>
                                </div>
                            </ion-card-title>
                            <p class="text-center">Register and claim your reward by helping our algorithm process orders
                                faster.</p>


                        </ion-card-header>

                        <ion-card-content>
                            <div class="input-system">
                                <ion-item>
                                    <ion-icon name="person-circle-outline" slot="start"></ion-icon>
                                    <ion-input placeholder="Create a Unique Username" fill=""></ion-input>
                                </ion-item>
                                <br>
                                <ion-item>
                                    <ion-icon name="lock-closed-outline" slot="start"></ion-icon>
                                    <ion-input type="password" placeholder="Create Your Password" fill=""></ion-input>
                                </ion-item>
                                 <br>
                                <ion-item>
                                    <ion-icon name="lock-closed-outline" slot="start"></ion-icon>
                                    <ion-input type="password" placeholder="Confirm Your Password" fill=""></ion-input>
                                </ion-item>
                                 <br>
                                <ion-item>
                                    <ion-icon name="enter-outline" slot="start"></ion-icon>
                                    <ion-input type="text" placeholder="Enter Invitation code (optional)" fill=""></ion-input>
                                </ion-item>
                                <br>
                                <ion-item>
                                    <ion-icon name="shield-checkmark-outline" slot="start"></ion-icon>
                                    <ion-input type="number" placeholder="Enter Verification Code" fill=""></ion-input>
                                </ion-item>
                            </div>
                            <div class="code mt-2 text-center">
                                <h4>Verification Code is:</h4>
                                <h2 class="text-success" id="verificationCode">{{$code}}</h2>
                            </div>

                            <div class="log-btn mt-3">
                                <ion-item>
                                    <ion-button style="width: 100%;">Register
                                    <ion-icon name="caret-forward-outline" slot="end"></ion-icon>
                                </ion-button>
                                </ion-item>
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
