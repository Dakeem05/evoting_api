<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\voterController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CountController;
use App\Http\Controllers\votescontroller;
use App\Http\Controllers\candidateController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin/home/index', ['page_name' => 'Home','greeting' => 'Welcome Back']);

})->middleware('auth');

Route::get('/login', [AuthController::class,'loginView'])->name('login');
Route::post('/login', [AuthController::class,'loginPost'])->name('login.post');
Route::get('/ForgotPassword',[AuthController::class,'forgotView'])->name('forgot.view');
Route::post('/ForgotPassword',[AuthController::class,'forgotPost'])->name('forgot.post');
Route::get('/ResetPassword/{token}',[AuthController::class,'ResetPasswordView'])->name('ResetPassword.view');
Route::post('/ResetPassword/{token}',[AuthController::class,'ResetPasswordPost'])->name('ResetPassword.post');Route::middleware('auth')->group(function () {
   
Route::get('/Admin.registration', [AuthController::class,'AdminRegistrationView'])->name('registration.view');
Route::post('/Admin.registration', [AuthController::class,'AdminRegistrationPost'])->name('registration.post');

Route::get('/Admin_record', [AuthController::class,'Admin_recordView'])->name('Admin_record.view');
Route::get('/delete.Admin/{id}', [AuthController::class,'DeleteAdmin'])->name('delete.Admin');
Route::get('/enable_disable.Admin/{id}', [AuthController::class,'enable_disable_Admin'])->name('enable_disable.Admin');

Route::get('/logout',[adminController::class,'logout'])->name('logout');

Route::get('/profile', [adminController::class,'profileView'])->name('profile.view');
Route::post('/profile', [adminController::class,'profilePost'])->name('profile.post');

Route::get('/ChangePassword', [adminController::class,'changePasswordView'])->name('changePassword.view');
Route::post('/ChangePassword', [adminController::class,'changePasswordPost'])->name('changePassword.post');
});

Route::middleware('auth')->group(function () {
Route::get('/countrecord', [CountController::class,'countUsers'])->name('countrecord');
});

Route::middleware('auth')->group(function () {
Route::get('/voterRecord', [voterController::class,'voterRecordView'])->name('voterRecord.view');
Route::get('/ViewVoterData/{id}', [voterController::class,'ViewAllVoterRecords'])->name('ViewAllVoterRecords.view');
Route::get('/delete.Voter/{id}', [voterController::class,'DeleteVoter'])->name('delete.Voter');
Route::get('/enable_disable.Voter/{id}', [voterController::class,'enable_disable_Voter'])->name('enable_disable.Voter');
 
});

Route::middleware('auth')->group(function () {
Route::get('/Votes', [votesController::class,'votesRecordView'])->name('votesRecordView.view');
Route::get('/ViewVotesData/{id}', [votesController::class,'ViewAllVotesRecords'])->name('ViewAllVotesRecords.view');

});

Route::middleware('auth')->group(function () {
Route::get('/candidates', [candidateController::class,'candidatesRecordView'])->name('candidatesRecordView.view');
Route::get('/delete.Candidate/{id}', [candidateController::class,'DeleteCandidate'])->name('delete.Candidate');
Route::get('/enable_disable.Candidate/{id}', [candidateController::class,'enable_disable_Candidate'])->name('enable_disable.Candidate');
Route::get('/ViewCandidateData/{id}', [candidateController::class,'ViewAllCandidateRecords'])->name('ViewAllCandidateRecords.view');

});