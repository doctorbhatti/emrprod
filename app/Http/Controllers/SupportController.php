<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class SupportController extends Controller
{
    /**
     * Get the timezones of a country by its country code.
     *
     * @param string $countryCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTimezones($countryCode)
    {
        try {
            $timezones = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $countryCode);
            return response()->json($timezones);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Unable to fetch timezones'], 500);
        }
    }

    /**
     * Get the matching drugs based on a keyword.
     *
     * @param string $text
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDrugPredictions($text)
    {
        try {
            $drugs = DB::table('drug_pool')
                ->where('trade_name', 'LIKE', $text . '%')
                ->select('trade_name')
                ->distinct()
                ->take(10)
                ->get();

            return response()->json(['status' => 'success', 'drugs' => $drugs]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Unable to fetch drug predictions'], 500);
        }
    }

    /**
     * Get the matching ingredients based on a keyword.
     *
     * @param string $text
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIngredientPredictions($text)
    {
        try {
            $ingredients = DB::table('drug_pool')
                ->where('ingredient', 'LIKE', $text . '%')
                ->select('ingredient')
                ->distinct()
                ->take(30)
                ->get();

            return response()->json(['status' => 'success', 'ingredients' => $ingredients]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Unable to fetch ingredient predictions'], 500);
        }
    }

    /**
     * Get a list of manufacturers based on a keyword.
     *
     * @param string $text
     * @return \Illuminate\Http\JsonResponse
     */
    public function getManufacturerPredictions($text)
    {
        try {
            $manufacturers = DB::table('drug_pool')
                ->where('manufacturer', 'LIKE', $text . '%')
                ->select('manufacturer')
                ->distinct()
                ->take(10)
                ->get();

            return response()->json(['status' => 'success', 'manufacturers' => $manufacturers]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Unable to fetch manufacturer predictions'], 500);
        }
    }

    /**
     * Get the disease name predictions based on a partially entered text.
     *
     * @param string $text
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDiseasePredictions($text)
    {
        try {
            $diseases = DB::table('disease_pool')
                ->where('disease', 'LIKE', $text . '%')
                ->select('disease')
                ->take(10)
                ->get();

            return response()->json(['status' => 'success', 'diseases' => $diseases]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Unable to fetch disease predictions'], 500);
        }
    }
}
