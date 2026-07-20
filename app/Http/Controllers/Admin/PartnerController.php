<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            'email'    => 'required|email|unique:partners,email',
            'password' => 'required|string|min:8',
            'logo_url' => 'nullable|url|max:255',
        ]);

        Partner::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'logo_url' => $request->logo_url,
            'status'   => 'active',
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
            'email'    => 'required|email|unique:partners,email,' . $partner->id,
            'password' => 'nullable|string|min:8', // opsional saat edit
            'logo_url' => 'nullable|url|max:255',
        ]);

        $data = [
            'name'     => $request->name,
            'email'    => $request->email,
            'logo_url' => $request->logo_url,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $partner->update($data);

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