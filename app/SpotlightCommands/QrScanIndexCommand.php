<?php

namespace App\SpotlightCommands;

use Illuminate\Http\Request;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class QrScanIndexCommand extends SpotlightCommand
{
    protected string $name = 'QR-Code scannen';

    protected string $description = 'Einen QR-Code auf einem Ausdruck scannen';

    protected array $synonyms = [
        'Kamera',
    ];

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect(route('qr-scan.index'));
    }

    public function shouldBeShown(Request $request): bool
    {
        return $request->user()->can('tools-scanqr');
    }
}
