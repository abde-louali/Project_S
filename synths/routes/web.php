<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\FoldersController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\VereficationController;
use Illuminate\Container\Attributes\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});
Route::get('/login', LoginController::class . "@index")->name('loginpage');
Route::post('/login', LoginController::class . "@logout")->name('logout');
Route::post('/sendlogin', LoginController::class . "@check");
Route::get('/admin', AdminController::class . '@index');
Route::get('/admin/profile', [AdminController::class, 'Adminprofile'])->name('admin.profile');
Route::post('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.update.profile');
Route::post('/admin/password/change', [AdminController::class, 'changePassword'])->name('admin.change.password');
Route::get('/student', StudentController::class . '@index');
Route::post('/student', StudentController::class . '@poststg');
Route::get('/classes', ClasseController::class . '@check')->name('classes.index');
Route::post('/classes/import', ClasseController::class . '@import')->name('classes.import');
Route::delete('/classes/{cin}', ClasseController::class . '@destroy')->name('classes.destroy');
Route::get('/filires', ClasseController::class . '@filiers');
Route::get('/class-details/{filierName}/{group}', FoldersController::class . '@groupdetails');
Route::post('/create-folders/{filierName}/{className}', FoldersController::class . '@createFolders')->name('create.folders');

Route::post('/verify-documents/{filierName}/{className}', [VereficationController::class, 'verifyDocuments'])
    ->name('verify.documents');

Route::get('/document-verification-results/{filierName}/{className}', [VereficationController::class, 'showResults'])
    ->name('document.verification.results');

// New routes for validation history
Route::get('/validation-history', [VereficationController::class, 'validationHistory'])
    ->name('validation.history');

Route::get('/validation-details/{filierName}/{className}', [VereficationController::class, 'validationDetails'])
    ->name('validation.details');

// Delete validation routes
Route::delete('/validation/{id}', [VereficationController::class, 'deleteValidation'])
    ->name('validation.delete');

Route::delete('/validation-details/{filierName}/{className}', [VereficationController::class, 'deleteAllValidations'])
    ->name('validation.delete.all');
