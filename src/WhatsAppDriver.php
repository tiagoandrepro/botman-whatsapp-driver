<?php

namespace Botman\Drivers\WhatsApp;

use \BotMan\BotMan\Drivers\HttpDriver;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\Drivers\WhatsApp\Extensions\ButtonsTemplate;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class WhatsAppDriver extends HttpDriver
{

    const DRIVER_NAME = 'whatsapp';

    private function whatsAppUrl()
    {
        return config('botman.whatsapp.url').config('botman.whatsapp.port');
    }

    public function matchesRequest()
    {
        return !is_null($this->event->get('userId')) &&
            !is_null($this->event->get('message')) &&
            !is_null($this->event->get('type')) &&
            $this->event->get('type') == 'converstion';
    }

    public function getMessages()
    {
        if (empty($this->messages)) {
            $message = $this->event->get('message');
            $userId = $this->event->get('userId');
            $this->messages = [new IncomingMessage($message, $userId, $userId, $this->payload)];
        }
        return $this->messages;
    }

    public function isConfigured()
    {
        return true;
    }

    public function getUser(IncomingMessage $matchingMessage)
    {
        return new User($matchingMessage->getSender());
    }

    public function getConversationAnswer(IncomingMessage $message)
    {
        return Answer::create($message->getText())->setMessage($message);
    }

    public function buildServicePayload($message, $matchingMessage, $additionalParameters = [])
    {
        $parameters = [
            'receiver' => $matchingMessage->getRecipient(),
            'message' => $message->getText()
        ];

        if ($message instanceof \JsonSerializable) {
            $parameters = array_merge($message->jsonSerialize(),$parameters);
        }

        return $parameters;
    }

    public function sendPayload($payload)
    {
        return $this->http->post($this->whatsAppUrl().'/chats/send-message', [], $payload, [
            'Content-Type: application/json',
            'Accept: application/json',
        ], true);
    }

    public function buildPayload(Request $request)
    {
        $this->payload = new ParameterBag((array)json_decode($request->getContent(), true));
        $this->event = Collection::make((array)$this->payload->get('messages'));
        $this->content = $request->getContent();
        $this->config = Collection::make($this->config->get('whatsapp', []));
    }

    public function sendRequest($endpoint, array $parameters, IncomingMessage $matchingMessage)
    {
        $url = $this->whatsAppUrl().'/chats/send-message';
        $this->http->post($url, [], $parameters);
    }
}
