<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Partner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $partners = Partner::orderBy('name')->get();

        $query = Event::with('category')
            ->where('date', '>=', now())
            ->orderBy('date', 'asc');

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $events = $query->get();

        return view('welcome', compact('events', 'categories', 'partners'));
    }
    public function katalog(Request $request)
    {
        $categories = Category::orderBy('name')->get();
 
        $query = Event::with('category')
            ->where('date', '>=', now())
            ->orderBy('date', 'asc');
 
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
 
        $events = $query->get();
 
        return view('katalog', compact('events', 'categories'));
    }
 
    public function profil()
    {
        return view('profil');
    }
 
    public function bantuan()
    {
        return view('bantuan');
    }
 
    public function contact()
    {
        return view('contact');
    }
}