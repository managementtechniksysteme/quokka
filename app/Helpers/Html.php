<?php

namespace App\Helpers;

use Pandoc\Pandoc;

class Html
{
    public static function fromMarkdown(string $text)
    {
        return (new Pandoc())
            ->input($text)
            ->execute([
                '-V', 'lang=de',
                '--from', 'gfm',
                '--to', 'html',
            ]);
    }
}
