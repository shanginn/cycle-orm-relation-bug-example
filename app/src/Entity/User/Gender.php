<?php

declare(strict_types=1);

namespace App\Entity\User;

enum Gender: string
{
    public function text(): string
    {
        return match ($this) {
            self::FEMALE => l('gender.female'),
            self::MALE   => l('gender.male'),
        };
    }

    case MALE   = 'male';
    case FEMALE = 'female';
}