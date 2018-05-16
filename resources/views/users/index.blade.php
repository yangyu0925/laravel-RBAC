@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>欢迎 {{auth()->user()->name}}</h1>
    </div>

@endsection