<?php

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

Route::get('/', static fn(): View => view('app'));

Route::get('/api/docs', static fn(): View => view('swagger/ui', [
    'jsonUrl' => route('openapi.json'),
]))->middleware('moonshine.basic');
