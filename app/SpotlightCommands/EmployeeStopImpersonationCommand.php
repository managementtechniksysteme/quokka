<?php

namespace App\SpotlightCommands;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class EmployeeStopImpersonationCommand extends SpotlightCommand
{
    protected string $name = 'Als Benutzer abmelden';

    protected string $description = '';

    public function __construct()
    {
        $this->description = 'Zurück zum eigenen ' . config('app.name') . ' Benutzer';
    }

    public function execute(Request $request, Spotlight $spotlight): void
    {
        $spotlight->redirect(route('employees.impersonate', $request->user()));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('employees.impersonate') && $request->session()->has('impersonatorId');
    }
}
