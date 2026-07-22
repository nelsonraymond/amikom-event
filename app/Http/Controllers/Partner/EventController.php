<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    protected CloudinaryService $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    public function index()
    {
        $partner = Auth::guard('partner')->user();

        $events = $partner->events()
            ->withCount('reviews')
            ->latest()
            ->paginate(10);

        return view('partner.events.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('partner.events.create', compact('categories'));
    }

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

        // partner_id TIDAK datang dari request — dipaksa dari sesi login,
        // biar partner A gak bisa nyolong-nyelipin partner_id milik partner B
        $data['partner_id'] = Auth::guard('partner')->id();

        if ($request->hasFile('poster')) {
            $data['poster_path'] = $this->cloudinary->upload(
                $request->file('poster')->getRealPath()
            );
        }

        Event::create($data);

        return redirect()->route('partner.events.index')
            ->with('success', 'Event berhasil ditambahkan.');
    }

    public function edit(Event $event)
    {
        $this->authorizeOwnership($event);

        $categories = Category::all();
        return view('partner.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorizeOwnership($event);

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
            if ($event->poster_path) {
                $this->cloudinary->delete($event->poster_path);
            }
            $data['poster_path'] = $this->cloudinary->upload(
                $request->file('poster')->getRealPath()
            );
        }

        $event->update($data);

        return redirect()->route('partner.events.index')
            ->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        $this->authorizeOwnership($event);

        if ($event->poster_path) {
            $this->cloudinary->delete($event->poster_path);
        }

        $event->delete();

        return redirect()->route('partner.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }

    /**
     * Blokir partner yang coba akses/edit/hapus event milik partner lain
     * lewat URL manual (mass assignment / IDOR guard).
     */
    private function authorizeOwnership(Event $event): void
    {
        abort_unless($event->partner_id === Auth::guard('partner')->id(), 403);
    }
}