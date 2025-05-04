<!DOCTYPE html>
<html lang={{app()->getLocale() == 'ar' ? 'ar' : 'en' }} dir={{app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <title>Dashboard</title>

</head>

<body class="bg-white">
    <div x-data="{ sidebarOpen: false }" class="main-container flex h-screen p-1">
        @include('layout.sidebar')
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('layout.header')
            <main class="flex-1 overflow-x-auto overflow-y-auto m-5">
                <span class="title text-xl font-bold text-primary m-1">{{__('web.Hello')}}
                    {{ Auth::user()->first_name . ' ' . Auth::user()->last_name  }}</span>

                @yield('body')
            </main>
        </div>
    </div>
</body>

</html>