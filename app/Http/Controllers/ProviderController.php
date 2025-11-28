<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    /**
     * Display a listing of providers.
     */
    public function index(Request $request)
    {
        $query = Provider::with(['services', 'reviews', 'appointments'])
            ->where('is_active', true);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('expertise', 'like', "%{$search}%")
                    ->orWhere('bio', 'like', "%{$search}%");
            });
        }

        $providers = $query->orderByDesc('average_rating')
            ->orderByDesc('total_reviews')
            ->paginate(12);

        return view('pages.providers.index', compact('providers'));
    }

    /**
     * Display the specified provider.
     */
    public function show(Provider $provider)
    {
        $provider->load(['services', 'reviews.user', 'reviews.appointment.service']);
        
        return view('pages.providers.show', compact('provider'));
    }
}
