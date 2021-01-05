<?php

namespace App\Helpers;

use App\Models\User;

class Mentions
{
    public static function extractMentionedUsers(string $text)
    {
        preg_match_all('/(?:^|[^a-zA-Z0-9_＠!@#$%&*])(?:(?:@|＠)(?!\/))([a-zA-Z0-9\/_]{1,15})(?:\b(?!@|＠)|$)/', $text, $matches);

        $usernames = $matches[1];

        $mentionedUsers = User::whereIn('username', $usernames)->get();

        return $mentionedUsers;
    }
}
