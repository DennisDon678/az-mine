@extends('admin.pages.layout')

@section('header')
    <ion-title>Users</ion-title>
    <ion-button slot="start" href="/admin/dashboard">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection

@section('content')
    <ion-card mode="ios">
        <ion-card-header class="text-center">
            <ion-title>Users List</ion-title>
        </ion-card-header>

        <ion-card-body>
            <ion-list inset="true">
                @forelse ($users as $user)
                    <ion-item>
                        <ion-grid>
                            <ion-row class="ion-justify-content-between">
                                <ion-col size="8">
                                    <ion-label>
                                        <p>Username: <strong>{{ $user->username }}</strong></p>
                                        <p><strong>{{ $user->email }}</strong></p>
                                    </ion-label>
                                </ion-col>
                                <ion-col size="4">
                                    <ion-row class="ion-justify-content-end">
                                        <ion-col>
                                            <ion-button color="primary"
                                                href="/admin/user/{{$user->id}}">View</ion-button>
                                        </ion-col>
                                    </ion-row>
                                </ion-col>
                            </ion-row>

                        </ion-grid>
                    </ion-item>
                @empty
                    <ion-item class="text-center text-danger">
                        <ion-label>No User to Show</ion-label>
                    </ion-item>
                @endforelse
            </ion-list>
        </ion-card-body>
    </ion-card>
@endsection
