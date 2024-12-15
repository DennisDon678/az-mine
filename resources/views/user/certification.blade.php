@extends('user.layout')
@section('title', 'our certificate')

@section('header')
    <ion-title>Our Certificate</ion-title>
    <ion-button slot="start" href="/user/dashboard">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>

@endsection

@section('content')
    <ion-card mode="ios" class="ion-padding">
        <ion-card-header class="text-center">
            <ion-card-title>Certificate of Incorporation</ion-card-title>
        </ion-card-header>

        <ion-card-content>
            <img src="{{ asset('cert/zi-group-certification.jpg') }}" alt="certificate">
        </ion-card-content>
    </ion-card>

    <ion-card mode='ios' class="ion-padding">
        <ion-card-header class="text-center">
            <ion-card-title>More Information</ion-card-title>
        </ion-card-header>

        <ion-card-content>
            <h3>Zappi is an officially certified B Corporation.</h3>
            <br>
            <p>Not only that, Zappi is now one of the few consumer insights data platforms to have achieved such
                distinction. Fortunately, there is so much happening in our ecosystem these days — whether it’s the net-zero
                commitment, using tech for good or the diversity and inclusion pledge— so being recognized as one of the
                pioneers in the insight world makes us even prouder of what we have achieved.
                <br>
                <br>
                From our earliest days, we knew we wanted Zappi to be a different sort of business; a business committed to
                building something bigger than ourselves, disrupting the status quo and showing real leadership. Becoming a
                B Corp is our way of making ourselves accountable for our impact on people, the planet and the communities
                we serve, signalling to everyone in the company the type of organization we want to be.</p>
                <br>
                <p>Anyone can <a href="https://www.bcorporation.net/en-us/find-a-b-corp/company/zi-group-ltd">read our assessment report</a>, which offers more transparency into how we are running our business.</p>
        </ion-card-content>
    </ion-card>
@endsection
