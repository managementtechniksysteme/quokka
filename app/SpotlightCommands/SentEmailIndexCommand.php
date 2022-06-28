<?php

namespace App\SpotlightCommands;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class SentEmailIndexCommand extends SpotlightCommand
{
    protected string $name = 'Gesendete Emails anzeigen';

    protected string $description = 'Alle gesendeten Emails anzeigen';

    protected array $synonyms = [
        'log',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('sent-emails.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('tools-viewsentemails');
    }
}
