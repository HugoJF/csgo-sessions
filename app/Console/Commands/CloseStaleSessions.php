<?php

namespace App\Console\Commands;

use App\Services\SessionService;
use App\Session;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class CloseStaleSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close sessions that were not closed by PlayerDisconnect events';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var SessionService $service */
        $service = app(SessionService::class);

        /** @var Collection $stale */
        $stale = Session::query()->where('updated_at', '<', now()->subHour());
        $count = $stale->count();
        log("Found $count stale sessions", ['sessions' => $stale->pluck('id')]);

        /** @var Session $session */
        foreach ($stale as $session) {
            log("Closing sessions for SteamID: $session->steamid");
            $service->closeActiveSessions($session->steamid);
        }
    }
}
