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

class DrugController extends Controller {

    public function getDrugList() {
        $clinic = Clinic::getCurrentClinic();
        $drugs  = $clinic->drugs;

        return view('drugs.drugs', ['drugs' => $drugs]);
    }

    public function getDrug($id) {
        $drug = Drug::find($id);
        $this->authorize('view', $drug);

        return view('drugs.drug', ['drug' => $drug]);
    }

    public function addDrug(StoreDrugRequest $request) {
        $this->authorize('add', Drug::class);

        DB::beginTransaction();
        try {
            $drug = $this->createDrug($request);

            if ($request->filled('quantity')) {
                $this->addStock($request, $drug);
            }
        } catch (Exception $e) {
            Logger::error("Failed adding the drug: " . $e->getMessage(), $request->all());
            DB::rollback();
            return back()->withErrors(['general' => 'Drug already exists or Stock data is incorrect'])->withInput();
        }
        DB::commit();

        return back()->with('success', 'Drug added successfully!');
    }

    public function deleteDrug($id) {
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

    public function editDrug($id, UpdateDrugRequest $request) {
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

    private function createDrug(StoreDrugRequest $request) {
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

    private function addStock(StoreDrugRequest $request, Drug $drug) {
        $stock = new Stock();
        $stock->drug()->associate($drug);
        $stock->manufactured_date = $request->manufacturedDate;
        $stock->received_date = $request->receivedDate;
        $stock->expiry_date = $request->expiryDate;
        $stock->quantity = $request->quantity;
        $stock->remarks = $request->remarks;
        $stock->creator()->associate(User::getCurrentUser());
        $stock->save();

        $drug->quantity += $request->quantity;
        $drug->update();
    }

    private function updateDrug(UpdateDrugRequest $request, Drug $drug) {
        $drug->name = $request->drugName;
        $drug->ingredient = $request->ingredient;
        $drug->quantityType()->associate(DrugType::find($request->quantityType));
        $drug->manufacturer = $request->manufacturer;
        $drug->update();
    }
}
