@extends('layouts.public')

@php
    $isHome = request()->routeIs('home');
    $landing = $isHome && $q === '' && $events->currentPage() === 1;
    $items = collect($events->items());
    $featuredEvents = $landing ? $items->take(3) : collect();
    $restEvents = $landing ? $items->slice(3)->values() : $items;
    $allOnFeaturedOnly = $landing && $featuredEvents->isNotEmpty() && $restEvents->isEmpty();
@endphp

@section('title', $landing ? "Accueil · Win's Events" : "Soirées à venir · Win's Events")

@section('content')
    @include('public.home')
@endsection
