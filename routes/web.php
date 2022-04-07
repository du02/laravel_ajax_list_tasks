<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Task::class, 'index'])->name('index.ajax');
Route::post('/task', [App\Http\Controllers\Task::class, 'store'])->name('store.ajax');
Route::get('/tasks', [App\Http\Controllers\Task::class, 'getTasks'])->name('getTasks.ajax');
Route::post('/remove/{id}', [App\Http\Controllers\Task::class, 'destroy'])->name('destroy.ajax');
