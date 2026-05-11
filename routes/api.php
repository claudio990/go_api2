<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Clients
Route::get('/getClients', [ApiController::class, 'getClients']);
Route::post('/addClient', [ApiController::class, 'addClient']);
Route::post('/getRaffleParticipants', [ApiController::class, 'getRaffleParticipants']);


//Sells
Route::post('/addSell', [ApiController::class, 'addSell']);
Route::get('/getSells', [ApiController::class, 'getSells']);
Route::post('/deleteVenta', [ApiController::class, 'deleteVenta']);

//Weeks
Route::post('/addWeek', [ApiController::class, 'addWeek']);
Route::get('/getWeeks', [ApiController::class, 'getWeeks']);
Route::get('/getWeek', [ApiController::class, 'getWeek']);

//Pays
Route::post('/addPay', [ApiController::class, 'addPay']);
Route::get('/getPaysWeek', [ApiController::class, 'getPaysWeek']);
Route::post('/deletePago', [ApiController::class, 'deletePago']);


//Boxes
Route::get('/getBoxes', [ApiController::class, 'getBoxes']);
Route::get('/getBox', [ApiController::class, 'getBox']);

//Bills

//Bills
Route::post('/addBill', [ApiController::class, 'addBill']);
Route::get('/getBills', [ApiController::class, 'getBills'])->name('getBills'); // si existe
Route::post('/deleteBill', [ApiController::class, 'deleteBill']);


//Credits
Route::get('/credits', [ApiController::class, 'credits']);
Route::get('/seeDetails', [ApiController::class, 'seeDetails']);
Route::post('/addDiscount', [ApiController::class, 'addDiscount']);
Route::post('/deleteDiscount', [ApiController::class, 'deleteDiscount']);

//Finance
Route::get('/financeDashboard', [ApiController::class, 'financeDashboard']);

//Delivery System
Route::get('/getDeliveryPoints', [ApiController::class, 'getDeliveryPoints']);
Route::post('/addDeliveryPoint', [ApiController::class, 'addDeliveryPoint']);
Route::post('/deleteDeliveryPoint', [ApiController::class, 'deleteDeliveryPoint']);

Route::get('/getClientAddresses', [ApiController::class, 'getClientAddresses']);
Route::post('/addClientAddress', [ApiController::class, 'addClientAddress']);
Route::post('/updateClientAddress', [ApiController::class, 'updateClientAddress']);

Route::get('/getDeliveries', [ApiController::class, 'getDeliveries']);
Route::post('/addDelivery', [ApiController::class, 'addDelivery']);
Route::post('/updateDeliveryStatus', [ApiController::class, 'updateDeliveryStatus']);
Route::get('/checkDeliveryPortal', [ApiController::class, 'checkDeliveryPortal']);
