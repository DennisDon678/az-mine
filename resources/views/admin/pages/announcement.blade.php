@extends('admin.pages.layout')

@section('header')
    <ion-title>Announcement</ion-title>
    <ion-button slot="start" href="/admin/dashboard">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection

@section('content')
    <ion-card class="ion-padding" mode="ios">
        <ion-card-header class="text-center">
            <ion-title>New Announcement</ion-title>
        </ion-card-header>
        <ion-card-content mode="ios">
            <form action="" id="notice">
                @csrf
                <ion-textarea name="notice" id="announcement" mode="md" fill="outline"
                    placeholder="Enter announcement here..." value="{{ $announcement->notice }}"
                    rows="4"></ion-textarea>
                <ion-button type="submit" expand="full">Save</ion-button>
            </form>
        </ion-card-content>
    </ion-card>
    </ion-card>
@endsection

@section('script')
    <script>
        const loading = document.querySelector('ion-loading');
        const alertCustom = document.querySelector('ion-alert');

        $('#notice').submit((e) => {
            e.preventDefault();
            loading.message = 'Saving Announcement...';
            loading.present();

            // check if announcement is empty
            // if (($('#announcement').val() == '')) {
            //     loading.dismiss();
            //     alertCustom.message = 'Announcement field cannot be empty.';
            //     alertCustom.buttons = ['ok'],
            //         alertCustom.present();
            //     return;
            // }
            $.ajax({
                type: "post",
                url: "/admin/announcement",
                data: new FormData($('#notice')[0]),
                processData: false,
                contentType: false,
                success: function(response) {
                    loading.dismiss();
                    alertCustom.message = response.message;
                    alertCustom.buttons = [{
                            text: 'OK',
                            handler: function() {
                                loading.message = 'Reloading announcement';
                                loading.present();
                                setTimeout(() => {
                                    window.location.href = '/admin/announcement';
                                }, 2000);
                            }
                        }],
                        alertCustom.present();

                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    loading.dismiss();
                    alertCustom.message = 'Failed to save announcement.';
                    alertCustom.buttons = ['ok'],
                        alertCustom.present();
                }
            });
        })
    </script>
@endsection
