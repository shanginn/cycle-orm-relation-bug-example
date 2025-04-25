<?php

namespace App\Infrastructure\CycleORM;

use App\Infrastructure\CycleORM\Typecaster\DecimalTypecaster;
use App\Infrastructure\CycleORM\Typecaster\JsonTypecaster;
use App\Infrastructure\CycleORM\Typecaster\TranscriptionTypecaster;
use App\Infrastructure\CycleORM\Typecaster\TypecasterInterface;
use Cycle\ORM\Parser\CastableInterface;
use Cycle\ORM\Parser\UncastableInterface;

class ExtendedTypecast implements CastableInterface, UncastableInterface
{
    private array $rules = [];

    /**
     * @var array<TypecasterInterface>
     */
    private array $casters = [
        JsonTypecaster::class,
        TranscriptionTypecaster::class,
        DecimalTypecaster::class,
    ];

    public function setRules(array $rules): array
    {
        foreach ($rules as $key => $rule) {
            if (\in_array($rule, $this->casters, true)) {
                unset($rules[$key]);
                $this->rules[$key] = $rule;
            }
        }

        return $rules;
    }

    public function cast(array $data): array
    {
        foreach ($this->rules as $column => $rule) {
            if (!isset($data[$column])) {
                continue;
            }

            $data[$column] = $rule::cast($data[$column]);
        }

        return $data;
    }

    public function uncast(array $data): array
    {
        foreach ($this->rules as $column => $rule) {
            if (!isset($data[$column])) {
                continue;
            }

            $data[$column] = $rule::uncast($data[$column]);
        }

        return $data;
    }
}