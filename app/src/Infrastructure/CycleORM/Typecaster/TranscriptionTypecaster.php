<?php

declare(strict_types=1);

namespace App\Infrastructure\CycleORM\Typecaster;

use App\Domain\Types\Transcription;

/**
 * @template-implements TypecasterInterface<array,Transcription>
 */
class TranscriptionTypecaster implements TypecasterInterface
{
    /**
     * Casts a string to a Transcription object.
     *
     * @param string $data the data to cast
     *
     * @return Transcription the casted data
     */
    public static function cast($data)
    {
        assert(is_string($data));

        $array = json_decode(
            json: $data,
            associative: true,
            flags: JSON_THROW_ON_ERROR
        );

        return Transcription::fromArray($array);
    }

    /**
     * Uncasts a Transcription object back to a string.
     *
     * @param Transcription $data the Transcription object to uncast
     *
     * @return string the uncasted data
     */
    public static function uncast($data)
    {
        assert($data instanceof Transcription);

        return json_encode(
            value: $data->toArray(),
            flags: JSON_THROW_ON_ERROR
        );
    }
}
