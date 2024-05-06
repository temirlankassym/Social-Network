<?php

namespace App\Interfaces;

interface PublisherInterface{
    public function addSubscriber(string $username, string $subscriber);
    public function removeSubscriber(string $username, string $subscriber);
}
