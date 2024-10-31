@extends('admin.pages.layout')

@section('header')
    <ion-title>Task Config</ion-title>
    <ion-button slot="start" href="/admin/dashboard">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection

@section('content')
    <ion-card mode="ios">
        <ion-card-header class="text-center">
            <ion-title>Subscribers List</ion-title>
        </ion-card-header>

        <ion-card-body>
            <ion-list inset="true">
                @forelse ($users as $user)
                    <ion-item>
                        <ion-grid>
                            <ion-row class="ion-justify-content-between">
                                @php
                                $current = App\Models\User::where('id', '=', $user->user_id)->first();
                                $package = App\Models\packages::where('id',$user->package_id)->first();
                                @endphp
                                <ion-col size="8">
                                    <ion-label>
                                        <p>Username: <strong>{{ $current->username }}</strong></p>
                                        <p><strong>{{ $package->package_name }}</strong></p>
                                    </ion-label>
                                </ion-col>
                                <ion-col size="4">
                                    <ion-row class="ion-justify-content-end">
                                        <ion-col>
                                            <ion-button color="primary" href="/admin/task-setting?user={{$current->id}}">Config</ion-button>
                                        </ion-col>
                                    </ion-row>
                                </ion-col>
                            </ion-row>

                        </ion-grid>
                    </ion-item>
                @empty
                    <ion-item class="text-center text-danger">
                        <ion-label>No Subscriber to Show</ion-label>
                    </ion-item>
                @endforelse
            </ion-list>
        </ion-card-body>
    </ion-card>
@endsection
