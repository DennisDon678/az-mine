@extends('admin.pages.layout')

@section('header')
    <ion-title>Settings</ion-title>
    <ion-button slot="start" href="/admin/dashboard">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection

@section('content')
    <ion-card>
        <ion-card-header class="text-center">
            <ion-card-title>Settings</ion-card-title>
        </ion-card-header>

        <ion-card-body>
            <ion-list>
                <ion-item>
                    <ion-input placeholder="Whatsapp Link for customer service." name="whatsapp"></ion-input>
                </ion-item>
            </ion-list>
        </ion-card-body>
    </ion-card>
@endsection