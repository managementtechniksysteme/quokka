<?php

namespace App\SpotlightCommands;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class LogoutCommand extends SpotlightCommand
{
    protected string $name = 'Abmelden';

    protected string $description = '';

    public function __construct()
    {
        $this->description = 'Dich von ' . config('app.name') . ' abmelden';
    }

    public function execute(Spotlight $spotlight, StatefulGuard $guard): void
    {
        $guard->logout();
        $spotlight->redirect('/');
    }

    public function shouldBeShown(Request $request): bool
    {
        return !$request->session()->has('impersonatorId');
    }
}
