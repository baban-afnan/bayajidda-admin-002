<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\Admin\AdminWalletController;
use App\Http\Controllers\Admin\DataVariationController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SmeDataController;
use App\Http\Controllers\Admin\KiraniDataController;
use App\Http\Controllers\Admin\SmileDataController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Agency\BVNmodController;
use App\Http\Controllers\Agency\BVNserviceController;
use App\Http\Controllers\Agency\BvnSearchController;
use App\Http\Controllers\Agency\CRMController;
use App\Http\Controllers\Agency\NINmodController;
use App\Http\Controllers\Agency\NinIpeController;
use App\Http\Controllers\Agency\NinPersonalisationController;
use App\Http\Controllers\Agency\ValidationController;
use App\Http\Controllers\Agency\VninToNibssController;
use App\Http\Controllers\Agency\CACController;
use App\Http\Controllers\Agency\VisaRequestController;
use App\Http\Controllers\Agency\EsimRequestController;
use App\Http\Controllers\Agency\HotelBookingController;
use App\Http\Controllers\Agency\FlightBookingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    // --- Shared Authenticated Routes ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::post('/photo', [ProfileController::class, 'updatePhoto'])->name('photo');
        Route::post('/pin', [ProfileController::class, 'updatePin'])->name('pin');
        Route::post('/update-required', [ProfileController::class, 'updateRequired'])->name('updateRequired');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // General User Utilities
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
    Route::get('/support', [SupportController::class, 'index'])->name('support');

    // Wallet & Transfers
    Route::prefix('wallet')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('wallet');
        Route::post('/create', [WalletController::class, 'createWallet'])->name('wallet.create');
        Route::post('/claim-bonus', [WalletController::class, 'claimBonus'])->name('wallet.claimBonus');
        
        // P2P Transfer Routes
        Route::prefix('transfer')->group(function () {
            Route::get('/', [TransferController::class, 'index'])->name('wallet.transfer');
            Route::post('/verify', [TransferController::class, 'verifyUser'])->name('transfer.verify');
            Route::post('/process', [TransferController::class, 'processTransfer'])->name('transfer.process');
            Route::post('/verify-pin', [TransferController::class, 'verifyPin'])->name('verify.pin');
        });
    });

    // --- Admin Management Routes ---
    Route::prefix('admin')->name('admin.')->group(function () {
        
        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserManagementController::class, 'index'])->name('index');
            Route::post('/', [UserManagementController::class, 'store'])->name('store');
            Route::post('/block-ip', [UserManagementController::class, 'blockIp'])->name('block-ip');
            Route::delete('/unblock-ip/{blockedIp}', [UserManagementController::class, 'unblockIp'])->name('unblock-ip');
            Route::get('/download-sample', [UserManagementController::class, 'downloadSample'])->name('download-sample');
            Route::post('/import', [UserManagementController::class, 'import'])->name('import');
            Route::get('/{user}', [UserManagementController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
            Route::patch('/{user}/status', [UserManagementController::class, 'updateStatus'])->name('update-status');
            Route::patch('/{user}/role', [UserManagementController::class, 'updateRole'])->name('update-role');
            Route::patch('/{user}/limit', [UserManagementController::class, 'updateLimit'])->name('update-limit');
            Route::patch('/{user}/verify-email', [UserManagementController::class, 'verifyEmail'])->name('verify-email');
        });

        // Wallet Management
        Route::prefix('wallet')->name('wallet.')->group(function () {
            Route::get('/', [AdminWalletController::class, 'index'])->name('index');
            Route::get('/fund', [AdminWalletController::class, 'fundView'])->name('fund.view');
            Route::post('/fund', [AdminWalletController::class, 'fund'])->name('fund');
            Route::get('/bulk-fund', [AdminWalletController::class, 'bulkFundView'])->name('bulk-fund.view');
            Route::post('/bulk-fund', [AdminWalletController::class, 'bulkFund'])->name('bulk-fund');
        });

        // Service Management
        Route::prefix('services')->name('services.')->group(function () {
            Route::get('/', [ServiceController::class, 'index'])->name('index');
            Route::post('/', [ServiceController::class, 'store'])->name('store');
            Route::get('/{service}', [ServiceController::class, 'show'])->name('show');
            Route::put('/{service}', [ServiceController::class, 'update'])->name('update');
            Route::delete('/{service}', [ServiceController::class, 'destroy'])->name('destroy');

            // Fields & Prices
            Route::post('/{service}/fields', [ServiceController::class, 'storeField'])->name('fields.store');
            Route::put('/fields/{field}', [ServiceController::class, 'updateField'])->name('fields.update');
            Route::delete('/fields/{field}', [ServiceController::class, 'destroyField'])->name('fields.destroy');
            Route::post('/{service}/prices', [ServiceController::class, 'storePrice'])->name('prices.store');
            Route::put('/prices/{price}', [ServiceController::class, 'updatePrice'])->name('prices.update');
            Route::delete('/prices/{price}', [ServiceController::class, 'destroyPrice'])->name('prices.destroy');
        });

        // Data & SME Management
        Route::prefix('data-variations')->name('data-variations.')->group(function () {
            Route::get('/', [DataVariationController::class, 'index'])->name('index');
            Route::get('/{service}', [DataVariationController::class, 'show'])->name('show');
            Route::post('/', [DataVariationController::class, 'store'])->name('store');
            Route::put('/{dataVariation}', [DataVariationController::class, 'update'])->name('update');
            Route::delete('/{dataVariation}', [DataVariationController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('sme-data')->name('sme-data.')->group(function () {
            Route::get('/', [SmeDataController::class, 'index'])->name('index');
            Route::post('/', [SmeDataController::class, 'store'])->name('store');
            Route::post('/sync', [SmeDataController::class, 'sync'])->name('sync');
            Route::put('/{smeData}/update', [SmeDataController::class, 'update'])->name('update');
            Route::delete('/{smeData}', [SmeDataController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('kirani-data')->name('kirani-data.')->group(function () {
            Route::get('/', [KiraniDataController::class, 'index'])->name('index');
            Route::post('/', [KiraniDataController::class, 'store'])->name('store');
            Route::post('/sync', [KiraniDataController::class, 'sync'])->name('sync');
            Route::put('/{kiraniPlan}/update', [KiraniDataController::class, 'update'])->name('update');
            Route::delete('/{kiraniPlan}', [KiraniDataController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('smile-data')->name('smile-data.')->group(function () {
            Route::get('/', [SmileDataController::class, 'index'])->name('index');
            Route::post('/', [SmileDataController::class, 'store'])->name('store');
            Route::post('/sync', [SmileDataController::class, 'sync'])->name('sync');
            Route::put('/{smilePlan}/update', [SmileDataController::class, 'update'])->name('update');
            Route::delete('/{smilePlan}', [SmileDataController::class, 'destroy'])->name('destroy');
        });
    });

    // --- Agency Services (Super Admin) ---
    Route::middleware(['role:super_admin'])->group(function () {
        
        // BVN Services
        Route::prefix('bvn-modification')->name('bvnmod.')->group(function () {
            Route::get('/', [BVNmodController::class, 'index'])->name('index');
            Route::get('/{id}', [BVNmodController::class, 'show'])->name('show');
            Route::put('/{id}', [BVNmodController::class, 'update'])->name('update');
            Route::get('/{id}/check-status', [BVNmodController::class, 'checkStatus'])->name('check-status');
        });

        Route::prefix('bvn-search')->name('bvn-search.')->group(function () {
            Route::get('/', [BvnSearchController::class, 'index'])->name('index');
            Route::get('/{id}', [BvnSearchController::class, 'show'])->name('show');
            Route::put('/{id}', [BvnSearchController::class, 'update'])->name('update');
        });

        Route::prefix('bvn-service')->name('bvnservice.')->group(function () {
            Route::get('/', [BVNserviceController::class, 'index'])->name('index');
            Route::get('/{id}', [BVNserviceController::class, 'show'])->name('show');
            Route::put('/{id}', [BVNserviceController::class, 'update'])->name('update');
        });

        // NIN Services
        Route::prefix('nin-modification')->name('ninmod.')->group(function () {
            Route::get('/', [NINmodController::class, 'index'])->name('index');
            Route::get('/{id}', [NINmodController::class, 'show'])->name('show');
            Route::put('/{id}', [NINmodController::class, 'update'])->name('update');
            Route::get('/{id}/check-status', [NINmodController::class, 'checkStatus'])->name('check-status');
        });

        Route::prefix('nin-ipe')->name('ninipe.')->group(function () {
            Route::get('/', [NinIpeController::class, 'index'])->name('index');
            Route::get('/{id}', [NinIpeController::class, 'show'])->name('show');
            Route::put('/{id}', [NinIpeController::class, 'update'])->name('update');
            Route::get('/{id}/check-status', [NinIpeController::class, 'checkStatus'])->name('check-status');
        });

        Route::prefix('nin-personalisation')->name('nin-personalisation.')->group(function () {
            Route::get('/', [NinPersonalisationController::class, 'index'])->name('index');
            Route::get('/{id}', [NinPersonalisationController::class, 'show'])->name('show');
            Route::put('/{id}', [NinPersonalisationController::class, 'update'])->name('update');
        });

        // CRM & Support
        Route::prefix('crm')->name('crm.')->group(function () {
            Route::get('/', [CRMController::class, 'index'])->name('index');
            Route::get('/export/csv', [CRMController::class, 'exportCsv'])->name('export.csv');
            Route::get('/export/excel', [CRMController::class, 'exportExcel'])->name('export.excel');
            Route::get('/{id}', [CRMController::class, 'show'])->name('show');
            Route::put('/{id}', [CRMController::class, 'update'])->name('update');
        });

        Route::prefix('validation')->name('validation.')->group(function () {
            Route::get('/', [ValidationController::class, 'index'])->name('index');
            Route::get('/{id}', [ValidationController::class, 'show'])->name('show');
            Route::put('/{id}', [ValidationController::class, 'update'])->name('update');
            Route::get('/{id}/check-status', [ValidationController::class, 'checkStatus'])->name('check-status');
        });

        Route::prefix('vnin-nibss')->name('vnin-nibss.')->group(function () {
            Route::get('/', [VninToNibssController::class, 'index'])->name('index');
            Route::get('/{id}', [VninToNibssController::class, 'show'])->name('show');
            Route::put('/{id}', [VninToNibssController::class, 'update'])->name('update');
            Route::post('/', [VninToNibssController::class, 'store'])->name('store');
        });

        // CAC Registration
        Route::prefix('cac-registration')->name('cac.')->group(function () {
            Route::get('/', [CACController::class, 'index'])->name('index');
            Route::get('/{id}', [CACController::class, 'show'])->name('show');
            Route::put('/{id}', [CACController::class, 'update'])->name('update');
        });

        // Visa Request
        Route::prefix('visa-request')->name('visa.')->group(function () {
            Route::get('/', [VisaRequestController::class, 'index'])->name('index');
            Route::get('/{id}', [VisaRequestController::class, 'show'])->name('show');
            Route::put('/{id}', [VisaRequestController::class, 'update'])->name('update');
        });

        Route::get('/esim', [EsimRequestController::class, 'index'])->name('esim');
        Route::prefix('esim-request')->name('esim.')->group(function () {
            Route::get('/', [EsimRequestController::class, 'index'])->name('index');
            Route::get('/{id}', [EsimRequestController::class, 'show'])->name('show');
            Route::put('/{id}', [EsimRequestController::class, 'update'])->name('update');
        });

        // Hotel Booking
        Route::prefix('hotel-booking')->name('hotel.')->group(function () {
            Route::get('/', [HotelBookingController::class, 'index'])->name('index');
            Route::get('/{id}', [HotelBookingController::class, 'show'])->name('show');
            Route::put('/{id}', [HotelBookingController::class, 'update'])->name('update');
        });

        // Flight Booking
        Route::prefix('flight-booking')->name('flight.')->group(function () {
            Route::get('/', [FlightBookingController::class, 'index'])->name('index');
            Route::get('/{id}', [FlightBookingController::class, 'show'])->name('show');
            Route::put('/{id}', [FlightBookingController::class, 'update'])->name('update');
        });
    });
});

require __DIR__.'/auth.php';

    

require __DIR__.'/auth.php';
