<?php

namespace App\Helpers;

use App\Models\Person;
use App\Models\User;
use App\Support\GlobalSearch\CrossReferenceResolver;

class Mentions
{
    public static function extractMentionedUsers(string $text)
    {
        preg_match_all('/(?:^|[^a-zA-Z0-9_＠!@#$%&*])(?:(?:@|＠)(?!\/))([a-zA-Z0-9\/_]{1,15})(?:\b(?!@|＠)|$)/', $text, $matches);

        $usernames = $matches[1];

        $mentionedUsers = User::whereIn('username', $usernames)->get();

        return $mentionedUsers;
    }

    // Resolves "@username" / "#type-id" tokens (same shapes extractMentionedUsers
    // and App\Support\GlobalSearch\CrossReferenceResolver already work with) into
    // the same chip markup MarkdownEditor.vue renders live in the editor. Called
    // from Html::fromMarkdown before handing the text to Pandoc, which passes
    // inline HTML straight through untouched in gfm mode — so this is the one
    // place that needs to know how to turn a token into a chip, for both the
    // editor (JS side, decorateMentions/decorateCrossReferences) and every show
    // page (this, server side).
    public static function renderInline(string $text): string
    {
        $text = preg_replace_callback(
            '/(^|[^a-zA-Z0-9_@])@(?!\/)([a-zA-Z0-9_\/]{1,15})(?=[^a-zA-Z0-9_\/]|$)/',
            function ($matches) {
                $person = Person::whereHas('employee.user', fn ($query) => $query->where('username', $matches[2]))
                    ->with('employee.user')
                    ->first();

                if (!$person) {
                    return $matches[0];
                }

                return $matches[1] . self::renderMentionChip($person->name, $person->avatar);
            },
            $text
        );

        return preg_replace_callback(
            '/(^|[^a-zA-Z0-9_#])#([a-z]+(?:-[a-z]+)*-\d+)(?=[^a-zA-Z0-9\-]|$)/',
            function ($matches) {
                $result = CrossReferenceResolver::resolve($matches[2]);

                if (!$result) {
                    return $matches[0];
                }

                return $matches[1] . self::renderCrossReferenceChip($result->getModel(), $result->getType(), $result->getName(), $result->getRoute());
            },
            $text
        );
    }

    private static function renderMentionChip(string $name, array $avatar): string
    {
        $style = $avatar['hex']
            ? sprintf('background: color-mix(in srgb, %s 20%%, transparent); color: %s;', $avatar['hex'], $avatar['hex'])
            : '';
        $colourClass = $avatar['hex'] ? '' : ' q-avatar--' . e($avatar['colour']);

        return sprintf(
            '<span class="q-mention-chip"><span class="q-avatar q-avatar--round q-avatar--sm%s" style="%s">%s</span><span>%s</span></span>',
            $colourClass,
            e($style),
            e($avatar['initials']),
            e($name)
        );
    }

    // Reuses partials/model_icon.blade.php — the same per-model icon mapping
    // the global search results list already renders — rather than
    // duplicating it here, in the CrossReferenceController JSON payload, and
    // in the editor's inline chip.
    private static function renderCrossReferenceChip(string $model, string $type, string $name, string $route): string
    {
        return sprintf(
            '<a class="q-crossref-chip" href="%s" title="%s">%s<span>%s</span></a>',
            e($route),
            e($type),
            view('partials.model_icon', ['model' => $model])->render(),
            e($name)
        );
    }
}
