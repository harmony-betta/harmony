<?php

namespace App\Support\Mailer;

class Message
{
    protected $mailer;

    protected $from;

    public function __construct($mailer)
    {
        $this->mailer = $mailer;
        $this->from->setFrom(env('EMAIL_USERNAME'));
    }

    public function to($address)
    {
        $this->mailer->addTo($address);
    }

    public function subject($subject)
    {
        $this->mailer->setSubject($subject);
    }

    public function body($body)
    {
        $this->mailer->setHTMLBody($body);
    }
}