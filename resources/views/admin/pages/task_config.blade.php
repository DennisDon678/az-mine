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
                                    $subscription = App\Models\packages::where(
                                        'id',
                                        App\Models\subscription::where('user_id', $user->user->id)->first()->package_id,
                                    )->first();
                                @endphp
                                <ion-col size="8">
                                    <ion-label>
                                        <p>Username: <strong>{{ $user->user->username }}</strong></p>
                                        <p><strong>{{ $subscription->package_name }}</strong></p>
                                    </ion-label>
                                </ion-col>
                                <ion-col size="4">
                                    <ion-row class="ion-justify-content-end">
                                        <ion-col>
                                            <ion-button color="primary" id="config-{{$user->id}}">Config</ion-button>
                                        </ion-col>
                                    </ion-row>
                                </ion-col>
                            </ion-row>

                        </ion-grid>
                    </ion-item>

                    <ion-modal trigger="config-{{$user->id}}" initial-breakpoint="0.65" mode="ios" id="modal-{{$user->id}}">
                        <ion-header>
                            <ion-toolbar>
                                <ion-title>Task Config</ion-title>
                            </ion-toolbar>
                        </ion-header>
                        <ion-content class="ion-padding">
                            <form action="" method="post" id="save-{{$user->id}}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <ion-item mode="ios">
                                    <ion-label slot="start">Package Name:</ion-label>
                                    <ion-input type="text" placeholder="Crypto Name" name=""
                                        value="{{ $subscription->package_name }}" readonly required></ion-input>
                                </ion-item>
                                <ion-item mode="ios">
                                    <ion-label slot="start">Daily Order:</ion-label>
                                    <ion-input type="text" placeholder="Crypto Name" name=""
                                        value="{{ $subscription->number_of_orders_per_day }}" readonly required></ion-input>
                                </ion-item>
                                <ion-item mode="ios">
                                    <ion-label slot="start">Start Task:</ion-label>
                                    <ion-input type="text" placeholder="Task Enabled" name="task_start_enabled"
                                        value="{{ $user->task_start_enabled }}" required></ion-input>
                                </ion-item>
                                <ion-item mode="ios">
                                    <ion-label slot="start">Task Threshold</ion-label>
                                    <ion-input type="number" placeholder="Task Threshold" name="task_threshold"
                                        value="{{ $user->task_threshold }}"></ion-input>
                                </ion-item>
                                <ion-item mode="ios">
                                    <ion-label slot="start">Negetive Balance</ion-label>
                                    <ion-input type="text" placeholder="Negative Balance" name="negative_balance_amount"
                                        value="{{ number_format($user->negative_balance_amount,2) }}" required></ion-input>
                                </ion-item>
                                

                                <ion-button expand="block" type="submit" color="primary">Save
                                    Config</ion-button>
                            </form>
                        </ion-content>
                    </ion-modal>
                @empty
                    <ion-item class="text-center text-danger">
                        <ion-label>No Subscriber to Show</ion-label>
                    </ion-item>
                @endforelse
            </ion-list>
        </ion-card-body>
    </ion-card>
@endsection

@section('script')
@if(count($users) > 0)
<script>
    const loading = document.querySelector('ion-loading');
    const alertCustom = document.querySelector('ion-alert');
    
    $('#save-{{$user->id}}').submit((e) => {
        const modal = document.querySelector('#modal-{{$user->id}}')
        e.preventDefault();
        loading.message = "Saving Config...";
        loading.present();

        $.ajax({
            type: "post",
            url: "/admin/task-config",
            data: new FormData($('#save-{{$user->id}}')[0]),
            contentType:false,
            processData: false,
            success: function (response) {
                loading.dismiss();
                modal.dismiss();
                alertCustom.message = response.message;
                alertCustom.buttons = [{
                    text: 'OK',
                }];
                alertCustom.present();
            },
            error: function (error) {
               console.log(error); 
                loading.dismiss();
                modal.dismiss();
                alertCustom.message = error.responseJSON.message;
                alertCustom.buttons = [{
                    text: 'OK',
                }];
                alertCustom.present();
            }
        });
    })
</script>

@endif
@endsection
