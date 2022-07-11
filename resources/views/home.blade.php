<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>@yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{asset('/images/favicon.ico')}}">

    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="http://jqueryvalidation.org/files/dist/jquery.validate.js"></script>
</head>
<body>
<div id="alert" class="alert container-fluid" role="alert"></div>
@yield('content')
@yield('scripts')
<script src="{{asset('js/bootstrap.js')}}"></script>
<script src="{{asset('js/app.js')}}"></script>
</body>
</html>
