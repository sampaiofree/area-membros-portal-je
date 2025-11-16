<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DuxPack;
use Illuminate\Http\Request;

class DuxPackController extends Controller
{
    public function index()
    {
        $packs = DuxPack::orderByDesc('active')->orderBy('price_cents')->get();

        return view('admin.dux.packs.index', compact('packs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'duxes' => 'required|integer|min:1',
            'price_cents' => 'required|integer|min:0',
            'currency' => 'required|string|max:8',
            'active' => 'boolean',
        ]);

        $data['active'] ??= false;

        DuxPack::create($data);

        return redirect()->route('admin.dux.packs.index')->with('status', 'Pacote criado.');
    }

    public function update(Request $request, DuxPack $pack)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'duxes' => 'required|integer|min:1',
            'price_cents' => 'required|integer|min:0',
            'currency' => 'required|string|max:8',
            'active' => 'boolean',
        ]);

        $data['active'] ??= false;

        $pack->update($data);

        return redirect()->route('admin.dux.packs.index')->with('status', 'Pacote atualizado.');
    }

    public function destroy(DuxPack $pack)
    {
        $pack->delete();

        return redirect()->route('admin.dux.packs.index')->with('status', 'Pacote removido.');
    }
}
