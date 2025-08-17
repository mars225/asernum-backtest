<?php

use App\Http\Controllers\MarkdownController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return view('welcome');});

Route::get('/getting-started', [MarkdownController::class, 'showMarkdown']);
Route::get('/coverage', function () { return redirect('coverage/index.html');});
