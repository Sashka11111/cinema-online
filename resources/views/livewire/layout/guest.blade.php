<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/auth-page.css') }}" rel="stylesheet">
    @livewireStyles
</head>
<body>
{{ $slot }}
@livewireScripts
</body>
</html>
