<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of services.
     */
    public function index(Request $request)
    {
        $query = Service::with(['providers'])
            ->where('is_active', true);

        // Search functionality (optional, for future)
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Category filter (optional, for future)
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        $services = $query->orderBy('name', 'asc')->paginate(12);

        return view('pages.services.index', compact('services'));
    }
}
