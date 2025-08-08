<?php



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiController;


// Example API route
Route::get('/hello', function () {
    return response()->json(['message' => 'Hello, API!']);
});

// Add your API routes below
