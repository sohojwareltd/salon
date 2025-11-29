<?php

namespace App\Http\Controllers;

use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::active()->ordered()->get();
        
        return view('faqs.index', compact('faqs'));
    }
}
