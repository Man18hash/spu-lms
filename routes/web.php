<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoanApplicationController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\RepaymentController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Bookkeeper\PaymentController as BookkeeperPaymentController;
/*
|--------------------------------------------------------------------------
| Public Landing Pages
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('dashboard'));
Route::view('/dashboard', 'dashboard')->name('dashboard');
Route::view('/loans',     'loans')->name('loans.index');

/*
|--------------------------------------------------------------------------
| Authentication Routes (Client, Bookkeeper, Admin)
|--------------------------------------------------------------------------
*/

// CLIENT
Route::view('/signup', 'auth.signup')->name('signup');
Route::post('/signup', [AuthController::class, 'register'])->name('auth.register');
Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

// BOOKKEEPER
Route::view('/bookkeeper/login', 'auth.bookkeeper.login')->name('bookkeeper.login');
Route::post('/bookkeeper/login', [AuthController::class, 'loginBookkeeper'])
     ->name('bookkeeper.auth.login');

// ADMIN
Route::view('/admin/login', 'auth.admin.login')->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'loginAdmin'])
     ->name('admin.auth.login');

// Logout (all roles)
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

/*
|--------------------------------------------------------------------------
| Client-only Pages
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RoleMiddleware::class . ':client'])
     ->prefix('client')
     ->name('client.')
     ->group(function() {
         Route::view('home',   'client.home')->name('home');
         Route::view('loan',   'client.loan')->name('loan');
         Route::get('loans',   [LoanApplicationController::class, 'index'])->name('loans');
         Route::get('account',[AccountController::class, 'show'])->name('account');
         Route::post('account',[AccountController::class, 'update'])->name('account.update');
         Route::post('apply', [LoanApplicationController::class, 'store'])->name('apply');
     });

/*
|--------------------------------------------------------------------------
| Bookkeeper-only Page
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RoleMiddleware::class . ':bookkeeper'])
    ->prefix('bookkeeper')
    ->name('bookkeeper.')
    ->group(function () {
        Route::get('/home', fn() => view('bookkeeper.home'))->name('home');
        Route::get('/payments', [BookkeeperPaymentController::class, 'index'])->name('payments');
        Route::post('/application/{application}/ledger', [BookkeeperPaymentController::class, 'storeLedger'])->name('application.ledger');
        Route::get('/ledger/{application}', [BookkeeperPaymentController::class, 'viewLedger'])->name('ledger.view');
        
        // Repayment routes for Bookkeeper
        Route::get('/repayments/create/{expectedSchedule}', [BookkeeperPaymentController::class, 'createRepayment'])->name('repayments.create');
        Route::post('/repayments', [BookkeeperPaymentController::class, 'storeRepayment'])->name('repayments.store');
        Route::get('/repayments/{repayment}/edit', [BookkeeperPaymentController::class, 'editRepayment'])->name('repayments.edit');
        Route::put('/repayments/{repayment}', [BookkeeperPaymentController::class, 'updateRepayment'])->name('repayments.update');
        Route::delete('/repayments/{repayment}', [BookkeeperPaymentController::class, 'destroyRepayment'])->name('repayments.destroy');
    });

/*
|--------------------------------------------------------------------------
| Admin-only Pages
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
     ->middleware(['auth', RoleMiddleware::class . ':admin'])
     ->name('admin.')
     ->group(function() {
         // Dashboard
         Route::get('/', [DashboardController::class, 'index'])->name('home');

         // User management
         Route::get('user',           [UserController::class, 'index'])->name('user');
         Route::put('user/{user}',    [UserController::class, 'update'])->name('user.update');
         Route::delete('user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

         // Transactions
         Route::view('transaction', 'admin.transaction')->name('transaction');

         // Loan Applications
         Route::get('application', [ApplicationController::class, 'index'])->name('application');
         Route::patch('application/{application}', [ApplicationController::class, 'update'])
              ->name('application.update');

         // Repayments — explicit routes only:
         Route::get(
             'repayments/create/{expectedSchedule}',
             [RepaymentController::class, 'create']
         )->name('repayments.create');

         Route::post(
             'repayments',
             [RepaymentController::class, 'store']
         )->name('repayments.store');

         Route::get(
             'repayments/{repayment}/edit',
             [RepaymentController::class, 'edit']
         )->name('repayments.edit');

         Route::put(
             'repayments/{repayment}',
             [RepaymentController::class, 'update']
         )->name('repayments.update');

         Route::delete(
             'repayments/{repayment}',
             [RepaymentController::class, 'destroy']
         )->name('repayments.destroy');

         // Save Subsidiary Ledger (AJAX)
         Route::post('application/{application}/ledger', [DashboardController::class, 'storeLedger'])
              ->name('application.ledger');

         // View saved ledger
         Route::get('ledger/{application}', [DashboardController::class, 'viewLedger'])
              ->name('ledger.view');
     });
     Route::prefix('admin')
     ->middleware(['auth', RoleMiddleware::class . ':admin'])
     ->name('admin.')
     ->group(function() {
         // … other admin routes …

         // Transaction History
         Route::get('transaction', [TransactionController::class, 'index'])
              ->name('transaction');
     });
     Route::get('client/home', [LoanApplicationController::class, 'home'])->name('client.home');

