<?php

declare(strict_types=1);

namespace App\Exception;

use App\Entity\User;

class UserNotFoundException extends EntityNotFoundException
{
    public function __construct(
        public array $criteria = [],
    ) {
        parent::__construct(User::class, $this->criteria);
    }
}
