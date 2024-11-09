<?php

namespace App\Services;

use Kreait\Firebase\Factory;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(config('firebase.credentials.file'));
        $this->messaging = $factory->createMessaging();
    }

    public function sendNotification($title, $body, $token)
    {
        $message = [
            'notification' => ['title' => $title, 'body' => $body],
            'token' => $token,
        ];

        return $this->messaging->send($message);
    }
}
