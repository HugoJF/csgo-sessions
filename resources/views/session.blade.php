@extends('layouts.app')

@section('content')
    <h1>Session {{ $session->id }} data</h1>
    <ul>
        @forelse ($data as $key => $value)
        <li>
            <code>{{ $key }} = {{ $value }}</code>
        </li>
        @empty
            <h4>There are not stats for this session</h4>
        @endforelse
    </ul>
@endsection