<?php

namespace App\Support\Mailer;
use Nette\Mail\Message;

class Mail
{
    protected $view;

    protected $mailer;

    public function __construct($view, $mailer)
    {
        $this->view = $view;
        $this->mailer = $mailer;
    }

    public static function send($template, $data, $callback)
    {
        $message = new Message($this->mailer);

        $message->body($this->view->fetch($template, $data));

        call_user_func($callback, $message);

        $this->send_mailer->send($message);
    }
}