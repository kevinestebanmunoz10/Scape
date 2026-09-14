<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home');

Route::view('/planes', 'planes');

Route::view('/modulos/personas', 'modulos.personas');
Route::view('/modulos/equipos', 'modulos.equipos');
Route::view('/modulos/visitantes', 'modulos.visitantes');
Route::view('/modulos/reportes', 'modulos.reportes');

Route::view('/admin/login', 'auth.login');
