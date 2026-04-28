<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

        if (empty($report->email)) {
            return back()->with('error', 'Cannot send email: Customer email is missing.');
        }

        try {
            Mail::to($report->email)->send(new \App\Mail\ReportMail($report));
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

        if (empty($report->email)) {
            return back()->with('error', 'Cannot send email: Customer email is missing.');
        }

        try {
            Mail::to($report->email)->send(new \App\Mail\ReportMail($report));
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
            $html = '<div style="font-family: sans-serif; padding: 20px;">
                <h1 style="color: #667eea;">Bank Report #' . $report->id . '</h1>
                <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                    <tr><th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd; width: 30%;">Name:</th><td style="padding: 8px; border-bottom: 1px solid #ddd;">' . htmlspecialchars($report->name ?? '') . '</td></tr>
                    <tr><th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Email:</th><td style="padding: 8px; border-bottom: 1px solid #ddd;">' . htmlspecialchars($report->email ?? '') . '</td></tr>
                    <tr><th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Start Date:</th><td style="padding: 8px; border-bottom: 1px solid #ddd;">' . htmlspecialchars($report->start_date ?? '') . '</td></tr>
                    <tr><th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">End Date:</th><td style="padding: 8px; border-bottom: 1px solid #ddd;">' . htmlspecialchars($report->end_date ?? '') . '</td></tr>
                    <tr><th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Transaction Type:</th><td style="padding: 8px; border-bottom: 1px solid #ddd;">' . htmlspecialchars(ucfirst($report->transaction_type ?? '')) . '</td></tr>
                    <tr><th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Format:</th><td style="padding: 8px; border-bottom: 1px solid #ddd;">' . htmlspecialchars($report->format ?? 'N/A') . '</td></tr>
                    <tr><th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Description:</th><td style="padding: 8px; border-bottom: 1px solid #ddd;">' . htmlspecialchars($report->description ?? 'N/A') . '</td></tr>
                    <tr><th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Status:</th><td style="padding: 8px; border-bottom: 1px solid #ddd;">' . htmlspecialchars(ucfirst($report->status ?? '')) . '</td></tr>
                    <tr><th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Created At:</th><td style="padding: 8px; border-bottom: 1px solid #ddd;">' . htmlspecialchars($report->created_at ? $report->created_at->format('Y-m-d H:i:s') : '') . '</td></tr>
                </table>
            </div>';

            $dompdf = new \Dompdf\Dompdf();
            $dompdf->setOptions(new \Dompdf\Options(['isHtml5ParserEnabled' => true]));
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            file_put_contents($filePath, $dompdf->output());
        } else {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'Bank Report #' . $report->id);
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);

            $headers = ['Field', 'Value'];
            $sheet->setCellValue('A3', $headers[0]);
            $sheet->setCellValue('B3', $headers[1]);
            $sheet->getStyle('A3:B3')->getFont()->setBold(true);

            $data = [
                ['Name', $report->name],
                ['Email', $report->email],
                ['Start Date', $report->start_date],
                ['End Date', $report->end_date],
                ['Transaction Type', ucfirst($report->transaction_type)],
                ['Format', $report->format ?? 'N/A'],
                ['Description', $report->description ?? 'N/A'],
                ['Status', ucfirst($report->status)],
                ['Created At', $report->created_at ? $report->created_at->format('Y-m-d H:i:s') : ''],
            ];

            $row = 4;
            foreach ($data as $item) {
                $sheet->setCellValue('A' . $row, $item[0]);
                $sheet->setCellValue('B' . $row, $item[1]);
                $row++;
            }

            foreach (range('A', 'B') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($filePath);
        }

        return $fileName;
    }
}
