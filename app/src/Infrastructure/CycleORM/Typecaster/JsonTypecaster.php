<?php

declare(strict_types=1);

namespace App\Infrastructure\CycleORM\Typecaster;

/**
 * @template-implements TypecasterInterface<array,string>
 */
class JsonTypecaster implements TypecasterInterface
{
    /**
     * Casts a string to an array.
     *
     * @param string $data the data to cast
     *
     * @return array the casted data
     */
    public static function cast($data)
    {
        assert(is_string($data));

        return json_decode(
            json: $data,
            associative: true,
            flags: JSON_THROW_ON_ERROR
        );
    }

    /**
     * Uncasts an array back to a string.
     *
     * @param array $data the array to uncast
     *
     * @return string the uncasted data
     */
    public static function uncast($data)
    {
        assert(is_array($data));

        return json_encode(
            value: $data,
            flags: JSON_THROW_ON_ERROR
        );
    }
}