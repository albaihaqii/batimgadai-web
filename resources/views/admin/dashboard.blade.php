@extends('layouts.app')
@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Dashboard Admin</h1>
    <p class="text-gray-500 mt-2">Selamat datang, {{ auth()->user()->nama }}</p>
</div>
@endsection