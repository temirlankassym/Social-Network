<?php

namespace App\Http\Controllers;

use App\Interfaces\AccountManager;

class AccountManagerController extends Controller
{
    private $accountManager;

    public function __construct(AccountManager $accountManager)
    {
        $this->accountManager = $accountManager;
    }

    public function makePrivate()
    {
        return $this->accountManager->makePrivate();
    }

    public function makePublic()
    {
        return $this->accountManager->makePublic();
    }
}
