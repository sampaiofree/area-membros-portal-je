<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CertificadoController extends Controller
{
    public function index(): View
    {
        return view('certificado.index');
    }
}
