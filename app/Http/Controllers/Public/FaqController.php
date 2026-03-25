<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\mongoDB\Faq;
use Illuminate\Http\Request;
use App\Services\SitemapService;

class FaqController extends Controller
{
    public function __construct(private Faq $faq) {}


    public function index(Request $request)
    {

        $query = Faq::query();

        // records = paginación
        $records = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();


        return view('public.faqs', [
            'records' => $records
        ]);
    }


    public function show(Faq $faq)
    {
        return response()->json([
            'id' => $faq->id,
            'title' => $faq->title,
            'description' => $faq->description,
        ]);
    }
}
