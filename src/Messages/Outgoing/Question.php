<?php

namespace BotMan\Drivers\WhatsApp\Messages\Outgoing;

class Question extends \BotMan\BotMan\Messages\Outgoing\Question
{

    protected $footerText;

    public function footerText($footerText)
    {
        $this->footerText = $footerText;

        return $this;
    }


    public function toWebDriver()
    {
        return [
            'type' => (count($this->actions) > 0) ? 'button' : 'text',
            'button' => [
                "headerType" => 1,
                'contentText' => $this->text,
                'footerText' => $this->footerText,
                'buttons' => $this->actions
            ]
        ];
    }
}