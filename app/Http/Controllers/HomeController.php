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
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        // Store contact form submission
        $contact = \App\Models\Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);
        
        // Send notification to admin
        try {
            $adminEmail = \App\Facades\Settings::get('email', config('mail.from.address'));
            \Illuminate\Support\Facades\Notification::route('mail', $adminEmail)
                ->notify(new \App\Notifications\ContactFormSubmitted($contact));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send contact notification: ' . $e->getMessage());
        }
        
        return back()->with('success', 'Thank you for your message! We\'ll get back to you soon.');
    }
}
