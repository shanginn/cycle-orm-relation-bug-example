<?php

namespace App\Infrastructure\CycleORM\Behavior\Smolid;

use Attribute;
use Cycle\ORM\Entity\Behavior\Schema\BaseModifier;
use Spiral\Attributes\NamedArgumentConstructor;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE), NamedArgumentConstructor]
class Smolid extends BaseModifier
{
    /**
     * @param non-empty-string      $field    Smolid property name
     * @param non-empty-string|null $column   Smolid column name
     * @param bool                  $nullable Indicates whether to generate a new Smol ID or not
     */
    public function __construct(
        protected string $field = 'id',
        protected ?string $column = null,
        protected bool $nullable = false
    ) {}

    protected function getListenerClass(): string
    {
        return Listener::class;
    }

    /**
     * @return array{field: non-empty-string, nullable: bool}
     */
    protected function getListenerArgs(): array
    {
        return [
            'field'    => $this->field,
            'nullable' => $this->nullable,
        ];
    }
}