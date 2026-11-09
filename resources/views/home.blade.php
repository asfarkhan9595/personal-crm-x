@extends('layouts.app')

@section('title', $settings->site_name ?? ($profile->name ?? 'Portfolio'))

@section('content')
    @include('partials.hero')
    @include('partials.about')
    @include('partials.skills')
    @include('partials.projects')
    @include('partials.experience')
    @include('partials.blog')
    @include('partials.resume-cta')
    @include('partials.contact')
@endsection

