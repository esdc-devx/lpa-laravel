<?php

namespace App\Support;

use Faker\Provider\Base;

class CustomFaker extends Base
{
    public function costCenter()
    {
        $letter = chr(64 + rand(0, 26));
        $digits = 5;
        $number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
        return "{$letter}{$number}";
    }

    public function roundNumberBetween($min, $max)
    {
        return round($this->numberBetween($min, $max), -1);
    }

    public function sentenceNoDot()
    {
        return substr($this->generator->sentence(), 0, -1);
    }

    public function randomList($list)
    {
        return $this->numberBetween(1, $list->count());
    }

    public function randomMultipleList($list)
    {
        return array_random($list->pluck('id')->toArray(), rand(0, $list->count()));
    }
}
