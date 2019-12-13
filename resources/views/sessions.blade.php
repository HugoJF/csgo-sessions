@extends('layouts.app')

@section('content')
    <h1>Sessions</h1>
    <ul>
        @forelse ($sessions as $session)
        <li>
            <strong>{{ $session->active ? 'Active' : 'Ended' }}</strong> session - {{ $session->steamid }} on {{ $session->server->address }}[{{ $session->server->id }}]
        </li>
        @empty
            <h4>There are no sessions available</h4>
        @endforelse
    </ul>
@endsection