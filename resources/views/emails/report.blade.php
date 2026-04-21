<div style="font-family: Segoe UI, Tahoma, Geneva, Verdana, sans-serif; max-width: 600px; margin: 0 auto;">
    <div
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">Bank Report</h1>
    </div>

    <div style="background: white; padding: 30px; border-radius: 0 0 8px 8px; border: 1px solid #e0e0e0;">
        <p style="color: #333; font-size: 16px; line-height: 1.6;">
            Hello <strong>{{ $report->name }}</strong>,
        </p>

        <p style="color: #555; font-size: 15px; line-height: 1.6;">
            Your requested bank report has been generated and is ready for download.
            The report covers transactions from <strong>{{ $report->start_date }}</strong> to
            <strong>{{ $report->end_date }}</strong>.
        </p>

        <div
            style="background: #f9f9f9; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #667eea;">
            <p style="margin: 5px 0; color: #666; font-size: 14px;">
                <strong>Report Details:</strong>
            </p>
            <p style="margin: 5px 0; color: #666; font-size: 14px;">
                Format: <strong>{{ $report->format }}</strong>
            </p>
            <p style="margin: 5px 0; color: #666; font-size: 14px;">
                Transaction Type: <strong>{{ ucfirst($report->transaction_type) }}</strong>
            </p>
            @if ($report->description)
                <p style="margin: 5px 0; color: #666; font-size: 14px;">
                    Description: <strong>{{ $report->description }}</strong>
                </p>
            @endif
        </div>

        <p style="color: #555; font-size: 15px; line-height: 1.6;">
            The report is attached to this email. If you have any questions or need further assistance,
            please contact our support team.
        </p>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0; text-align: center;">
            <p style="color: #999; font-size: 12px; margin: 0;">
                Best regards,<br>
                <strong>Bank Reports Team</strong>
            </p>
        </div>
    </div>
</div>
