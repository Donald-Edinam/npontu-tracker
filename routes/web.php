<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Volt::route('/today', 'today')->name('today')->middleware('auth');
Volt::route('/activities', 'activities.index')->name('activities.index')->middleware('auth');
Volt::route('/reports', 'reports')->name('reports')->middleware('auth');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
