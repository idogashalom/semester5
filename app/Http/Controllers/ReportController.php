<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reports = \App\Models\Report::latest()->get();
        return view('reports.index', compact('reports'));
    }

    public function generate(Request $request)
    {
        return back()->with('success', 'Report request received');
    }

    public function download($id)
    {
        $report = \App\Models\Report::findOrFail($id);
        return response()->download(storage_path('app/' . $report->file_path));
    }
}
