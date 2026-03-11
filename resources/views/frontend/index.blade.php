@extends('layouts.frontend')

@section('title', 'BATIM GADAI — Sistem Informasi Gadai Elektronik')

@section('content')
    @include('frontend.partials.navbar')
    @include('frontend.partials.hero')
    @include('frontend.partials.about')
    @include('frontend.partials.services')
    @include('frontend.partials.how-it-works')
    @include('frontend.partials.stats-divider')
    @include('frontend.partials.mobile-app')
    @include('frontend.partials.branches')
    @include('frontend.partials.terms')
    @include('frontend.partials.faq')
    @include('frontend.partials.contact')
    @include('frontend.partials.footer')
@endsection