<?php

use iggyvolz\covid\Entry;
require_once __DIR__ . "/vendor/autoload.php";
var_dump(iterator_to_array(Entry::readAll()));