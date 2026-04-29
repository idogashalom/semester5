<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;

class AdminController extends Controller
{
    public function index()
    {
        $reports = Report::latest()->get();
        return view('admin.index', compact('reports'));
    }

    public function updateStatus(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:processing,done',
        ]);

        $newStatus = $request->input('status');
        
        if ($newStatus === 'done' && $report->status !== 'done') {
            $this->assignDefaultReports($report);
        }

        $report->status = $newStatus;
        $report->save();

        return back()->with('success', 'Report status updated successfully.');
    }

    private function assignDefaultReports(Report $report)
    {
        $directory = storage_path('app/reports');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $baseName = 'report_' . $report->id . '_' . time();
        $pdfFileName = $baseName . '.pdf';
        $excelFileName = $baseName . '.xlsx';
        
        $targetPdf = $directory . '/' . $pdfFileName;
        $targetExcel = $directory . '/' . $excelFileName;
        
        // Use default files
        $defaultPdf = storage_path('app/default-reports/Bank_Report_Sample.pdf');
        $defaultExcel = storage_path('app/default-reports/Bank_Report_Sample.xlsx');
        
        if (file_exists($defaultPdf)) {
            copy($defaultPdf, $targetPdf);
            $report->file_pdf = $pdfFileName;
        }

        if (file_exists($defaultExcel)) {
            // Populate Excel if possible using PhpSpreadsheet
            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($defaultExcel);
                $sheet = $spreadsheet->getActiveSheet();
                
                // Assuming we can write to specific cells without breaking the template
                // We'll write to cell A1 as title, and A3-B11 with data to populate report details
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
                $writer->save($targetExcel);
            } catch (\Exception $e) {
                // If anything fails, simply copy the base file
                copy($defaultExcel, $targetExcel);
            }
            $report->file_excel = $excelFileName;
        }
        
        $report->save();
    }
}
