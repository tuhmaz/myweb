@php
$configData = Helper::appClasses();
$isFront = true;
@endphp

@section('layoutContent')

@extends('layouts/commonMaster' )

@include('layouts/sections/navbar/navbar-front')



@yield('content')


@include('layouts/sections/footer/footer-front')
@endsection
