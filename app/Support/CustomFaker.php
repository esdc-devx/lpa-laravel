<?php

namespace App\Support;

use App\Models\OrganizationalUnit;
use App\Models\User\User;
use Faker\Provider\Base;

class CustomFaker extends Base
{
    static $learningProductOwnerIds;
    static $userIds;

    public function randomUserId()
    {
        if (! static::$userIds) {
            static::$userIds = User::pluck('id');
        }
        return static::$userIds->random();
    }

    public function randomLearningProductOwnerId()
    {
        if (! static::$learningProductOwnerIds) {
            static::$learningProductOwnerIds = OrganizationalUnit::learningProductOwners()->pluck('id');
        }
        return static::$learningProductOwnerIds->random();
    }

    public function costCentre()
    {
        $letter = chr(64 + rand(1, 26));
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
        return array_random($list->pluck('id')->toArray());
    }

    public function randomMultipleList($list, $min = 1, $max = null)
    {
        return array_random($list->pluck('id')->toArray(), rand($min, $max ?? $list->count()));
    }

    public function internalOrder()
    {
        $letter = chr(64 + rand(1, 26));
        $digits = 6;
        $number = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
        return "{$letter}{$number}";
    }
}
