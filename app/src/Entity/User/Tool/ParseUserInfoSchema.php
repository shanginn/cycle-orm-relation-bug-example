<?php

declare(strict_types=1);

namespace App\Entity\User\Tool;

use App\Entity\User\Gender;
use App\Openai\ChatCompletion\Tool\AbstractTool;
use App\Openai\ChatCompletion\Tool\OpenaiToolSchema;
use Spiral\JsonSchemaGenerator\Attribute\Field;

#[OpenaiToolSchema(
    name: 'parse_user_info',
    description: 'Cleans, normalizes and parses user info in original language'
)]
class ParseUserInfoSchema extends AbstractTool
{
    public function __construct(
        #[Field(
            title: 'Full name',
            description: 'The full name of the user'
        )]
        public ?string $fullName = null,
        #[Field(
            title: 'Age',
            description: 'The age of the user'
        )]
        public ?int $age = null,
        #[Field(
            title: 'Birth date day',
            description: 'The day of the birth date of the user'
        )]
        public ?int $birthDateDay = null,
        #[Field(
            title: 'Birth date month',
            description: 'The month of the birth date of the user'
        )]
        public ?int $birthDateMonth = null,
        #[Field(
            title: 'Birth date year',
            description: 'The year of the birth date of the user'
        )]
        public ?int $birthDateYear = null,
        #[Field(
            title: 'Birth place',
            description: 'The birth place of the user'
        )]
        public ?string $birthPlace = null,
        #[Field(
            title: 'Birth time',
            description: 'The birth time of the user in H:M 24h format'
        )]
        public ?string $birthTime = null,
        #[Field(
            title: 'Gender',
            description: 'gender, try to guess it based on other info',
        )]
        public ?Gender $gender = null,
        #[Field(
            title: 'Title',
            description: 'title or "обращение" (for example "госпожа", "мистер")',
        )]
        public ?string $title = null,
    ) {}
}