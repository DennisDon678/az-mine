@extends('admin.pages.layout')

@section('header')
    <ion-title>Settings</ion-title>
    <ion-button slot="start" href="/admin/dashboard">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection

@section('content')
    <ion-card mode="ios" class="ion-padding">
        <ion-card-header class="text-center">
            <ion-title>Settings</ion-title>
        </ion-card-header>

        <ion-card-body>
            <form action="" id="settings">
                @csrf
                <ion-list>
                    <ion-item>
                        {{-- <ion-icon name="whatsapp-outline"></ion-icon> --}}
                        <ion-input label-placement="floating" placeholder="Whatsapp Link for customer service."
                            value="{{ $settings->whatsapp }}" name="whatsapp">
                            <ion-label slot="label">
                                Whatsapp link Settings
                            </ion-label>
                        </ion-input>
                    </ion-item>
                    <ion-item>
                        {{-- <ion-icon name="telegram-outline"></ion-icon> --}}
                        <ion-input label-placement="floating" placeholder="Telegram Link for customer service."
                            value="{{ $settings->telegram }}" name="telegram">
                            <ion-label slot="label">
                                Telegram link Settings
                            </ion-label>
                        </ion-input>
                    </ion-item>
                </ion-list>
                <ion-button expand="block" class="mt-3" type="submit">
                    Save Settings
                    <ion-icon name="save-outline" slot="end"></ion-icon>
                </ion-button>
            </form>
        </ion-card-body>
    </ion-card>

    <ion-card mode="ios" class="ion-padding">
        <ion-card-header class="text-center">
            <ion-title>System Available Time</ion-title>
        </ion-card-header>
        <ion-card-body>
            <form action="" id="system-time">
                @csrf
                <ion-list>
                    <ion-item>
                        {{-- <ion-icon name="whatsapp-outline"></ion-icon> --}}
                        <ion-input type="time" label-placement="floating" placeholder="Whatsapp Link for customer service."
                            value="" name="open_time">
                            <ion-label slot="label">
                                System Opening time
                            </ion-label>
                        </ion-input>
                    </ion-item>
                    <ion-item>
                        {{-- <ion-icon name="telegram-outline"></ion-icon> --}}
                        <ion-input type="time" label-placement="floating" placeholder="Telegram Link for customer service."
                            value="" name="close_time">
                            <ion-label slot="label">
                                System Closing time
                            </ion-label>
                        </ion-input>
                    </ion-item>
                </ion-list>
                <ion-button expand="block" class="mt-3" type="submit">
                    Save System Time
                    <ion-icon name="save-outline" slot="end"></ion-icon>
                </ion-button>
            </form>
        </ion-card-body>
    </ion-card>
@endsection


@section('script')
    <script>
        const loading = document.querySelector('ion-loading');
        const alertCustom = document.querySelector('ion-alert');

        $('#settings').on('submit', function(e) {
            e.preventDefault();
            loading.message = 'Saving Settings...';
            loading.present();

            $.ajax({
                type: 'post',
                url: '/admin/setting',
                data: new FormData($('#settings')[0]),
                contentType: false,
                processData: false,
                success: function(response) {
                    loading.dismiss();
                    alertCustom.message = response.message;
                    alertCustom.buttons = [{
                        text: 'OK',
                    }];
                    alertCustom.present();
                },
                error: function(xhr, status, error) {
                    loading.dismiss();
                    alertCustom.message = 'Error saving settings.';
                    alertCustom.buttons = [{
                        text: 'OK',
                    }];
                    alertCustom.present();
                }
            });

        });
        
        $('#system-time').on('submit', function(e) {
            e.preventDefault();
            loading.message = 'Saving System Time...';
            loading.present();

            $.ajax({
                type: 'post',
                url: '/admin/system-time',
                data: new FormData($('#system-time')[0]),
                contentType: false,
                processData: false,
                success: function(response) {
                    loading.dismiss();
                    alertCustom.message = response.message;
                    alertCustom.buttons = [{
                        text: 'OK',
                    }];
                    alertCustom.present();
                },
                error: function(xhr, status, error) {
                    loading.dismiss();
                    alertCustom.message = 'Error saving settings.';
                    alertCustom.buttons = [{
                        text: 'OK',
                    }];
                    alertCustom.present();
                }
            });
        });
    </script>
@endsection
