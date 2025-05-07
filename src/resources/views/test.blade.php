<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.title.faily') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark text-color">
<main>
  @include('includes.navbar')
    <div class="page-container" id="app">
        <test></test>
    <div>
</main>
@include('includes.footer')
</body>
</html>
<script>
    window.locale = "{{ app()->getLocale() }}";
</script>
