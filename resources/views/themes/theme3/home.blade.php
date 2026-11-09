@extends('layouts.theme3')

@section('title', $profile->name ?? 'Portfolio — Theme 3 (Glass)')

@section('content')
    @include('themes.theme3.hero')
    @include('themes.theme3.about')
    @include('themes.theme3.skills')
    @include('themes.theme3.projects')
    @include('themes.theme3.experience')
    @include('themes.theme3.services')
    @include('themes.theme3.blog')
    @include('themes.theme3.resume-cta')
    @include('themes.theme3.contact')
@endsection
