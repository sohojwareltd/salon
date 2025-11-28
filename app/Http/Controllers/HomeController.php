<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)
            ->latest()
            ->take(6)
            ->get();
        
        $topProviders = Provider::where('is_active', true)
            ->with(['user', 'services'])
            ->orderBy('average_rating', 'desc')
            ->take(10)
            ->get();
        
        return view('pages.home', compact('services', 'topProviders'));
    }
    
    public function about()
    {
        return view('pages.about');
    }
    
    public function contact()
    {
        return view('pages.contact');
    }
    
    public function contactStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);
        
        // In a real app, you would send an email or store the message
        // For now, just redirect with success message
        
        return back()->with('success', 'Thank you for your message! We\'ll get back to you soon.');
    }
}
