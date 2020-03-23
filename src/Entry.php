<?php
declare(strict_types=1);
namespace iggyvolz\covid;

use SleekDB\SleekDB;
use DateTime;
use Iterator;

class Entry
{
    public string $source;
    public string $locality;
    // One of: cases, hospitalizations, deaths, recovered
    public string $type;
    public DateTime $date;
    public int $num;
    private static ?SleekDB $db=null;
    private static function getDB():SleekDB
    {
        if(is_null(self::$db)) {
            self::$db = SleekDB::store("entries", __DIR__."/../data", ["auto_cache" => false]);
        }
        return self::$db;
    }
    public function __construct(string $source, string $locality, string $type, DateTime $date, int $num)
    {
        $this->source = $source;
        $this->locality = $locality;
        $this->type = $type;
        $this->date = $date;
        $this->num = $num;
    }
    public function insert():void
    {
        self::getDB()->insert([
            "source" => $this->source,
            "locality" => $this->locality,
            "type" => $this->type,
            "date" => $this->date->format("U"),
            "num" => $this->num
        ]);
    }
    public static function readAll():Iterator
    {
        foreach(self::getDB()->fetch() as $row) {
            yield new self($row["source"], $row["locality"], $row["type"], DateTime::createFromFormat("U", $row["date"]), $row["num"]);
        }
    }
    public static function updateAll():void
    {
        self::getDB()->delete();
        $n=0;
        $num=0;
        foreach(EntrySource::getAllFromAllSources($num) as $entry) {
            $entry->insert();
        }
    }
}