<div class="flex flex-wrap mb-8 p-6 justify-between items-center bg-grey-800 shadow">
    <h3 class="text-4xl text-grey-600">#{{ $session->id }}</h3>
    <div class="flex flex-col items-center">
        <div class="text-lg">
            <span>{{ $session->server->address }}</span>
            <small class="text-grey-600">por {{ $session->duration }} minutos</small>
        </div>
        <div class="flex-grow-0 text-sm text-grey-600">{{ $session->created_at }} ({{ $session->created_at->diffForHumans() }})</div>
    </div>
    @if(!$session->active)
        <a href="{{ route('sessions.show', $session) }}" class="trans-fast flex items-center justify-center
                        px-5 py-3
                        font-mono text-lg
                        text-blue-100 hover:text-white
                        no-underline
                        bg-blue-800 hover:bg-blue-700 hover:shadow">Ver</a>
    @endif

</div>