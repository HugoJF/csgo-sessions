@extends('layouts.blank')

@php
    arsort($data['damage-by-weapon']);
    arsort($data['hits-by-hitgroup']);
@endphp

@section('content')
    <div class="bg-grey-800 mx-32 py-6 px-16 shadow-lg">
        <h1 class="mb-8 font-hairline text-grey-500 text-center text-3xl tracking-wider">Session #{{ $session->id }} statistics</h1>
        <div class="flex flex-row">
            <!-- Top guns -->
            <div class="w-1/3">
                <h2 class="p-2 mb-4 font-light text-center text-2xl tracking-wider uppercase">Top weapons</h2>
                <table>
                    @foreach ($data['damage-by-weapon'] as $weapon => $damage)
                        <tr>
                            <td class="font-medium text-3xl text-grey-500">{{ $loop->index + 1}}</td>
                            <td class="px-3 font-csgo text-center text-2xl text-grey-400">
                                {{ csgo_getchar_by_name($weapon) }}
                            </td>
                            <td class="">
                                <div class="">
                                    <p class="font-medium uppercase">{{ $weapon }}</p>
                                    <p class="text-grey-600 font-normal tracking-tight">
                                        {{ $data['kills-by-weapon'][$weapon] ?? 0 }} kills
                                        <small>({{ to_unit($damage) }} damage)</small>
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            
            <!-- Hit groups -->
            <div class="flex flex-col p-4 pt-0 w-1/3">
                <h2 class="p-2 mb-4 font-light text-center text-2xl tracking-wider uppercase">Hit groups</h2>
                
                <table>
                    @foreach ($data['hits-by-hitgroup'] as $part => $hits)
                        <tr class="text-lg">
                            <td class="text-grey-600 font-normal">{{ str_replace('_', ' ', Str::title($part)) }}</td>
                            <td class="">
                                <div class="flex items-baseline">
                                    <span class="font-medium">{{ $hits }}</span>
                                    <small class="ml-1 text-grey-400 tracking-tight">hits</small>
                                    <small class="ml-1 text-grey-600 tracking-tight">({{ number_format($hits / $data['hits-total'] * 100, 0) }}%)</small>
                                </div>
                            </td>
                            <td class="">
                                @php
                                    $kills = $data['kills-by-hitgroup'][$part] ?? null;
                                    $killsTotal = $data['kills-total'];
                                @endphp
                                <div class="flex items-baseline">
                                    <span class="font-medium">{{ $kills ?? '-' }}</span>
                                    <small class="ml-1 text-grey-400 tracking-tight">kills</small>
                                    <small class="ml-1 text-grey-600 tracking-tight">({{ number_format(($kills ?? 0) / ($killsTotal ?: 1) * 100, 0) }}%)</small>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            
            <!-- Stats -->
            <div class="w-1/3">
                <h2 class="p-2 mb-4 font-light text-center text-2xl tracking-wider uppercase">Stats</h2>
                <div class="flex flex-wrap">
                    <!-- Kills/Min -->
                    <div class="flex flex-col w-1/2 justify-center p-3 text-center">
                        <h3 class="text-grey-400 text-xl font-light tracking-wide uppercase">KILLS / MIN</h3>
                        <h2 class="text-grey-200 font-normal text-3xl tracking-tight">
                            {{ number_format($data['kills-per-min'], 1) ?? 'N/A' }}
                        </h2>
                        <small class="text-grey-500 text-sm font-light tracking-tight">{{ $data['duration'] ?? 'N/A' }} minutes</small>
                    </div>
                    
                    <!-- Damage/Min -->
                    <div class="flex flex-col w-1/2 justify-center p-3 text-center">
                        <h3 class="text-grey-400 text-xl font-light tracking-wide uppercase">DAMAGE / MIN</h3>
                        <h2 class="text-grey-200 font-normal text-3xl tracking-tight">
                            {{ number_format($data['damage-per-min'], 1) ?? 'N/A' }}
                        </h2>
                        <small class="text-grey-500 text-sm font-light tracking-tight">{{ $data['damage-total'] ?? 'N/A' }} damage</small>
                    </div>
                    
                    <!-- KDR -->
                    <div class="flex flex-col w-1/2 justify-center p-3 text-center">
                        <h3 class="text-grey-400 text-xl font-light tracking-wide uppercase">KDR</h3>
                        <h2 class="text-grey-200 font-normal text-3xl tracking-tight">
                            {{ number_format($data['kdr'], 1) }}
                        </h2>
                        <small class="text-grey-500 text-sm font-light tracking-tight">{{ $data['kills-total'] ?? 'N/A' }} kills</small>
                    </div>
                    
                    <!-- HS% -->
                    <div class="flex flex-col w-1/2 justify-center p-3 text-center">
                        <h3 class="text-grey-400 text-xl font-light tracking-wide uppercase">HS %</h3>
                        <h2 class="text-grey-200 font-normal text-3xl tracking-tight">
                            {{ number_format($data['hs-percentage'], 1) }}%
                        </h2>
                        <small class="text-grey-500 text-sm font-light tracking-tight">{{ $data['kills-by-hitgroup']['head'] ?? 0 }} headshots</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex mt-8 justify-center">
            <a href="{{ route('sessions.search', ['id' => $session->steamid]) }}" class="trans-fast flex items-center justify-center
                    h-16 w-32
                    font-mono text-lg
                    text-blue-100 hover:text-white
                    no-underline
                    bg-blue-800 hover:bg-blue-700 hover:shadow">< Back</a>
        </div>
    </div>
@endsection