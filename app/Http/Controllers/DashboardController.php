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
            $availableTabs = ['painel', 'cursos', 'vitrine', 'notificacoes', 'suporte', 'conta'];
            $initialTab = in_array($request->query('tab'), $availableTabs, true) ? $request->query('tab') : 'painel';
            $unreadCount = 0;

            if (
                \Illuminate\Support\Facades\Schema::hasTable('notifications') &&
                \Illuminate\Support\Facades\Schema::hasColumn('notifications', 'notifiable_type') &&
                \Illuminate\Support\Facades\Schema::hasColumn('notifications', 'notifiable_id')
            ) {
                $unreadCount = $user->unreadNotifications()->count();
            }

            return view('dashboard.student', [
                'user' => $user,
                'initialTab' => $initialTab,
                'availableTabs' => $availableTabs,
                'unreadCount' => $unreadCount,
            ]);
        }

        return view('dashboard.admin'); 
    }
}
