<?php

namespace App\Application\Facade;

use Crell\Serde\SerdeCommon;

final class Serde
{
    private static \Crell\Serde\Serde $serde;

    private static function serde(): \Crell\Serde\Serde
    {
        if (!isset(self::$serde)) {
            self::$serde = new SerdeCommon();
        }

        return self::$serde;
    }

    /**
     * Deserialize a value to a PHP object.
     *
     * @template T of object
     *
     * @param mixed              $serialized
     *                                       The serialized form to deserialize
     * @param string             $from
     *                                       The format the serialized value is in
     * @param class-string<T>    $to
     *                                       The class name of the class to which to deserialize
     * @param array<string|null> $scopes
     *                                       An array of scopes that should be deserialized.  Fields not in this scope will be ignored.
     *
     * @return T
     *           The deserialized object
     */
    public static function deserialize(mixed $serialized, string $from, string $to, array $scopes = []): object
    {
        return self::serde()->deserialize(
            serialized: $serialized,
            from: $from,
            to: $to,
            scopes: $scopes
        );
    }

    /**
     * Serialize an object to a given format.
     *
     * @param object             $object
     *                                   The object to serialize
     * @param string             $format
     *                                   Identifier of the format to which to serialize
     * @param mixed|null         $init
     *                                   An initial value to serialize into. Its expected type and meaning will vary with the format.
     * @param array<string|null> $scopes
     *                                   An array of scopes that should be serialized.  Fields not in this scope will be ignored.
     *
     * @return mixed
     *               The serialized value
     */
    public static function serialize(object $object, string $format, mixed $init = null, array $scopes = []): mixed
    {
        return self::serde()->serialize(
            object: $object,
            format: $format,
            init: $init,
            scopes: $scopes
        );
    }
}