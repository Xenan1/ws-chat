<?php

namespace App\Broadcasting;

use App\Logging\ChatLogger;
use App\Logging\Enum\LogLevels;
use Exception;
use Illuminate\Contracts\Broadcasting\Broadcaster;
use WebSocket\Client;

class WebSocketBroadcaster implements Broadcaster
{
    protected Client $client;

    public function __construct(protected ChatLogger $logger)
    {
        $this->client = new Client('ws://ws:80');
    }

    public function auth($request)
    {
        return true;
    }

    public function validAuthenticationResponse($request, $result)
    {
        return ['message' => 'ok'];
    }

    public function broadcast(array $channels, $event, array $payload = [])
    {
        $this->logger->log(LogLevels::Info, config('broadcasting.url'));
        $this->logger->log(LogLevels::Debug, http_build_query($payload));
        try {
            $this->client->text(json_encode($payload));
            $this->client->disconnect();
            $this->logger->log(LogLevels::Info, 'Message was sent successfully');
        } catch (Exception $e) {
            $this->logger->log(LogLevels::Error, 'Ошибка при отправке сообщения на WebSocket сервер: ' . $e->getMessage());
        }
    }
}
