<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Ocean Food') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
            rel="stylesheet">
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/285676dd7f.js" crossorigin="anonymous"></script>


    </head>
    <body class="antialiased h-full bg-jungleGreen">
{{--       <header class="sticky top-0 z-50">--}}
{{--           @include('layouts.navigation')--}}
{{--       </header>--}}
{{--        <main>--}}
{{--            {{$slot}}--}}
{{--        </main>--}}

        <div class="h-full flex flex-col">
            <header class="sticky top-0 z-50">
                @include('layouts.navigation')
            </header>

            <div class="flex flex-1 overflow-hidden">
                <main class="flex-1 overflow-y-auto">
                    {{$slot}}
                </main>
            </div>
        </div>
    </body>
</html>
