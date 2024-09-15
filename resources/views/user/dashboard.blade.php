@extends('user.layout')
@section('title','Dashboard')

@section('header')
<ion-title>{{ env('APP_NAME') }}</ion-title>
<ion-button slot="end" href="/user/profile">
    <ion-icon name="person-circle"  mode="ios" size="large"></ion-icon>
</ion-button>
@endsection

@section('content')
    <h1>HELLO</h1>
@endsection