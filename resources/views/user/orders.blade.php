@extends('user.layout')

@section('title', 'orders');
@section('header')
    <ion-title>Order History</ion-title>
    <ion-button slot="start" href="/user/dashboard">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection
@section('content')
    <div class="container">
        <!-- Button Container for Deposit and Withdraw -->
        <div class="mt-3 nav nav-pills">
            <div class="col nav-item text-center rounded">
                <a class=" btn-dark nav-link active " data-bs-toggle="pill" data-bs-target="#deposit">Pending</a>
            </div>
            <div class="col nav-item text-center rounded">
                <a class=" nav-link " data-bs-toggle="pill" data-bs-target="#withdraw">Completed</a>
            </div>
        </div>

        <div class="tab-content mt-2" id="pills-tabContent">
            <div class="tab-pane fade show active" id="deposit" role="tabpanel" aria-labelledby="pills-home-tab">
                @php
                    $pending = App\Models\TaskLog::where('user_id', Auth::user()->id)
                        ->where('completed', false)
                        ->get();
                    $completed = App\Models\TaskLog::where('user_id', Auth::user()->id)
                        ->where('completed', true)
                        ->get();
                @endphp
                @forelse ($pending as $deposit)
                    <ion-card mode="ios" class="ion-padding">
                        <ion-card-body>
                            <div class="d-flex mb-2">
                                <div class="col-6">
                                    <ion-label class="bold"><strong>Order ID:</strong></ion-label>
                                </div>
                                <div class="col-6">
                                    {{ $deposit->order_id }}
                                </div>
                            </div>
                            <div class="d-flex mb-2">
                                <div class="col-6">
                                    <ion-label class="bold"><strong>Product Price:</strong></ion-label>
                                </div>
                                <div class="col-6">
                                    ${{ $deposit->product_amount }}
                                </div>
                            </div>
                            <div class="d-flex mb-2">
                                <div class="col-6">
                                    <ion-label class="bold"><strong>Profit:</strong></ion-label>
                                </div>
                                <div class="col-6">
                                    ${{ number_format($deposit->amount_earned, 2) }}
                                </div>
                            </div>
                            <div class="d-flex mb-2">
                                <div class="col-6">
                                    <ion-label class="bold"><strong>Action:</strong></ion-label>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-success" id="{{ $deposit->order_id }}">Submit</button>
                                </div>
                            </div>
                        </ion-card-body>
                    </ion-card>
                @empty
                    <ion-item>
                        <ion-label>No Pending Order found.</ion-label>
                    </ion-item>
                @endforelse
            </div>
            <div class="tab-pane fade" id="withdraw" role="tabpanel" aria-labelledby="pills-profile-tab">
                @forelse ($completed as $deposit)
                    <ion-card mode="ios" class="ion-padding">
                        <ion-card-body>
                            <div class="d-flex mb-2">
                                <div class="col-6">
                                    <ion-label class="bold"><strong>Order ID:</strong></ion-label>
                                </div>
                                <div class="col-6">
                                    {{ $deposit->order_id }}
                                </div>
                            </div>
                            <div class="d-flex mb-2">
                                <div class="col-6">
                                    <ion-label class="bold"><strong>Product Price:</strong></ion-label>
                                </div>
                                <div class="col-6">
                                    ${{ $deposit->product_amount }}
                                </div>
                            </div>
                            <div class="d-flex mb-2">
                                <div class="col-6">
                                    <ion-label class="bold"><strong>Profit:</strong></ion-label>
                                </div>
                                <div class="col-6">
                                    ${{ number_format($deposit->amount_earned, 2) }}
                                </div>
                            </div>
                        </ion-card-body>
                    </ion-card>
                @empty
                    <ion-item>
                        <ion-label>No completed order found.</ion-label>
                    </ion-item>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const alertCustom = document.querySelector('ion-alert');
        const loading = document.querySelector('ion-loading');
    </script>

    @foreach ($pending as $pending)
        <script>
            $('#{{ $pending->order_id}}').click(() => {
                loading.message = "Submitting Order #{{$pending->order_id}}...";
                loading.present();

                $.ajax({
                    type: "get",
                    url: "/user/submit-pending-task?order_id={{$pending->order_id}}",
                    success: function (response) {
                        loading.dismiss();
                        alertCustom.message = response.message;
                        alertCustom.buttons = [{
                            text: 'OK',
                            handler: () => {
                                loading.message = "Refreshing...";
                                loading.present();
                                setTimeout(() => {
                                    location.href = "/user/orders";
                                }, 2000);
                            }
                        }];
                        alertCustom.present();
                    },
                    error: (err)=>{
                        loading.dismiss();
                        alertCustom.message = err.responseJSON.error;
                        alertCustom.buttons = ['ok'],
                        alertCustom.present();
                    }
                });
            })
        </script>
    @endforeach
@endsection
