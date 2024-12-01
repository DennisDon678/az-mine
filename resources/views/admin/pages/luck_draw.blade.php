@extends('admin.pages.layout')
@section('title', 'Lucky Draw')

@section('header')
    <ion-title>Lucky Items</ion-title>
    <ion-button slot="start" href="/admin/dashboard">
        <ion-icon name="arrow-back-outline"></ion-icon>
    </ion-button>
@endsection

@section('content')
    <ion-card mode="ios" class="ion-padding">
        <ion-card-header class="text-center">
            <ion-card-title>New Draw Item</ion-card-title>
        </ion-card-header>

        <ion-card-body>
            {{-- button for creating a lucky item --}}
            <ion-button id="newItem">Add a new item</ion-button>
        </ion-card-body>
    </ion-card>
    <ion-card mode="ios">
        <ion-card-header class="text-center">
            <ion-card-title>Items List</ion-card-title>
        </ion-card-header>

        <ion-card-body>
            <ion-list lines="full" inset="true">
                @forelse ($items as $package)
                    <ion-item>

                        <ion-label>

                            ${{ number_format($package->amount,2) }} {{ $package->type }}
                        </ion-label>

                        <ion-button color="success" id="{{ $package->id }}" slot="end"
                            class="ion-padding">Edit</ion-button>
                        <ion-button color="danger" class="ion-padding" href="/admin/lucky-draw/delete?id={{$package->id}}">
                            delete
                        </ion-button>
                    </ion-item>

                    <ion-modal trigger="{{ $package->id }}" initial-breakpoint="0.95" mode="ios">
                        <ion-header>
                            <ion-toolbar>
                                <ion-title>Edit Package {{ $package->package_name }}</ion-title>
                            </ion-toolbar>
                        </ion-header>
                        <ion-content class="ion-padding">
                            <form action="" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ $package->id }}">
                                <ion-item mode="ios">
                                    <ion-label slot="start">Item Type</ion-label>
                                    <ion-select placeholder="Select Item Type" name="type" fill="outline" value="{{ $package->type }}">
                                        <ion-select-option value="credit" >Credit</ion-select-option>
                                            <ion-select-option value="product"
                                                >product</ion-select-option>
                                    </ion-select>
                                </ion-item>
                                <ion-item mode="ios">
                                    <ion-label slot="start">Draw Value</ion-label>
                                    <ion-input type="text" placeholder="Package Name" name="amount"
                                        value="{{ $package->amount }}" required></ion-input>
                                </ion-item>

                                <ion-button expand="block" type="submit" color="primary">Save
                                    Package</ion-button>
                            </form>
                        </ion-content>
                    </ion-modal>
                @empty
                @endforelse
            </ion-list>

        </ion-card-body>
    </ion-card>

    <ion-card mode="ios" class="ion-padding">
        <ion-card-header class="text-center">
            <ion-card-title>Pending Claims</ion-card-title>
        </ion-card-header>
        <ion-card-body>
            <ion-list lines="full" inset="true">
                @forelse ($pendings as $package)
                    <ion-item>

                        <ion-label>
                            {{$package->user->username}}'s 
                            ${{ number_format($package->reward,2) }} {{ $package->type }}
                        </ion-label>

                        
                        <ion-button color="success" class="ion-padding" href="/admin/clear-claim?id={{$package->id}}">
                            Clear
                        </ion-button>
                    </ion-item>
                @empty
                <p class="text-center">
                    No Pending Product Claims
                </p>
                @endforelse
            </ion-list>
        </ion-card-body>
    </ion-card>

    <ion-modal id="newItemModal" initial-breakpoint="0.65" mode="ios">
        <ion-header>
            <ion-toolbar>
                <ion-title>Add New Item</ion-title>
            </ion-toolbar>
        </ion-header>
        <ion-content class="ion-padding">
            <form action="/admin/lucky-draw/add-item" method="post">
                @csrf
                <ion-item mode="ios">
                    <ion-label slot="start">Item Type</ion-label>
                    <ion-select placeholder="Select Item Type" name="type">
                        <ion-select-option value="credit">Credit</ion-select-option>
                        <ion-select-option value="product">product</ion-select-option>
                    </ion-select>
                </ion-item>
                <ion-item mode="ios">
                    <ion-label slot="start">value</ion-label>
                    <ion-input type="text" placeholder="Package Percentage" name="amount" required></ion-input>
                </ion-item>
                <ion-button type="submit" expand='block'>Add Item</ion-button>
            </form>
        </ion-content>
    </ion-modal>
@endsection

@section('script')
    <script>
        const newItemModal = document.querySelector('#newItemModal');

        $('#newItem').click(() => {
            newItemModal.present();
        })
    </script>
@endsection
