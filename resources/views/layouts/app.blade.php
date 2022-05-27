<!DOCTYPE html>
{{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">--}}
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'ProClubsAPI') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link href="{{ asset('bladewind/css/animate.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="{{ asset('css/lity.min.css') }}">


        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="{{ asset('js/jquery-2.2.4.min.js') }}" defer></script>
        <script src="{{ asset('js/lity.min.js') }}" defer></script>
        <script>var notification_timeout,user_function,el_name,momo_obj,delete_obj;var dropdownIsOpen = false;</script>
        @stack('head-scripts')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation', ['user' => $user])

            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <script src="https://cdn.zipy.ai/sdk/v1.0/zipy.min.umd.js" crossorigin="anonymous"></script> <script> window.zipy && window.zipy.init('e038a759');</script>
        <script
            type="text/javascript"
            src="https://sdk.relicx.ai/relicx-sdk.min.js"
            onload="relicxSDK.init({orgID: '322c76a1-eaaa-493f-9bac-758d5e87a695',appID: '304a9ad9-254b-474f-9f0c-5b8116eec0a5'})">
        </script>
    </body>
</html>
