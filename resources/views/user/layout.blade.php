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
            max-width: 650px;
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
            <ion-refresher id="refresher" slot="fixed">
                <ion-refresher-content></ion-refresher-content>
            </ion-refresher>
            @yield('content')
        </ion-content>
        <ion-tab-bar slot="bottom" color="primary">
            <ion-tab-button href="/user/dashboard"
                {{ request()->segment(count(request()->segments())) == 'dashboard' ? 'selected' : '' }}>
                <ion-icon name="home" size="large"></ion-icon>
                <ion-label>
                    <h5>Home</h5>
                </ion-label>
            </ion-tab-button>
            <ion-tab-button href="/user/lucky"
                {{ request()->segment(count(request()->segments())) == 'lucky' ? 'selected' : '' }}>
                <ion-icon name="compass" size="large"></ion-icon>
                <ion-label>
                    <h5>Lucky Draw</h5>
                </ion-label>
            </ion-tab-button>
            <ion-tab-button href="/user/earn"
                {{ request()->segment(count(request()->segments())) == 'earn' ? 'selected' : '' }}>
                <ion-icon name="flash" size="large"></ion-icon>
                <ion-label>
                    <h5>Earn</h5>
                </ion-label>
            </ion-tab-button>
            <ion-tab-button href="/user/orders"
                {{ request()->segment(count(request()->segments())) == 'orders' ? 'selected' : '' }}>
                <ion-icon name="receipt" size="large"></ion-icon>
                <ion-label>
                    <h5>Orders</h5>
                </ion-label>
            </ion-tab-button>
            <ion-tab-button href="/user/history"
                {{ request()->segment(count(request()->segments())) == 'history' ? 'selected' : '' }}>
                <ion-icon name="book" size="large"></ion-icon>
                <ion-label>
                    <h5>History</h5>
                </ion-label>
            </ion-tab-button>
        </ion-tab-bar>
        <ion-loading mode="ios"></ion-loading>
        <ion-alert mode="ios"></ion-alert>
        <ion-toast mode="ios"></ion-toast>
    </ion-app>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"
        integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
  const refresher = document.getElementById('refresher');

  refresher.addEventListener('ionRefresh', () => {
    setTimeout(() => {
      location.reload();
      refresher.complete();
    }, 2000);
  });
</script>

    @yield('script')
</body>

</html>
