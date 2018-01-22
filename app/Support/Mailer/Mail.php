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
        $message = new Message(self::$mailer);

        $message->body(self::$view->fetch($template, $data));

        call_user_func($callback, $message);

        self::$send_mailer->send($message);
    }
}