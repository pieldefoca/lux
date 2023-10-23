<?php

namespace Pieldefoca\Lux\Enum;

enum SliderPosition: string
{
    case Home = 'home';

    public function forHumans(): string
    {
        return match($this) {
            self::Home => 'Página de inicio',
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
