<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        if ($user->isStudent()) {
            return view('dashboard.student', ['user' => $user]);
        }

        return view('dashboard.admin');
    }
}
