<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\testsController;
use Illuminate\Support\Facades\DB;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
})->name('main');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    $data ="this is the data";

    //get all subject
    $subject = DB::table('subjects')->get();

    return view('dashboard' , ['data'=>$data ,'subjects'=>$subject]);
})->name('dashboard');

Route::get('test/{subject_id}',[testscontroller::class, "getTestQuestions"])->name('getTestQuestions')->middleware('auth');
Route::post('/submitExam', [testscontroller::class, "submitExam"])->name('submitExam');
Route::get('/register_exam/{subject_id}',[testscontroller::class, "registerExam"])->name('registerExam');

Route::get('/allResults',[testscontroller::class, "allResults"])->name('allResults');
Route::get('/allTests',[testscontroller::class, "allTests"])->name('allTests');
Route::get('/sendRemainingTime/{remaining_time}/subjectId/{subject_id}', [testscontroller::class, "sendRemainingTime"])->name("sendRemainingTime");
