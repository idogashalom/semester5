<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReportMail; // ✅ ADDED (needed for clean email usage)

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

        $report = \App\Models\Report::create(array_merge($validated, [
            'status' => 'processing',
        ]));

        \App\Jobs\GenerateReportJob::dispatch($report)->delay(now()->addSeconds(5));

        return back()->with('success', 'Report request received');
    }

    public function download($id, $type)
    {
        $report = \App\Models\Report::findOrFail($id);

        if ($report->status !== 'done' && $report->status !== 'completed') {
            return back()->with('error', 'Report is not done yet.');
        }

        $fileName = strtolower($type) === 'pdf' ? $report->file_pdf : $report->file_excel;

        if (empty($fileName)) {
            $fileName = $this->generateReportFile($report, strtolower($type));

            if (strtolower($type) === 'pdf') {
                $report->file_pdf = $fileName;
            } else {
                $report->file_excel = $fileName;
            }
            $report->save();
        }

        $directory = storage_path('app/reports');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $filePath = storage_path("app/reports/{$fileName}");

        if (!file_exists($filePath)) {
            return back()->with('error', 'The requested file could not be found on the server.');
        }

        return response()->download($filePath);
    }

    public function deleteSelected(Request $request)
    {
        $ids = $request->input('report_ids');

        if (!empty($ids)) {
            \App\Models\Report::whereIn('id', $ids)->delete();
            return back()->with('success', 'Selected reports deleted successfully.');
        }

        return back()->with('error', 'No reports selected for deletion.');
    }

    public function sendReport($id)
    {
        $report = \App\Models\Report::findOrFail($id);

        if (($report->status === 'done' || $report->status === 'completed') && empty($report->file_pdf) && empty($report->file_excel)) {
            $type = strtolower($report->format ?: 'pdf');
            $fileName = $this->generateReportFile($report, $type);
            if ($type === 'pdf') {
                $report->file_pdf = $fileName;
            } else {
                $report->file_excel = $fileName;
            }
            $report->save();
        } elseif (!$report->file_path && empty($report->file_pdf) && empty($report->file_excel)) {
            return back()->with('error', 'Report not ready');
        }

        // ✅ ADDED: email validation safety check
        if (empty($report->email) || !filter_var($report->email, FILTER_VALIDATE_EMAIL)) {
            return back()->with('error', 'Invalid or missing email address.');
        }

        try {
            Mail::to($report->email)->send(new ReportMail($report));
            return back()->with('success', 'Report sent successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    public function sendEmail($id)
    {
        $report = \App\Models\Report::findOrFail($id);

        if (($report->status === 'done' || $report->status === 'completed') && empty($report->file_pdf) && empty($report->file_excel)) {
            $type = strtolower($report->format ?: 'pdf');
            $fileName = $this->generateReportFile($report, $type);
            if ($type === 'pdf') {
                $report->file_pdf = $fileName;
            } else {
                $report->file_excel = $fileName;
            }
            $report->save();
        }

        // ✅ ADDED: email validation safety check
        if (empty($report->email) || !filter_var($report->email, FILTER_VALIDATE_EMAIL)) {
            return back()->with('error', 'Invalid or missing email address.');
        }

        try {
            Mail::to($report->email)->send(new ReportMail($report));
            return back()->with('success', 'Report sent to email successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    private function generateReportFile(\App\Models\Report $report, $type)
    {
        $directory = storage_path('app/reports');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $fileName = 'report_' . $report->id . '_' . time() . '.' . ($type === 'pdf' ? 'pdf' : 'xlsx');
        $filePath = $directory . '/' . $fileName;

        if ($type === 'pdf') {
            $defaultPdf = storage_path('app/default-reports/Bank_Report_Sample.pdf');
            if (file_exists($defaultPdf)) {
                copy($defaultPdf, $filePath);
            }
        } else {
            $defaultExcel = storage_path('app/default-reports/Bank_Report_Sample.xlsx');
            if (file_exists($defaultExcel)) {
                try {
                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($defaultExcel);
                    $sheet = $spreadsheet->getActiveSheet();

                    $sheet->setCellValue('A1', 'Bank Report #' . $report->id);

                    $data = [
                        ['Name', $report->name],
                        ['Email', $report->email],
                        ['Start Date', $report->start_date],
                        ['End Date', $report->end_date],
                        ['Transaction Type', ucfirst($report->transaction_type)],
                        ['Format', $report->format ?? 'N/A'],
                        ['Description', $report->description ?? 'N/A'],
                        ['Status', 'Done'],
                        ['Created At', $report->created_at ? $report->created_at->format('Y-m-d H:i:s') : ''],
                    ];

                    $row = 4;
                    foreach ($data as $item) {
                        $sheet->setCellValue('A' . $row, $item[0]);
                        $sheet->setCellValue('B' . $row, $item[1]);
                        $row++;
                    }

                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    $writer->save($filePath);
                } catch (\Exception $e) {
                    copy($defaultExcel, $filePath);
                }
            }
        }

        return $fileName;
    }
}
