<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\mongoDB\Faq;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\FaqRequest;
use App\Services\SitemapService;

class FaqController extends Controller
{

    public function __construct(private Faq $faq) {}

    public function index(FaqRequest $request)
    {

        $query = $this->faq->newQuery();

        $records = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();


        if ($request->expectsJson()) {
            $formHtml = view('components.admin.faqs.form', [
                'faq' => null,
            ])->render();

            $tableHtml = view('components.admin.faqs.list', [
                'records' => $records,
            ])->render();

            return response()->json([
                'form' => $formHtml,
                'table' => $tableHtml,
            ]);
        }

        return view('admin.faqs.index', [
            'records' => $records,
        ]);
    }

    public function show(Request $request, Faq $faq)
    {

        if ($request->expectsJson()) {
            $formHtml = view('components.admin.faqs.form', [
                'faq' => $faq,
            ])->render();

            return response()->json([
                'form' => $formHtml,
            ]);
        }
    }


    public function store(FaqRequest $request)
    {

        try {
            $data = $request->all();
            $faq = Faq::create($data);


            return response()->json(['id' => $faq->_id], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(FaqRequest $request, Faq $faq)
    {
        $data = $request->all();
        $data['_id'] = $request->input('id');

        $faq->update($data);




        return response()->json(['id' => $faq->id]);
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return response()->json(['ok' => true]);
    }
}
