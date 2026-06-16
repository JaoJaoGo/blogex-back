<?php

namespace App\Support;

class AuthorMap
{
    public const MAP = [
        'joao' => 1,
        'ellen' => 2,
    ];

    public static function all(): array
    {
        return self::MAP;
    }

    public static function userIdFromAuthor(string $author): ?int
    {
        return self::MAP[$author] ?? null;
    }

    public static function authorFromUserId(int $userId): ?string
    {
        return array_flip(self::MAP)[$userId] ?? null;
    }
}