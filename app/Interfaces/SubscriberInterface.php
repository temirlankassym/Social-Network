<?php

namespace App\Interfaces;

interface SubscriberInterface{
    public function updateState(string $username, string $subscriber);
}
