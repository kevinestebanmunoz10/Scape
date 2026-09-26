<?php

// Espacio de nombres al que pertenece este controlador

namespace App\Http\Controllers;

use Illuminate\View\View; // Tipo de retorno para vistas

// Controlador que muestra el menú de gestión con los módulos disponibles
class GestionController extends Controller
{
    // funcion para mostrar la pantalla de gestion
    public function index(): View
    {
        return view('admin.gestion.index'); // Muestra la vista con los botones de los módulos de gestión
    }
}
