<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\UtilityController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\DrugController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\DrugTypeController;
use App\Http\Controllers\DosageController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\APIController;
use App\Http\Controllers\DrugAPIController;
use App\Http\Controllers\SupportController;

Route::middleware('web')->group(function () {
    Auth::routes();

    Route::prefix('web')->group(function () {
        Route::get('aboutUs', [WebsiteController::class, 'getAboutUsPage']);
        Route::get('features', [WebsiteController::class, 'getFeaturesPage']);
        Route::get('privacyPolicy', [WebsiteController::class, 'getPrivacyPolicyPage']);
        Route::get('contactUs', [WebsiteController::class, 'getContactUs']);
        Route::post('contactUs', [WebsiteController::class, 'postContactUs'])->name('contactUs');
    });

    Route::get('registerClinic', [ClinicController::class, 'showRegistrationForm'])->name('registerClinic');
    Route::post('registerClinic', [ClinicController::class, 'postRegister'])->name('registerClinic');

    Route::get('/', [UtilityController::class, 'getDashboard'])->name('root');
    // Route to show the hold page when a clinic is held
    Route::get('clinic-hold', [AdminController::class, 'showHoldPage'])->name('holdPage');

    Route::prefix('Admin')->group(function () {
        // Admin login routes
        Route::get('login', [AdminAuthController::class, 'getLogin'])->name('admin.login');
        Route::post('login', [AdminAuthController::class, 'postLogin']);

        // Grouped routes for authenticated Admin users
        Route::middleware('auth:admin')->group(function () {
            Route::get('admin', [AdminController::class, 'index'])->name('admin.dashboard');
            Route::get('acceptClinic/{id}', [AdminController::class, 'acceptClinic'])->name('acceptClinic');
            Route::get('deleteClinic/{id}', [AdminController::class, 'deleteClinic'])->name('deleteClinic');
            Route::get('holdClinic/{id}', [AdminController::class, 'holdClinic'])->name('holdClinic'); // New route to hold a clinic
            Route::get('unholdClinic/{id}', [AdminController::class, 'unholdClinic'])->name('unholdClinic'); // New route to unhold a clinic    
            Route::get('logout', [AdminAuthController::class, 'logout'])->name('adminLogout');
        });
    });

    Route::middleware('auth')->group(function () {
        Route::get('search', [UtilityController::class, 'search'])->name('search');
        Route::get('issueMedicine', [PrescriptionController::class, 'viewIssueMedicine'])->name('issueMedicine');

        Route::prefix('settings')->group(function () {
            Route::get('/', [SettingsController::class, 'viewSettings'])->name('settings');
            Route::post('changePassword', [SettingsController::class, 'changePassword'])->name('changePassword');
            Route::post('createAccount', [SettingsController::class, 'createAccount'])->name('createAccount');
            Route::get('deleteAccount/{id}', [SettingsController::class, 'deleteAccount'])->name('deleteAccount');
            Route::get('changePassword', [SettingsController::class, 'viewSettings']);
            Route::get('createAccount', [SettingsController::class, 'viewSettings']);
        });

        Route::prefix('queue')->group(function () {
            Route::get('/', [QueueController::class, 'viewQueue'])->name('queue');
            Route::get('addToQueue/{patientId}', [QueueController::class, 'addToQueue'])->name('addToQueue');
            Route::get('create', [QueueController::class, 'createQueue'])->name('createQueue');
            Route::get('close', [QueueController::class, 'closeQueue'])->name('closeQueue');
        });

        Route::prefix('patients')->group(function () {
            Route::get('/', [PatientController::class, 'getPatientList'])->name('patients');
            Route::get('/list', [PatientController::class, 'listPatients'])->name('listPatients');
            Route::post('addPatient', [PatientController::class, 'addPatient'])->name('addPatient');
            Route::get('patient/{id}', [PatientController::class, 'getPatient'])->name('patient');
            Route::any('deletePatient/{id}', [PatientController::class, 'deletePatient'])->name('deletePatient');
            Route::post('editPatient/{id}', [PatientController::class, 'editPatient'])->name('editPatient');
            Route::get('patient/{id}/printID', [PatientController::class, 'getPrintPreview'])->name('IDPreview');
            Route::get('patient/{id}/printPrescription/{prescriptionId}', [PrescriptionController::class, 'prescriptionPrintPreview'])->name('printPrescription');
            Route::get('payments', [PrescriptionController::class, 'getPayments'])->name('payments');
        });

        Route::prefix('drugs')->group(function () {
            Route::get('/', [DrugController::class, 'getDrugList'])->name('drugs');
            Route::get('drug/{id}', [DrugController::class, 'getDrug'])->name('drug');
            Route::post('addDrug', [DrugController::class, 'addDrug'])->name('addDrug');
            Route::post('editDrug/{id}', [DrugController::class, 'editDrug'])->name('editDrug');
            Route::post('deleteDrug/{id}', [DrugController::class, 'deleteDrug'])->name('deleteDrug');

            Route::post('addStock/{drugId}', [StockController::class, 'addStock'])->name('addStock');
            Route::get('stocks/runningLow', [StockController::class, 'getStocksRunningLow'])->name('stocksRunningLow');

            Route::get('drugTypes', [DrugTypeController::class, 'getDrugTypeList'])->name('drugTypes');
            Route::post('addDrugType', [DrugTypeController::class, 'addDrugType'])->name('addDrugType');
            Route::get('deleteDrugType/{id}', [DrugTypeController::class, 'deleteDrugType'])->name('deleteDrugType');

            Route::get('dosages', [DosageController::class, 'getDosageList'])->name('dosages');
            Route::post('addDosage', [DosageController::class, 'addDosage'])->name('addDosage');
            Route::get('deleteDosage/{id}', [DosageController::class, 'deleteDosage'])->name('deleteDosage');
            Route::post('edit-dosage/{id}', [DosageController::class, 'editDosage'])->name('editDosage');
            Route::post('edit-frequency/{id}', [DosageController::class, 'editFrequency'])->name('editFrequency');
            Route::post('edit-period/{id}', [DosageController::class, 'editPeriod'])->name('editPeriod');

            Route::post('addFrequency', [DosageController::class, 'addFrequency'])->name('addFrequency');
            Route::get('deleteFrequency/{id}', [DosageController::class, 'deleteFrequency'])->name('deleteFrequency');


            Route::post('addPeriod', [DosageController::class, 'addPeriod'])->name('addPeriod');
            Route::get('deletePeriod/{id}', [DosageController::class, 'deletePeriod'])->name('deletePeriod');
        });

        Route::prefix('feedback')->group(function () {
            Route::get('feedbacks', [FeedbackController::class, 'getFeedbacks'])->name('feedbacks');
            Route::get('feedbackForm', [FeedbackController::class, 'viewFeedbackForm'])->name('feedbackForm');
            Route::post('submitFeedback', [FeedbackController::class, 'submitFeedback'])->name('submitFeedback');
        });
    });

    Route::prefix('API')->group(function () {
        Route::post('drugs', [APIController::class, 'getDrugs']);
        Route::post('dosages', [APIController::class, 'getDosages']);
        Route::post('savePrescription', [APIController::class, 'savePrescription']);

        // Getting prescriptions
        Route::post('getPrescriptions/{id}', [APIController::class, 'getPrescriptions']);
        Route::post('getAllPrescriptions', [APIController::class, 'getAllRemainingPrescriptions']);

        Route::post('checkStocksAvailability', [APIController::class, 'checkStocksAvailability']);
        Route::post('issuePrescription', [APIController::class, 'issuePrescription']);
        Route::post('deletePrescription/{id}', [APIController::class, 'deletePrescription']);
        Route::post('getMedicalRecords/{patientId}', [APIController::class, 'getMedicalRecords']);

        // Queue
        Route::post('getQueue', [APIController::class, 'getQueue']);
        Route::post('updateQueue', [APIController::class, 'updateQueue']);

        // Drug API
        Route::post('getDosages', [DrugAPIController::class, 'getDosages']);
        Route::post('getFrequencies', [DrugAPIController::class, 'getFrequencies']);
        Route::post('getPeriods', [DrugAPIController::class, 'getPeriods']);

        Route::post('getQuantityTypes', [DrugAPIController::class, 'getQuantityTypes']);

        Route::post('saveDrugWithDosages', [DrugAPIController::class, 'saveDrugWithDosages']);
    });

    /*
     * SUPPORT API
     */
    Route::group(['prefix' => 'API'], function () {
        // Clinic registration support
        Route::post('support/timezones/{countryCode}', [SupportController::class, 'getTimezones']);
        Route::post('support/drugPredictions/{text}', [SupportController::class, 'getDrugPredictions']);
        Route::post('support/ingredientPredictions/{text}', [SupportController::class, 'getIngredientPredictions']);
        Route::post('support/manufacturerPredictions/{text}', [SupportController::class, 'getManufacturerPredictions']);
        Route::post('support/diseasePredictions/{text}', [SupportController::class, 'getDiseasePredictions']);
    });
});
