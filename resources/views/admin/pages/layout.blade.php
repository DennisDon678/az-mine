<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css"
        integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Ionic framework --}}
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>@yield('title') {{ env('APP_NAME') }}</title>

    <style>
        #main {
            max-width: 850px;
            margin: auto;
        }
    </style>
</head>

<body>
    <ion-app id="main">
        <ion-header mode="ios">
            <ion-toolbar color="primary">
                @yield('header')
            </ion-toolbar>
        </ion-header>
        <ion-content scroll="true">
            @yield('content')
        </ion-content>
            <ion-tab-bar slot="bottom" color="primary">
                <ion-tab-button href="/admin/dashboard" {{request()->segment(count(request()->segments())) == "dashboard"? "selected":""}}>
                    <ion-icon name="home" size="large"></ion-icon>
                    <ion-label>
                        <h5>Home</h5>
                    </ion-label>
                </ion-tab-button>
                <ion-tab-button href="/admin/users" {{request()->segment(count(request()->segments())) == "users"? "selected":""}}>
                    <ion-icon name="people" size="large"></ion-icon>
                    <ion-label>
                        <h5>Users</h5>
                    </ion-label>
                </ion-tab-button>
                <ion-tab-button href="/admin/setting" {{request()->segment(count(request()->segments())) == "setting"? "selected":""}}>
                    <ion-icon name="settings" size="large"></ion-icon>
                    <ion-label>
                        <h5>Settings</h5>
                    </ion-label>
                </ion-tab-button>
            </ion-tab-bar>
        <ion-loading mode="ios"></ion-loading>
        <ion-alert mode="ios"></ion-alert>
    </ion-app>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"
        integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    @yield('script')
</body>

</html>
