<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Get all provinces
     */
    public function getProvinces()
    {
        $provinces = Province::select('code', 'name', 'full_name')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $provinces
        ]);
    }

    /**
     * Get wards by province code
     */
    public function getWards($provinceCode)
    {
        $wards = Ward::where('province_code', $provinceCode)
            ->select('code', 'name', 'full_name')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $wards
        ]);
    }
}
