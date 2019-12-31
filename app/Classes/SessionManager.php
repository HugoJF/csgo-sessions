<?php

namespace App\Classes;

use App\Exceptions\MissingEventDataException;
use App\Services\ServerService;
use App\Session;
use Illuminate\Support\Collection;

class SessionManager
{
    protected $server;
    protected $session;

    protected $collectors;

    // Flag to mark session as modified (when at least one collector received an event)
    protected $dirty = false;

    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->server = $session->server;

        $this->loadCollectors();
    }

    public function __destruct()
    {
        if ($this->dirty) {
            $this->session->touch();
            $this->session->save();
        }
    }

    public function getRedisPrefix()
    {
        return $this->session->id;
    }

    public function getRedisKey(string $name)
    {
        $prefix = $this->getRedisPrefix();

        return "$prefix.$name";
    }

    private function loadCollectors()
    {
        if (!$this->session)
            return;

        /** @var ServerService $service */
        $service = app(ServerService::class);

        /** @var Collection $collectors */
        $collectors = $service->getCollectors($this->server);

        $this->collectors = $collectors->map(function ($collector) {
            return new $collector($this);
        });
    }

    public function handleEvent(array $event)
    {
        $address = $event['server'] ?? false;

        if ($address === $this->server->address)
            $this->handleGenericEvent($event);
    }

    /**
     * @param array $event
     *
     * @throws MissingEventDataException
     */
    protected function handleGenericEvent(array $event): void
    {
        /** @var Collector $collector */
        foreach ($this->collectors as $collector) {
            if ($collector->accepts($event)) {
                $this->dirty = true;
                $collector->collect($event);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getSession()
    {
        return $this->session;
    }
}
