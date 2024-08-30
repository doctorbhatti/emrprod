<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Drug;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    /**
     * Adds stock to a given drug.
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addStock($id, Request $request)
    {
        // Ensure user has permission to add stock
        $this->authorize('add', Stock::class);

        $drug = Drug::findOrFail($id); // Use findOrFail for better error handling
        $this->authorize('addStocks', $drug);

        $validator = Validator::make($request->all(), [
            'quantity'         => 'required|numeric|min:1',
            'manufacturedDate' => 'required|date|before:' . date('Y-m-d') . '|after:1900-01-01',
            'receivedDate'     => 'required|date|before_or_equal:' . date('Y-m-d', strtotime('+1 day')) . '|after_or_equal:' . $request->manufacturedDate,
            'expiryDate'       => 'required|date|after:' . date('Y-m-d'),
            'remarks'          => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return back()->with('type', 'stock')->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $stock = new Stock();
            $stock->drug()->associate($drug);
            $stock->manufactured_date = $request->manufacturedDate;
            $stock->received_date = $request->receivedDate;
            $stock->expiry_date = $request->expiryDate;
            $stock->quantity = $request->quantity;
            $stock->remarks = $request->remarks;
            $stock->creator()->associate(User::getCurrentUser());
            $stock->save();

            // Update drug quantity
            $drug->quantity += $request->quantity;
            $drug->save(); // Use save() instead of update()

            DB::commit();
            return back()->with('success', 'Stock added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('type', 'stock')->withInput()->withErrors(['general' => 'Stock could not be added. Please try again.']);
        }
    }

    /**
     * Get the view with stocks that are running low.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStocksRunningLow()
    {
        $this->authorize('seeRunningLow', Stock::class);

        $clinic = Clinic::getCurrentClinic();
        $drugs = $clinic->drugs()->where('quantity', '<', 100)->get();

        return view('drugs.stocks.runningLow', ['drugs' => $drugs]);
    }
}
