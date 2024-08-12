<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('dashboard', function() {
        return view('dashboard', [
            'users' => \App\Models\User::where('id', '<>', Auth::id())->get()
        ]);
    })
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('chatroom/{id}', function($id) {
        return view('chatroom', [
            'user_id' => $id
        ]);
    })
    ->middleware(['auth', 'verified'])
    ->name('chatroom');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
