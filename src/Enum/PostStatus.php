<?php

namespace Pieldefoca\Lux\Enum;

enum PostStatus: string
{
    case Draft = 'draft';
    case Private = 'private';
    case Published = 'published';
    case Archived = 'archived';

    public function forHumans(): string
    {
        return match($this) {
            self::Draft => 'Borrador',
            self::Private => 'Privado',
            self::Published => 'Publicado',
            self::Archived => 'Archived',
        };
    }

    public static function options(): array
    {
        $options = [];

        foreach(self::cases() as $case) {
            $options[$case->value] = $case->forHumans();
        }

        return $options;
    }
}
