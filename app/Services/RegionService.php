<?php

namespace App\Services;

use App\Models\District;
use App\Models\Village;

class RegionService
{
    public static function getDistricts()
    {
        return District::with('villages')->where('regency_id', PEKANBARU_REGENCY_ID)->get();
    }

    public static function getVillagesByDistrict($districtId)
    {
        return District::find($districtId)->villages ?? collect();
    }

    public static function getVillages()
    {
        return Village::whereHas('district', function ($query) {
            $query->where('regency_id', PEKANBARU_REGENCY_ID);
        })->get();
    }
}
