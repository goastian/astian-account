@extends('layouts.app')


@section('content')
    <x-captcha :only-links="true" />

    @inertia
@endsection
