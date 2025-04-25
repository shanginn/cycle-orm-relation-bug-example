<?php

declare(strict_types=1);

namespace App\Infrastructure\CycleORM\Typecaster;

use App\DataObject\Decimal;

class DecimalTypecaster implements TypecasterInterface
{
    /**
     * Casts a value to a Decimal object.
     *
     * @param mixed $data the value to cast
     *
     * @return Decimal|null the casted Decimal object or null if value is not set
     */
    public static function cast($data)
    {
        if ($data === null) {
            return null;
        }

        return Decimal::fromScalar($data);
    }

    /**
     * Uncasts a Decimal object back to its scalar representation.
     *
     * @param Decimal|null $data the Decimal object to uncast
     *
     * @return mixed the uncasted scalar value
     */
    public static function uncast($data)
    {
        if (!$data instanceof Decimal) {
            return $data;
        }

        return $data->format(
            decimals: 8,
            decPoint: '.',
            thousandsSep: ''
        );
    }
}
