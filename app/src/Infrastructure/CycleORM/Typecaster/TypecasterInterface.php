<?php

namespace App\Infrastructure\CycleORM\Typecaster;

/**
 * Interface TypecasterInterface.
 *
 * @template O
 * @template T
 *
 * @template-implements TypecasterInterface<O,T>
 */
interface TypecasterInterface
{
    /**
     * Casts data to the specified type.
     *
     * @param T $data the data to cast
     *
     * @return O the casted data
     */
    public static function cast($data);

    /**
     * Uncasts the object back to its original type.
     *
     * @param O $data the object to uncast
     *
     * @return T the uncasted data
     */
    public static function uncast($data);
}

