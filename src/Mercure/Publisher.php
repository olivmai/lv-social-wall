<?php

namespace App\Mercure;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class Publisher
{
    /**
     * @var MessageBusInterface
     */
    private $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public function publish(Update $update)
    {
        $this->bus->dispatch($update);
    }
}
