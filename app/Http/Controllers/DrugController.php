<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Drug;
use App\Models\DrugType;
use App\Lib\Logger;
use App\Models\Stock;
use App\Models\User;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\StoreDrugRequest;
use App\Http\Requests\UpdateDrugRequest;
use Illuminate\Support\Facades\Validator;

class DrugController extends Controller
{
    public function getDrugList()
    {
        $clinic = Clinic::getCurrentClinic();
        $drugs = $clinic->drugs;

        return view('drugs.drugs', ['drugs' => $drugs]);
    }

    public function getDrug($id)
    {
        $drug = Drug::find($id);
        $this->authorize('view', $drug);

        return view('drugs.drug', ['drug' => $drug]);
    }

    public function addDrug(Request $request)
    {
        $this->authorize('add', Drug::class);

        // Log incoming request data
        \Log::info('Incoming request data:', $request->all());

        DB::beginTransaction();
        try {
            $drug = $this->createDrug($request);

            // Check if quantity is filled and proceed to add stock
            if ($request->filled('quantity')) {
                $this->addStock($drug->id, $request); // Adjusted to use addStock
            }
        } catch (Exception $e) {
            Logger::error("Failed adding the drug: " . $e->getMessage(), $request->all());
            DB::rollback();
            return back()->withErrors(['general' => 'Drug already exists or Stock data is incorrect'])->withInput();
        }
        DB::commit();

        return back()->with('success', 'Drug added successfully!');
    }

    public function deleteDrug($id)
    {
        $drug = Drug::find($id);
        $this->authorize('delete', $drug);

        DB::beginTransaction();
        try {
            $drug->delete();
        } catch (Exception $e) {
            DB::rollback();
            return back()->with('error', 'The drug cannot be deleted!');
        }
        DB::commit();

        return back()->with('success', 'The drug was successfully deleted!');
    }

    public function editDrug($id, UpdateDrugRequest $request)
    {
        $drug = Drug::find($id);
        $this->authorize('edit', $drug);

        DB::beginTransaction();
        try {
            $this->updateDrug($request, $drug);
        } catch (Exception $e) {
            Logger::error("Exception when editing drug: " . $e->getMessage(), $request->all());
            DB::rollback();
            return back()->withErrors(['drugName' => 'Drug name already exists'])->withInput();
        }
        DB::commit();

        return back()->with('success', 'Drug updated successfully!');
    }

    private function createDrug(Request $request)
    {
        $quantityType = DrugType::find($request->quantityType);
        $drug = new Drug();
        $drug->name = $request->drugName;
        $drug->ingredient = $request->ingredient;
        $drug->manufacturer = $request->manufacturer;
        $drug->quantityType()->associate($quantityType);
        $drug->clinic()->associate(Clinic::getCurrentClinic());
        $drug->creator()->associate(User::getCurrentUser());
        $drug->save();

        return $drug;
    }

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

    private function updateDrug(UpdateDrugRequest $request, Drug $drug)
    {
        $drug->name = $request->drugName;
        $drug->ingredient = $request->ingredient;
        $drug->quantityType()->associate(DrugType::find($request->quantityType));
        $drug->manufacturer = $request->manufacturer;
        $drug->save(); // Save changes instead of using update()
    }
}
