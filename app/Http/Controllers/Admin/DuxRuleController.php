<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DuxRule;
use Illuminate\Http\Request;

class DuxRuleController extends Controller
{
    public function index()
    {
        $rules = DuxRule::orderBy('direction')->orderBy('name')->get();

        return view('admin.dux.rules.index', compact('rules'));
    }

    public function update(Request $request, DuxRule $rule)
    {
        $data = $request->validate([
            'amount' => 'required|integer|min:1',
        ]);

        $rule->update(['amount' => $data['amount']]);

        return redirect()->route('admin.dux.rules.index')->with('status', 'Regra atualizada.');
    }
}
