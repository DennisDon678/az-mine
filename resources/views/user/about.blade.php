@extends('user.layout')

@section('title', 'About Us');
@section('header')
    <ion-title>About Us</ion-title>
    <ion-button slot="start" href="/user/dashboard">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
    <ion-button slot="end" href="/user/contact">
        <ion-icon name="chatbubbles"></ion-icon>
    </ion-button>
@endsection

@section('content')
    <ion-card mode="ios" class="ion-padding">
        <ion-card-header class="text-center">
            <ion-title>About {{env('APP_NAME')}}</ion-title>
        </ion-card-header>
        <ion-card-content>
            <p>Welcome to {{env('APP_NAME')}}, an innovative solution designed to supercharge the performance of e-commerce websites. As an extended platform powered by Zappi.io, we provide businesses with a seamless way to improve their site speed, optimize processing time, and elevate the overall customer experience.</p>
            <br>
            <p>In today's digital world, slow website performance can cost you customers and sales. That's where we come in. {{env('APP_NAME')}} offers a cutting-edge solution by routing your e-commerce orders through our high-performance system, ensuring faster transaction processing and smoother user experiences. But we don't stop there—our platform also enables users to directly contribute to the processing power.</p>
            <br>
            <p>How does it work? {{env('APP_NAME')}} leverages the unused CPU resources of our user base, allowing individuals to opt in and contribute their system’s processing power to handle e-commerce orders. In return, users earn a fraction of the order amount processed through their system. This decentralized, efficient model ensures that your website runs at peak performance while creating an opportunity for users to earn passive income.</p>
            <br>
            <p>Our mission is to help e-commerce businesses of all sizes stay competitive by providing them with the tools to enhance site speed, reduce downtime, and improve order processing efficiency. Whether you're an online retailer, a marketplace, or a service provider, {{env('APP_NAME')}} offers a scalable and cost-effective solution to meet your needs.</p>
            <br>
            <p>At {{env('APP_NAME')}}, we believe in the power of collaboration. By combining the strengths of e-commerce businesses and individual users, we’ve created a platform that delivers results for everyone. With our focus on innovation, security, and performance, we are committed to helping you grow your online business and providing a sustainable way for users to contribute to the digital ecosystem.</p>
            <br>
            <p>Join us today and be part of a community that is transforming the future of e-commerce, one order at a time.</p>
        </ion-card-content>
    </ion-card>
@endsection