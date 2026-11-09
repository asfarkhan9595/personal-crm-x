@extends('layouts.theme1')

@section('title', ($profile->name ?? 'Asfar Khan') . ' — Theme 1 (Nexus Glass)')

@section('content')
    @include('themes.theme1.hero')
    @include('themes.theme1.about')
    @include('themes.theme1.skills')
    @include('themes.theme1.experience')
    @include('themes.theme1.projects')
    @include('themes.theme1.services')
    @include('themes.theme1.blog')
    @include('themes.theme1.resume-cta')
    @include('themes.theme1.contact')
@endsection
