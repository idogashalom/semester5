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
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'transaction_type' => 'required|string|max:255',
            'format' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        \App\Models\Report::create(array_merge($validated, [
            'status' => 'processing',
        ]));

        return back()->with('success', 'Report request received');
    }

    public function download($id)
    {
        $report = \App\Models\Report::findOrFail($id);
        return response()->download(storage_path('app/' . $report->file_path));
    }

    public function sendReport($id)
    {
        $report = \App\Models\Report::findOrFail($id);

        if (!$report->file_path) {
            return back()->with('error', 'Report not ready');
        }

        \Mail::to($report->email)->send(new \App\Mail\ReportMail($report));

        return back()->with('success', 'Report sent successfully');
    }
}
