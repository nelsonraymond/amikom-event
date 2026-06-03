<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $partners = Partner::when($search, function ($query, $search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.partners.index', compact('partners', 'search'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'logo_url' => 'required|url|max:255',
        ]);

        Partner::create([
            'name'     => $request->name,
            'logo_url' => $request->logo_url,
        ]);

        return redirect()->route('admin.partners.index')
                         ->with('success', 'Partner berhasil ditambahkan!');
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'logo_url' => 'required|url|max:255',
        ]);

        $partner->update([
            'name'     => $request->name,
            'logo_url' => $request->logo_url,
        ]);

        return redirect()->route('admin.partners.index')
                         ->with('success', 'Partner berhasil diperbarui!');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();

        return redirect()->route('admin.partners.index')
                         ->with('success', 'Partner berhasil dihapus!');
    }
}