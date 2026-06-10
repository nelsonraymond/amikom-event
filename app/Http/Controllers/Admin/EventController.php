<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;     
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::with('category')->latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.events.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * PERBAIKAN:
     * 1. Dihapus: $event->poster_path (variabel $event tidak ada di method store)
     * 2. Dihapus: $event->update($data) → diganti Event::create($data)
     * 3. Diperbaiki: kurung kurawal if yang tidak seimbang
     * 4. Diperbaiki: pesan sukses ("diperbarui" → "ditambahkan")
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|numeric|min:1',
            'poster'      => 'nullable|image|max:2048',
        ]);

        // Simpan poster ke storage jika ada file yang diunggah
        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        Event::create($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * PERBAIKAN: Method ini sebelumnya kosong, diisi agar mengembalikan
     * halaman detail event untuk pengunjung.
     */
    public function show(Event $event)
    {
        return view('event-detail', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $categories = Category::all();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * PERBAIKAN:
     * 1. Ditambahkan: logika upload & hapus poster lama (kode ini sebelumnya
     *    salah ditempatkan di method store)
     * 2. Diperbaiki: string pesan sukses yang terpotong antar baris
     */
    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric',
            'stock'       => 'required|numeric',
        ]);

        if ($request->hasFile('poster')) {
            // Hapus poster lama jika sudah ada sebelumnya
            if ($event->poster_path) {
                Storage::disk('public')->delete($event->poster_path);
            }
            // Simpan poster baru ke direktori storage/app/public/posters
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $event->update($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Rincian data event berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus file poster dari storage sebelum data event dihapus dari database.
     */
    public function destroy(Event $event)
    {
        // Hapus file poster dari storage jika ada
        if ($event->poster_path) {
            Storage::disk('public')->delete($event->poster_path);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Data event berhasil dihapus secara permanen.');
    }
}