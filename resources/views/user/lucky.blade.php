@extends('user.layout')

@section('title', 'Lucky Draw');
@section('header')
    <ion-title>Lucky Draw</ion-title>
    <ion-button slot="start" href="/user/dashboard">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection

<style>
    #spin-container {
        max-width: 600px;
        margin: 0 auto;
    }

    .spin-to-win {
        max-width: 600px;
    }

    .spin-to-win img {
        width: 100%;
        height: auto;
    }

    #spinner {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        vertical-align: middle;
    }

    #spin-btn {
        cursor: pointer;
        background: white;
        /*   background-image:url('https://contentservice.mc.reyrey.net/image_v1.0.0/?id=d08f9524-26eb-53be-a6a1-bc8d8e19cc20&637070095483683129'); */
        background-size: 100% 100%;
        box-shadow: 0 0 15px rgba(0, 0, 0, .25);
        animation: spinBtn 2s linear infinite;
        border: 5px solid transparent;
        text-align: center;
    }

    #spin-btn p {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        margin: 0;
        font-family: 'Montserrat', sans-serif;
    }

    @keyframes spinBtn {
        0% {
            border: 5px solid black;
        }

        50% {
            border: 5px solid red;
        }

        100% {
            border: 5px solid black;
        }
    }

    #spin-btn,
    #spin-arrow {
        max-width: 180px;
        width: calc(100vw * .3);
        max-height: 180px;
        height: calc(100vw * .3);
        border-radius: 50%;
        display: inline-block;
        position: absolute;
    }

    #spin-arrow {
        transition-timing-function: ease-in-out;
        transition: 3s;
        /* animation: spin 300ms linear infinite; */
    }

    #spin-arrow:after {
        content: '';
        position: absolute;
        left: 2px;
        top: 2px;
        width: calc(100vw * .14167);
        max-width: 85px;
        height: calc(100vw * .14167);
        max-height: 85px;
        background-color: white;
        box-shadow: -2px -2px 10px rgba(0, 0, 0, .25);
    }

    /* btn styles */
    #si-btn {
        max-width: 320px;
        background: white;
        color: black;
        text-align: center;
        font-family: sans-serif;
        border-radius: 15px;
        margin: 0 auto 25px auto;
        padding: 0px;
        /*   transition:2s; */
        filter: invert(1);
        opacity: 0;
    }

    #si-btn a {
        color: inherit;
        text-decoration: none;
        display: inline-block;
        padding: 20px 35px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    ion-modal ion-content::part(scroll) {
        overflow-y: scroll;
        overscroll-behavior-y: contain;
    }
</style>

@section('content')
    <ion-card mode="ios">
        <ion-card-header mode="md">
            <ion-title class="text-center">Spin And Win</ion-title>
            <p class="text-center">click on spin to try your luck.</button></p>
        </ion-card-header>
        <div id="spin-container">
            <div id="spinner" class="spin-to-win">
                <img
                    src="https://contentservice.mc.reyrey.net/image_v1.0.0/?id=e61fefdc-85d3-5145-9b3a-821b2ad47040&637115173847423178" />
                <div id="spin-arrow"></div>
                <div id="spin-btn">
                    <p>SPIN
                    <p>
                </div>
            </div>
        </div>
    </ion-card>

    <ion-card>
        <ion-card-header>
            <ion-title class="text-center">Pending claims</ion-title>
            <p class="text-center">Pending Claims Has to be cleared to take part in the next draw.</button></p>
        </ion-card-header>
        <ion-card-content>
            @if ($pending)
                <ion-list>
                    <ion-item>
                        <ion-label>${{ number_format($pending->reward, 2) }} worth of {{ $pending->type }}</ion-label>
                        <ion-chip color="primary" id="claim">
                            <ion-icon slot="start" name="checkmark-circle-outline"></ion-icon>
                            <ion-label>Claim Reward</ion-label>
                        </ion-chip>
                    </ion-item>
                </ion-list>
            @else
                <p class="text-center">You do not have any pending claims!</p>
            @endif
        </ion-card-content>
    </ion-card>
@endsection

@section('script')
    <script>
        var spinBtn = document.querySelector('#spin-btn')
        var spinArrow = document.querySelector('#spin-arrow')
        const modal = document.querySelector('ion-modal');
        const image = document.querySelector('#image');
        const title = document.querySelector('#title');
        const price = document.querySelector('#price');
        const alertCustom = document.querySelector('ion-alert');
        const claim = document.querySelector('#claim');
        const loader = document.querySelector('ion-loading')
        var inoperation = false;

        spinBtn.addEventListener('click', function() {
            if (!inoperation) {
                inoperation = true;
                spinArrow.style.animation = 'spin 300ms linear infinite';

                $.ajax({
                    type: "get",
                    url: "/user/lucky-draw",
                    success: function(data) {
                        // pause animation
                        spinArrow.style.animationPlayState = 'paused';
                        // display alert
                        // alertCustom.message = data.message;

                        const item = data.reward;
                        if (item.type == 'credit') {
                            alertCustom.message =
                                `You have won $${item.amount} worth of credits. Click the claim button to Add this to your balance.`;

                            alertCustom.buttons = [{
                                text: 'Claim',
                                handler: () => {

                                }
                            }];
                        } else {
                            alertCustom.message =
                                `You have won $${item.amount} worth of product. Click the claim button to contact our support on how to claim this.`;

                            alertCustom.buttons = [{
                                text: 'Claim',
                                handler: () => {
                                    loader.message = 'Redirecting...';
                                    loader.present();
                                    setTimeout(() => {
                                        location.href = '/user/contact'
                                    }, 2000);
                                }
                            }];
                        }


                        alertCustom.present();
                    },
                    error: function(xhr) {
                        spinArrow.style.animationPlayState = 'paused';
                        // display alert
                        alertCustom.message = xhr.responseJSON.message;
                        alertCustom.buttons = [{
                            text: 'Okay'
                        }];
                        alertCustom.present();
                    },
                });
            }
        })
    </script>

    @if ($pending)
        <script>
            $('#claim').click(() => {
                loader.message = 'Claiming please wait...';
                loader.present();
                if ('{{ $pending->type }}' == 'credit') {
                    $.ajax({
                        type: "post",
                        url: "/user/claim-draw",
                        data: {
                            id: '{{ $pending->id }}',
                            _token: '{{ csrf_token() }}'
                        },
                        processData: true,
                        success: function(data) {
                            loader.dismiss();
                            alertCustom.message = data.message;
                            alertCustom.buttons = [{
                                text: 'Go To Dashboard',
                                handler: () => {
                                    location.href = '/user/dashboard'
                                }
                            }];
                            alertCustom.present();
                        }
                    });

                } else {
                    loader.dismiss();
                    alertCustom.message =
                        `You have won ${{$pending->reward}}" worth of product. Click the claim button to contact our support on how to claim this.`;

                    alertCustom.buttons = [{
                        text: 'Claim',
                        handler: () => {
                            loader.message = 'Redirecting...';
                            loader.present();
                            setTimeout(() => {
                                location.href = '/user/contact'
                            }, 2000);
                        }
                    }];
                    alertCustom.present();
                }
            });
        </script>
    @endif
@endsection
