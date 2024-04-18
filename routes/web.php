<?php

use MCris112\FileSystemManager\Controllers\StorageController;

Route::get('/storage/{disk}/users/{filename}', [StorageController::class, 'avatar']);

Route::get('/storage/{disk}/{path}', [StorageController::class, 'index'])
    ->where('path', '.*')
    ->middleware('web');
