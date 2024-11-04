@extends('admin.pages.layout')

@section('header')
    <ion-title>Referral Settings</ion-title>
    <ion-button slot="start" href="/admin/dashboard">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection

@section('content')
    <ion-card mode="ios">
        <ion-card-header class="text-center">
            <ion-title>Settings</ion-title>
        </ion-card-header>

        <ion-card-body>
            <form action="" id="settings">
                @csrf
                <ion-list>
                    <ion-item>
                        {{-- <ion-icon name="whatsapp-outline"></ion-icon> --}}
                        <ion-input label-placement="floating" placeholder="Percentage commission"
                            value="{{ $referral_config->percentage }}" name="percentage">
                            <ion-label slot="label">
                                Percentage
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
            url: '/admin/referral-config',
            data: new FormData($('#settings')[0]),
            contentType:false,
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