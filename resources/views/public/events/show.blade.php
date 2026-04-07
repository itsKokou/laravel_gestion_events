@extends('layouts.public')

@php
    use Illuminate\Support\Facades\Storage;
    $soldCount = $event->tickets()->whereNull('tickets.cancelled_at')->count();
    $remainingSeats = max(0, $event->capacity - $soldCount);
    $minPrice =
        $event->ticketTypes->where('is_active', true)->isNotEmpty()
            ? $event->ticketTypes->where('is_active', true)->min('price_cents')
            : null;
@endphp

@section('title', $event->name . " · Win's Events")

@section('content')
    @include('public.events.event-details')
@endsection
