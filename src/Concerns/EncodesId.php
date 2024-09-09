<?php

namespace Conquest\Table\Concerns;

use Closure;

trait EncodesId
{
    protected static ?Closure $encodeUsing = null;

    protected static ?Closure $decodeUsing = null;

    public static function encodeUsing(Closure $encodeUsing): void
    {
        static::$encodeUsing = $encodeUsing;
    }

    public static function decodeUsing(Closure $decodeUsing): void
    {
        static::$decodeUsing = $decodeUsing;
    }

    public static function getEncodedID(string $id): string
    {
        if (static::$encodeUsing) {
            return value(static::$encodeUsing, $id);
        }

        return encrypt($id);
    }

    public static function getDecodedId(string $id): string
    {
        if (static::$decodeUsing) {
            return value(static::$decodeUsing, $id);
        }

        return decrypt($id);
    }

    public function getId(): string
    {
        return static::class;
    }
}
