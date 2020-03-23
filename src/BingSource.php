<?php
declare(strict_types=1);
namespace iggyvolz\covid;

use DateTime;
use Iterator;
use EmptyIterator;

class BingSource
{
    // public function __construct(string $source, string $locality, string $type, DateTime $date, int $num)
    public static function getAll():Iterator
    {
        $conts = json_decode(file_get_contents("https://bing.com/covid/graphdata"), true, 512, JSON_THROW_ON_ERROR);
        $expected=3*count($conts)*count(array_values($conts)[0]);
        foreach($conts as $area => $entries) {
            foreach($entries as $entry) {
                yield new Entry("bing", self::transformLocality($area), "cases", DateTime::createFromFormat("Y-m-d", $entry["date"]), $entry["confirmed"]);
                yield new Entry("bing", self::transformLocality($area), "deaths", DateTime::createFromFormat("Y-m-d", $entry["date"]), $entry["fatal"]);
                yield new Entry("bing", self::transformLocality($area), "recovered", DateTime::createFromFormat("Y-m-d", $entry["date"]), $entry["recovered"]);
            }
        }
    }
    private static function transformLocality(string $id):string
    {
        // TODO normalize
        return $id;
    }
}