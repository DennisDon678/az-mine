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
            <ion-title>User Task Config</ion-title>
        </ion-card-header>

        <ion-card-body>
            <ion-list inset="true">
                <form action="/admin/task-config" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $negative->id }}">
                    <ion-item mode="ios">
                        <ion-label slot="start">Package Name:</ion-label>
                        <ion-input type="text" placeholder="Crypto Name" name=""
                            value="{{ $package->package_name }}" readonly required></ion-input>
                    </ion-item>

                    <ion-item mode="ios">
                        <ion-label slot="start">Current Set:</ion-label>
                        <ion-input type="text" placeholder="Current Set" name=""
                            value="{{ $userTask->current_set }}" readonly required></ion-input>
                    </ion-item>
                    <ion-item mode="ios">
                        <ion-label slot="start">Current Task Performed:</ion-label>
                        <ion-input type="text" placeholder="Order Performed" name=""
                            value="{{ $userTask->tasks_completed_today }}" readonly required></ion-input>
                    </ion-item>

                    <ion-item mode="ios">
                        <ion-label slot="start">Daily Order per Set:</ion-label>
                        <ion-input type="text" placeholder="Crypto Name" name=""
                            value="{{ $package->number_of_orders_per_day }}" readonly required></ion-input>
                    </ion-item>
                    <ion-item mode="ios">
                        <ion-label slot="start">Available Set Per Day:</ion-label>
                        <ion-input type="text" placeholder="Crypto Name" name="" value="{{ $package->set }}"
                            readonly required></ion-input>
                    </ion-item>
                    <ion-item mode="ios">
                        <ion-label slot="start">Start Task:</ion-label>
                        <ion-input type="text" placeholder="Task Enabled" name="task_start_enabled"
                            value="{{ $negative->task_start_enabled }}" required></ion-input>
                    </ion-item>
                    <ion-item mode="ios">
                        <ion-label slot="start">Task Threshold</ion-label>
                        <ion-input type="number" placeholder="Task Threshold" name="task_threshold"
                            value="{{ $negative->task_threshold }}"></ion-input>
                    </ion-item>
                    <ion-item mode="ios">
                        <ion-label slot="start">Negetive Balance</ion-label>
                        <ion-input type="text" placeholder="Negative Balance" name="negative_balance_amount"
                            value="{{ $negative->negative_balance_amount }}" required></ion-input>
                    </ion-item>
                    <ion-item mode="ios">
                        <ion-label slot="start">Percentage (in %)</ion-label>
                        <ion-input type="text" placeholder="Percentage" name="percentage"
                            value="{{ $negative->percentage }}" required></ion-input>
                    </ion-item>

                    <ion-button expand="block" type="submit" color="primary">Save
                        Config</ion-button>
                </form>
                <br>
                <br>
                <form method="post" action="/admin/rest-user-balance" class="mb-2">
                    @csrf
                    <input type="hidden" name="user" value="{{$negative->user_id}}" id="user">
                    <ion-button expand="block" type="submit" color="success">Reset Balance</ion-button>
                </form>
                <ion-button expand="block" color="dark" id="nextSet">Activate Next Set</ion-button>
            </ion-list>
        </ion-card-body>
    </ion-card>
@endsection

@section('script')
    <script>
        const loading = document.querySelector('ion-loading');
        const alertCustom = document.querySelector('ion-alert');

        $('#nextSet').click(function() {
            loading.message = "Activating Next Set...";
            loading.present();
            $.ajax({
                type: "get",
                url: "/admin/activate-next-set?id=" + $('#user').val(),
                success: function (response) {
                    loading.dismiss();
                    alertCustom.message = response.message;
                    alertCustom.buttons = [{
                        text:'Ok',
                        handler: function(){
                            window.location.href = "/admin/task-setting?user=" + $('#user').val();
                        }
                    }];
                    alertCustom.present();
                }
            });
        });
    </script>
    @if (Session::has('message'))
    <script>
        alertCustom.message = "{{Session::get('message')}}";
        alertCustom.buttons = ['Ok']
        alertCustom.isOpen = true;
    </script>
    @endif
    @if (Session::has('error'))
    <script>
        alertCustom.message = "{{Session::get('error')}}";
        alertCustom.buttons = ['Ok']
        alertCustom.isOpen = true;
    </script>
    @endif
@endsection
