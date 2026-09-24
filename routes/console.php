<?php

use Illuminate\Foundation\Inspiring; // Clase que provee frases inspiradoras para el comando
use Illuminate\Support\Facades\Artisan; // Fachada para registrar comandos de consola

Artisan::command('inspire', function () { // Registra el comando de consola llamado 'inspire'
    $this->comment(Inspiring::quote()); // Muestra una frase inspiradora aleatoria en la consola
})->purpose('Display an inspiring quote'); // Define la descripción del comando
