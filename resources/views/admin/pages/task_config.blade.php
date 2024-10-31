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
                <form action="" method="post" >
                    @csrf
                    <ion-item mode="ios">
                        <ion-label slot="start">Package Name:</ion-label>
                        <ion-input type="text" placeholder="Crypto Name" name=""
                            value="{{ $package->package_name }}" readonly required></ion-input>
                    </ion-item>

                    <ion-item mode="ios">
                        <ion-label slot="start">Current Set:</ion-label>
                        <ion-input type="text" placeholder="Current Set" name=""
                            value="{{$userTask->current_set}}"
                            readonly required></ion-input>
                    </ion-item>
                    <ion-item mode="ios">
                        <ion-label slot="start">Current Task Performed:</ion-label>
                        <ion-input type="text" placeholder="Order Performed" name=""
                            value="{{$userTask->tasks_completed_today}}"
                            readonly required></ion-input>
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
                            value="{{ number_format($negative->negative_balance_amount, 2) }}" required></ion-input>
                    </ion-item>

                    <ion-button expand="block" type="submit" color="primary">Save
                        Config</ion-button>
                </form>
                <br>
                <br>
                <form id="resetform-" class="mb-2">
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
        
    </script>
@endsection
