<?php

namespace App\Http\Controllers;

use App\Interfaces\FeedInterface;

class FeedController extends Controller
{
    private $feed;

    public function __construct(FeedInterface $feed)
    {
        $this->feed = $feed;
    }

    public function getFeed()
    {
        return $this->feed->getFeed();
    }
}
