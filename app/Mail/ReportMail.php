<?php

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Report $report) {}

    public function build()
    {
        $mail = $this->subject('Your Generated Report')
            ->view('emails.report');

        $format = strtolower($this->report->format ?? '');
        $fileName = null;

        if ($format === 'excel' && !empty($this->report->file_excel)) {
            $fileName = $this->report->file_excel;
        } elseif (($format === 'pdf' || empty($format)) && !empty($this->report->file_pdf)) {
            $fileName = $this->report->file_pdf;
        } else {
            $fileName = $this->report->file_pdf ?? $this->report->file_excel;
        }

        if (!empty($fileName)) {
            $filePath = storage_path("app/reports/{$fileName}");
            if (file_exists($filePath)) {
                $mail->attach($filePath);
            }
        }

        // ✅ ADDED: fallback protection (prevents silent email failures)
        // ensures email is always properly formatted before sending
        if (empty($this->report->email) || !filter_var($this->report->email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Invalid recipient email address.");
        }

        return $mail;
    }
}
