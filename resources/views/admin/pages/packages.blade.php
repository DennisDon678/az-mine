@extends('admin.pages.layout')

@section('header')
    <ion-title>Packages</ion-title>
    <ion-button slot="start" href="/admin/dashboard">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection

@section('content')
    <ion-card mode="ios">
        <ion-card-header class="text-center">
            <ion-card-title>Packages List</ion-card-title>
        </ion-card-header>

        <ion-card-body>
            <ion-list lines="full" inset="true">
                @forelse ($packages as $package)
                    <ion-item>

                        <ion-label>
                            {{ $package->package_name }}
                        </ion-label>

                        <ion-button color="success" id="{{ $package->id }}" slot="end"
                            class="ion-padding">Edit</ion-button>

                    </ion-item>

                    <ion-modal trigger="{{ $package->id }}" initial-breakpoint="0.95" mode="ios">
                        <ion-header>
                            <ion-toolbar>
                                <ion-title>Edit Package {{ $package->package_name }}</ion-title>
                            </ion-toolbar>
                        </ion-header>
                        <ion-content class="ion-padding">
                            <form action="" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ $package->id }}">
                                <ion-item mode="ios">
                                    <ion-label>Package Name</ion-label>
                                    <ion-input type="text" placeholder="Crypto Name" name=""
                                        value="{{ $package->package_name }}" readonly required></ion-input>
                                </ion-item>
                                <ion-item mode="ios">
                                    <ion-label>Package Percentage:</ion-label>
                                    <ion-input type="text" placeholder="Crypto Short Name" name="percentage_profit"
                                        value="{{ $package->percentage_profit }}" required></ion-input>
                                </ion-item>
                                <ion-item mode="ios">
                                    <ion-label>Package No. of Orders per day</ion-label>
                                    <ion-input type="number" placeholder="Crypto Network" name="number_of_orders_per_day"
                                        value="{{ $package->number_of_orders_per_day }}"></ion-input>
                                </ion-item>
                                <ion-item mode="ios">
                                    <ion-label>Package Fee</ion-label>
                                    <ion-input type="number" placeholder="Crypto Wallet Address" name="package_price"
                                        value="{{ $package->package_price }}" required></ion-input>
                                </ion-item>
                                <ion-item mode="ios">
                                    <ion-label>Daily Profit</ion-label>
                                    <ion-input type="text" placeholder="Daily Profit percentage" name="daily_profit"
                                        value="{{ $package->daily_profit }}" required></ion-input>
                                </ion-item>

                                <ion-button expand="block" type="submit" color="primary">Save
                                    Package</ion-button>
                            </form>
                        </ion-content>
                    </ion-modal>
                @empty
                    
                @endforelse
            </ion-list>

        </ion-card-body>
    </ion-card>
    </ion-modal>
@endsection

@section('script')
    <script>
        const alertCustom = document.querySelector('ion-alert');
        @if (Session::has('message'))
            alertCustom.message = "{{ Session::get('message') }}"
            alertCustom.buttons = [{
                text: 'OK',
                handler: () => {}
            }]
            alertCustom.present();
        @endif
    </script>
@endsection
