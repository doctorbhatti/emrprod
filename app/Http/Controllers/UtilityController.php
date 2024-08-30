<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Prescription;
use Auth;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UtilityController extends Controller
{
    /**
     * Search for a patient or drug based on a query.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $clinic = Clinic::getCurrentClinic();

        // Search for patients
        $patients = $clinic->patients()
            ->where(function ($q) use ($query) {
                $q->orWhere('first_name', 'LIKE', $query . '%')
                    ->orWhere('last_name', 'LIKE', $query . '%')
                    ->orWhere('nic', 'LIKE', $query . '%')
                    ->orWhere('id', $query);
            })
            ->take(10)
            ->get();

        // Search for drugs
        $drugs = $clinic->drugs()
            ->where('name', 'LIKE', $query . '%')
            ->take(10)
            ->get();

        return view('utils.search', [
            'patients' => $patients,
            'drugs' => $drugs,
            'query' => $query
        ]);
    }

    /**
     * Displays the dashboard with the required data.
     * Redirects to the home page if the user is not logged in.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDashboard()
    {
        if (Auth::guest()) {
            return view('website.home');
        }

        $clinic = Clinic::getCurrentClinic();
        $patientIds = $clinic->patients()->pluck('id')->toArray();

        // Efficiently fetch prescriptions in batches
        $prescriptions = Prescription::whereIn('patient_id', $patientIds)->get();

        $from = Carbon::now()->startOfDay();
        $to = Carbon::now()->endOfDay();

        $prescriptionCount = $prescriptions->where('issued', 1)->count();
        $payments = Payment::whereIn('prescription_id', $prescriptions->where('issued', 1)->pluck('id'))->sum('amount');
        $stats = $this->calcClinicStats($clinic);

        // Get payments made today
        $paymentsToday = Payment::query()
            ->select(DB::raw('SUM(amount) AS total_cost'))
            ->whereBetween('created_at', [$from, $to])
            ->groupBy(DB::raw('DAY(created_at)'))
            ->get();

        // Get current day name
        $mytime = Carbon::now()->format('l');
        $dt = Carbon::now();

        return view('dashboard', [
            'clinic' => $clinic,
            'prescriptionCount' => $prescriptionCount,
            'payments' => $payments,
            'stats' => $stats,
            'paymentsToday' => $paymentsToday,
            'mytime' => $mytime,
            'dt'=> $dt
        ]);
    }

    /**
     * Calculates statistics for a given clinic.
     *
     * @param $clinic
     * @return array
     */
    private function calcClinicStats($clinic)
    {
        $stats = [
            'visits' => [
                'm' => [],
                'c' => []
            ]
        ];

        $date = Carbon::now()->subMonths(5)->startOfMonth()->format('Y-m-d');
        $patientIds = $clinic->patients()->pluck('id')->toArray();

        if (count($patientIds) > 0) {
            $query = "SELECT MONTH(created_at) AS m, COUNT(*) AS c
                      FROM prescriptions
                      WHERE patient_id IN (" . implode(",", $patientIds) . ")
                      AND created_at > :date
                      GROUP BY MONTH(created_at)";

            $pdo = DB::connection()->getPdo();
            $statement = $pdo->prepare($query);
            $statement->bindParam(':date', $date, \PDO::PARAM_STR);
            $statement->execute();
            $visits = $statement->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($visits as $result) {
                $dateObj = \DateTime::createFromFormat('!m', $result['m']);
                $stats['visits']['m'][] = $dateObj->format('F');
                $stats['visits']['c'][] = $result['c'];
            }
        }

        return $stats;
    }
}
