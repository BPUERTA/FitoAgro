<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientUserController;
use App\Http\Controllers\ContractorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\FarmLotController;
use App\Http\Controllers\MercadoPagoWebhookController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationUserController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistroTecnicoController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSessionLogController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\WorkOrderPreviewController;
use App\Http\Controllers\VigiaSatelitalController;
use App\Models\Plan;

Route::get('/', function () {
    $publishedPlans = collect();

    try {
        if (Schema::hasTable('plans')) {
            $publishedPlans = Plan::where('published', true)
                ->where('status', true)
                ->orderBy('monthly_price')
                ->get();
        }
    } catch (\Throwable $e) {
        // Keep landing page accessible even if DB is not ready.
    }

    return view('welcome', compact('publishedPlans'));
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('clients', ClientController::class);
    Route::resource('farms', FarmController::class);
    Route::post('farms/{farm}/lots', [FarmLotController::class, 'store'])->name('farms.lots.store');
    Route::put('farms/{farm}/lots/{lot}', [FarmLotController::class, 'update'])->name('farms.lots.update');
    Route::delete('farms/{farm}/lots/{lot}', [FarmLotController::class, 'destroy'])->name('farms.lots.destroy');
    Route::resource('professionals', ProfessionalController::class);
    Route::resource('contractors', ContractorController::class);
    Route::resource('teams', TeamController::class);
    Route::resource('users', UserController::class);
    Route::get('user-session-logs', [UserSessionLogController::class, 'index'])
        ->middleware('admin')
        ->name('user-session-logs.index');
    Route::resource('products', ProductController::class);
    Route::resource('organizations', OrganizationController::class);
    Route::resource('plans', PlanController::class);

    Route::resource('registro-tecnicos', RegistroTecnicoController::class);
    Route::get('registro-tecnicos/{registroTecnico}/work-orders/create', [RegistroTecnicoController::class, 'createWorkOrder'])
        ->name('registro-tecnicos.work-orders.create');

    Route::resource('work-orders', WorkOrderController::class);
    Route::get('work-orders/{workOrder}/pdf', [WorkOrderController::class, 'pdf'])
        ->name('work-orders.pdf');
    Route::get('work-orders/preview/{id?}', [WorkOrderPreviewController::class, 'sample'])
        ->name('work-orders.preview');
    Route::get('work-orders/client/{client}/farms', [WorkOrderController::class, 'getFarmsByClient'])
        ->name('work-orders.farms');
    Route::post('work-orders/{workOrder}/apply-farm', [WorkOrderController::class, 'applyFarm'])
        ->name('work-orders.apply-farm');

    Route::get('vigia-satelital', [VigiaSatelitalController::class, 'index'])->name('vigia-satelital.index');
    Route::get('vigia-satelital/mapas', [VigiaSatelitalController::class, 'mapas'])->name('vigia-satelital.mapas');
    Route::get('vigia-satelital/alertas', [VigiaSatelitalController::class, 'alertas'])->name('vigia-satelital.alertas');
    Route::get('vigia-satelital/acciones', [VigiaSatelitalController::class, 'acciones'])->name('vigia-satelital.acciones');
    Route::get('vigia-satelital/configuracion', [VigiaSatelitalController::class, 'configuracion'])->name('vigia-satelital.config');
    Route::put('vigia-satelital/configuracion', [VigiaSatelitalController::class, 'updateConfiguracion'])->name('vigia-satelital.config.update');

    Route::get('organization-users', [OrganizationUserController::class, 'index'])
        ->name('organization-users.index');
    Route::get('client-users/{organization}/create', [ClientUserController::class, 'create'])
        ->name('client-users.create');
    Route::post('client-users/{organization}', [ClientUserController::class, 'store'])
        ->name('client-users.store');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('subscription/{plan}/subscribe', [SubscriptionController::class, 'subscribe'])
        ->name('subscription.subscribe');
    Route::get('subscription/{plan}/periodicity', [SubscriptionController::class, 'selectPeriodicity'])
        ->name('subscription.periodicity');
    Route::post('subscription/cancel', [SubscriptionController::class, 'cancel'])
        ->name('subscription.cancel');
    Route::get('subscription/success', [SubscriptionController::class, 'success'])
        ->name('subscription.success');

    Route::get('billing', [BillingController::class, 'showForm'])->name('billing.form');
    Route::post('billing', [BillingController::class, 'store'])->name('billing.store');

    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('settings/backup', [SettingsController::class, 'downloadBackup'])->name('settings.backup');
});

Route::post('webhooks/mercadopago', [MercadoPagoWebhookController::class, 'handle'])
    ->name('webhooks.mercadopago');

require __DIR__.'/auth.php';
