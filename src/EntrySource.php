<?php
declare(strict_types=1);
namespace iggyvolz\covid;

use SleekDB;
use DateTime;
use Iterator;

abstract class EntrySource
{
    public abstract static function getAll():Iterator;
    private static array $sources = [
        BingSource::class
    ];
    public static function getAllFromAllSources():Iterator
    {
        foreach(self::$sources as $source) {
            yield from $source::getAll();
        }
    }
}