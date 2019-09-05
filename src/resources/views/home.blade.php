@extends('layouts.app')

@section('title', 'Home')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <p><a href="/register">Register</a></p>
    <p><a href="/login">Login</a></p>
@endsection
