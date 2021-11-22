<?php

namespace BotMan\Drivers\WhatsApp\Messages\Outgoing\Actions;

class Button extends \BotMan\BotMan\Messages\Outgoing\Actions\Button
{

    protected $buttonId, $displayText;

    public function buttonId($buttonId)
    {
        $this->buttonId = $buttonId;
        return $this;
    }


    public function toArray()
    {
        return [
            'buttonId' => isset($this->buttonId) ? $this->buttonId : rand(1000, 9999),
            'buttonText' => [
                'displayText' => isset($this->name) ? $this->name : $this->text,
            ],
            'type' => 1,
        ];
    }

}