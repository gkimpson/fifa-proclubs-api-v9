<!DOCTYPE html>
{{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">--}}
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'ProClubsAPI') }}</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
{{--        <link href="{{ asset('bladewind/css/animate.min.css') }}" rel="stylesheet" />--}}
{{--        <link href="{{ asset('bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />--}}
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
            <link rel="stylesheet" href="{{ asset('css/lity.min.css') }}">
        <script src="https://unpkg.com/flowbite@1.4.7/dist/flowbite.js"></script>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="{{ asset('js/jquery-2.2.4.min.js') }}" defer></script>
        <script src="{{ asset('js/lity.min.js') }}" defer></script>
{{--        <script>var notification_timeout,user_function,el_name,momo_obj,delete_obj;var dropdownIsOpen = false;</script>--}}
        @stack('head-scripts')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation', ['user' => $user])
            <header class="bg-white shadow">
                @isset($header)
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header}}
                </div>
                @endisset

                @isset($breadcrumbs)
                <div class="max-w-7xl mx-auto py-1 px-8 sm:px-1 lg:px-1">
                    {{ $breadcrumbs }}
                </div>
                @endisset
            </header>

            <!-- Page Content -->
            @isset($slot)
            <main class="container mx-auto px-4 sm:px-8">
                {{ $slot }}
            </main>
            @endif
        </div>

        <x-proclubs.monitoring />
    </body>
</html>
