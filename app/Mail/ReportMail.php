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

        $fileName = $this->report->file_pdf ?? $this->report->file_excel;
        
        if (!empty($fileName)) {
            $filePath = storage_path("app/reports/{$fileName}");
            if (file_exists($filePath)) {
                $mail->attach($filePath);
            }
        }

        return $mail;
    }
}
