@extends('layouts.app')

@section('content')
    <h1>Sessions</h1>
    <ul>
        @forelse ($sessions as $session)
        <li>
            <a href="{{ route('sessions.show', $session) }}"><strong>{{ $session->active ? 'Active' : 'Ended' }}</strong> session - {{ $session->steamid }} on {{ $session->server->address }}[{{ $session->server->id }}]</a>
        </li>
        @empty
            <h4>There are no sessions available</h4>
        @endforelse
    </ul>
@endsection