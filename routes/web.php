<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('find-user-by-name', function (Request $request) {
    $name = $request->query('name');
    $users = User::where('name', 'LIKE', '%' . $name . '%')->get(['id', 'name', 'email']);
    return response()->json($users);
})->name('find-user-by-name');
