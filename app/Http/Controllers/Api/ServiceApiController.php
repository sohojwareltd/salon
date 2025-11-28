<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceApiController extends Controller
{
    /**
     * Display a listing of services
     */
    public function index(Request $request)
    {
        $query = Service::where('is_active', true);
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $services = $query->orderBy('name')->get();
        
        return response()->json([
            'success' => true,
            'data' => $services,
        ]);
    }

    /**
     * Display the specified service
     */
    public function show(string $id)
    {
        $service = Service::with('providers')
            ->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $service,
        ]);
    }
}
