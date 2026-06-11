<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\BarangController;
use App\Http\Controllers\Master\AsetController;
use App\Http\Controllers\Master\SupplierController;
use App\Http\Controllers\StoreRequisitionController;
use App\Http\Controllers\DeliveryRequisitionController;
use App\Http\Controllers\PurchaseRequisitionController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReceivingReportController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('dashboard'));

// Auth Routes (Breeze)
require __DIR__.'/auth.php';

Route::middleware(['auth', 'log.activity'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/notifications/{id}/read', [DashboardController::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [DashboardController::class, 'markAllRead'])->name('notifications.markAllRead');

    // Katalog
    Route::get('/katalog', [\App\Http\Controllers\KatalogController::class, 'index'])->name('katalog.index');

    // Profile Routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // =========================================
    // MASTER DATA
    // =========================================
    Route::prefix('master')->name('master.')->group(function () {

        // Barang
        Route::get('barang/export/excel', [BarangController::class, 'export'])->name('barang.export.excel');
        Route::get('barang/export/pdf', [BarangController::class, 'exportPdf'])->name('barang.export.pdf');
        Route::post('barang/import', [BarangController::class, 'import'])->name('barang.import');
        Route::resource('barang', BarangController::class);

        // Aset
        Route::get('aset/export/excel', [AsetController::class, 'export'])->name('aset.export.excel');
        Route::get('aset/export/pdf', [AsetController::class, 'exportPdf'])->name('aset.export.pdf');
        Route::resource('aset', AsetController::class);

        // Supplier
        Route::patch('supplier/{supplier}/rating', [SupplierController::class, 'updateRating'])->name('supplier.updateRating');
        Route::resource('supplier', SupplierController::class);
    });

    // =========================================
    // STORE REQUISITION (SR)
    // =========================================
    Route::prefix('sr')->name('sr.')->group(function () {
        Route::get('/', [StoreRequisitionController::class, 'index'])->name('index');
        Route::get('/create', [StoreRequisitionController::class, 'create'])->name('create');
        Route::post('/', [StoreRequisitionController::class, 'store'])->name('store');
        Route::get('/{sr}', [StoreRequisitionController::class, 'show'])->name('show');
        Route::get('/{sr}/edit', [StoreRequisitionController::class, 'edit'])->name('edit');
        Route::put('/{sr}', [StoreRequisitionController::class, 'update'])->name('update');
        Route::delete('/{sr}', [StoreRequisitionController::class, 'destroy'])->name('destroy');
        Route::post('/{sr}/submit', [StoreRequisitionController::class, 'submit'])->name('submit');
        Route::post('/{sr}/approve', [StoreRequisitionController::class, 'approve'])->name('approve');
        Route::post('/{sr}/reject', [StoreRequisitionController::class, 'reject'])->name('reject');
        Route::post('/{sr}/revisi', [StoreRequisitionController::class, 'revisi'])->name('revisi');
        Route::get('/{sr}/cetak', [StoreRequisitionController::class, 'cetak'])->name('cetak');
    });

    // =========================================
    // DELIVERY REQUISITION (DR)
    // =========================================
    Route::prefix('dr')->name('dr.')->group(function () {
        Route::get('/', [DeliveryRequisitionController::class, 'index'])->name('index');
        Route::get('/create', [DeliveryRequisitionController::class, 'create'])->name('create');
        Route::post('/', [DeliveryRequisitionController::class, 'store'])->name('store');
        Route::get('/{dr}', [DeliveryRequisitionController::class, 'show'])->name('show');
        Route::get('/{dr}/edit', [DeliveryRequisitionController::class, 'edit'])->name('edit');
        Route::put('/{dr}', [DeliveryRequisitionController::class, 'update'])->name('update');
        Route::delete('/{dr}', [DeliveryRequisitionController::class, 'destroy'])->name('destroy');
        Route::post('/{dr}/submit', [DeliveryRequisitionController::class, 'submit'])->name('submit');
        Route::post('/{dr}/approve', [DeliveryRequisitionController::class, 'approve'])->name('approve');
        Route::post('/{dr}/reject', [DeliveryRequisitionController::class, 'reject'])->name('reject');
        Route::post('/{dr}/selesai', [DeliveryRequisitionController::class, 'selesai'])->name('selesai');
        Route::get('/{dr}/cetak', [DeliveryRequisitionController::class, 'cetak'])->name('cetak');
    });

    // =========================================
    // PURCHASE REQUISITION (PR)
    // =========================================
    Route::prefix('pr')->name('pr.')->group(function () {
        Route::get('/', [PurchaseRequisitionController::class, 'index'])->name('index');
        Route::get('/create', [PurchaseRequisitionController::class, 'create'])->name('create');
        Route::post('/', [PurchaseRequisitionController::class, 'store'])->name('store');
        Route::get('/{pr}', [PurchaseRequisitionController::class, 'show'])->name('show');
        Route::get('/{pr}/edit', [PurchaseRequisitionController::class, 'edit'])->name('edit');
        Route::put('/{pr}', [PurchaseRequisitionController::class, 'update'])->name('update');
        Route::delete('/{pr}', [PurchaseRequisitionController::class, 'destroy'])->name('destroy');
        Route::post('/{pr}/submit', [PurchaseRequisitionController::class, 'submit'])->name('submit');
        Route::post('/{pr}/approve', [PurchaseRequisitionController::class, 'approve'])->name('approve');
        Route::post('/{pr}/reject', [PurchaseRequisitionController::class, 'reject'])->name('reject');
        Route::get('/{pr}/cetak', [PurchaseRequisitionController::class, 'cetak'])->name('cetak');
    });

    // =========================================
    // PURCHASE ORDER (PO)
    // =========================================
    Route::prefix('po')->name('po.')->group(function () {
        Route::get('/riwayat', [PurchaseOrderController::class, 'riwayat'])->name('riwayat');
        Route::get('/', [PurchaseOrderController::class, 'index'])->name('index');
        Route::get('/create', [PurchaseOrderController::class, 'create'])->name('create');
        Route::post('/', [PurchaseOrderController::class, 'store'])->name('store');
        Route::get('/{po}', [PurchaseOrderController::class, 'show'])->name('show');
        Route::get('/{po}/edit', [PurchaseOrderController::class, 'edit'])->name('edit');
        Route::put('/{po}', [PurchaseOrderController::class, 'update'])->name('update');
        Route::delete('/{po}', [PurchaseOrderController::class, 'destroy'])->name('destroy');
        Route::post('/{po}/submit', [PurchaseOrderController::class, 'submit'])->name('submit');
        Route::post('/{po}/approve-head', [PurchaseOrderController::class, 'approveHead'])->name('approve.head');
        Route::post('/{po}/reject-head', [PurchaseOrderController::class, 'rejectHead'])->name('reject.head');
        Route::post('/{po}/approve-finance', [PurchaseOrderController::class, 'approveFinance'])->name('approve.finance');
        Route::post('/{po}/reject-finance', [PurchaseOrderController::class, 'rejectFinance'])->name('reject.finance');
        Route::post('/{po}/kirim', [PurchaseOrderController::class, 'kirim'])->name('kirim');
        Route::get('/{po}/cetak', [PurchaseOrderController::class, 'cetak'])->name('cetak');
    });

    // =========================================
    // RECEIVING REPORT (RR)
    // =========================================
    Route::prefix('rr')->name('rr.')->group(function () {
        Route::get('/', [ReceivingReportController::class, 'index'])->name('index');
        Route::get('/create', [ReceivingReportController::class, 'create'])->name('create');
        Route::post('/', [ReceivingReportController::class, 'store'])->name('store');
        Route::get('/{rr}', [ReceivingReportController::class, 'show'])->name('show');
        Route::get('/{rr}/edit', [ReceivingReportController::class, 'edit'])->name('edit');
        Route::put('/{rr}', [ReceivingReportController::class, 'update'])->name('update');
        Route::delete('/{rr}', [ReceivingReportController::class, 'destroy'])->name('destroy');
        Route::post('/{rr}/submit', [ReceivingReportController::class, 'submit'])->name('submit');
        Route::post('/{rr}/approve', [ReceivingReportController::class, 'approve'])->name('approve');
        Route::post('/{rr}/reject', [ReceivingReportController::class, 'reject'])->name('reject');
        Route::get('/{rr}/cetak', [ReceivingReportController::class, 'cetak'])->name('cetak');
    });

    // =========================================
    // RETUR BARANG
    // =========================================
    Route::prefix('retur')->name('retur.')->group(function () {
        Route::get('/', [ReturController::class, 'index'])->name('index');
        Route::get('/create', [ReturController::class, 'create'])->name('create');
        Route::post('/', [ReturController::class, 'store'])->name('store');
        Route::get('/{retur}', [ReturController::class, 'show'])->name('show');
        Route::get('/{retur}/edit', [ReturController::class, 'edit'])->name('edit');
        Route::put('/{retur}', [ReturController::class, 'update'])->name('update');
        Route::delete('/{retur}', [ReturController::class, 'destroy'])->name('destroy');
        Route::post('/{retur}/submit', [ReturController::class, 'submit'])->name('submit');
        Route::post('/{retur}/approve', [ReturController::class, 'approve'])->name('approve');
        Route::post('/{retur}/reject', [ReturController::class, 'reject'])->name('reject');
        Route::post('/{retur}/kirim', [ReturController::class, 'kirim'])->name('kirim');
        Route::post('/{retur}/selesai', [ReturController::class, 'selesai'])->name('selesai');
        Route::get('/{retur}/cetak', [ReturController::class, 'cetak'])->name('cetak');
    });

    // =========================================
    // STOCK OPNAME
    // =========================================
    Route::prefix('opname')->name('opname.')->group(function () {
        Route::get('/', [StockOpnameController::class, 'index'])->name('index');
        Route::get('/create', [StockOpnameController::class, 'create'])->name('create');
        Route::post('/', [StockOpnameController::class, 'store'])->name('store');
        Route::get('/{opname}', [StockOpnameController::class, 'show'])->name('show');
        Route::get('/{opname}/edit', [StockOpnameController::class, 'edit'])->name('edit');
        Route::put('/{opname}', [StockOpnameController::class, 'update'])->name('update');
        Route::delete('/{opname}', [StockOpnameController::class, 'destroy'])->name('destroy');
        Route::post('/{opname}/selesai', [StockOpnameController::class, 'selesai'])->name('selesai');
        Route::get('/{opname}/cetak', [StockOpnameController::class, 'cetak'])->name('cetak');
    });

    // =========================================
    // LAPORAN
    // =========================================
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/barang', [LaporanController::class, 'barang'])->name('barang');
        Route::get('/aset', [LaporanController::class, 'aset'])->name('aset');
        Route::get('/supplier', [LaporanController::class, 'supplier'])->name('supplier');
        Route::get('/sr', [LaporanController::class, 'sr'])->name('sr');
        Route::get('/dr', [LaporanController::class, 'dr'])->name('dr');
        Route::get('/pr', [LaporanController::class, 'pr'])->name('pr');
        Route::get('/po', [LaporanController::class, 'po'])->name('po');
        Route::get('/rr', [LaporanController::class, 'rr'])->name('rr');
        Route::get('/retur', [LaporanController::class, 'retur'])->name('retur');
        Route::get('/opname', [LaporanController::class, 'opname'])->name('opname');
    });
});
