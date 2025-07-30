<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Field;
use App\Models\Village;
use App\Services\RegionService;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        $field_query = Field::latest();

        $districts = RegionService::getDistricts();
        $villages = RegionService::getVillagesByDistrict($request->input('district'));

        if ($request->has('search')) {
            $search = $request->input('search');
            $searchTerms = explode(' ', trim($search));

            $field_query->where(function ($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->where('name', 'like', '%' . $term . '%');
                }
            });

            // Optional: Add ordering by relevance
            $field_query->selectRaw('*,
                CASE
                    WHEN name LIKE ? THEN 1
                    WHEN name LIKE ? THEN 2
                    ELSE 3
                END as relevance_score',
                [$search, $search . '%']
            )->orderBy('relevance_score');
        }

        if ($request->has('district')) {
            $districtId = $request->input('district');
            if ($districtId) {
                $field_query->whereHas('village', function ($query) use ($districtId) {
                    $query->where('district_id', $districtId);
                });
            }
        }

        if ($request->has('village')) {
            $villageId = $request->input('village');
            if ($villageId) {
                $field_query->where('village_id', $villageId);
            }
        }

        $fields = $field_query->get();

        return view('pages.landing.index', compact('fields', 'districts', 'villages'));
    }
}
