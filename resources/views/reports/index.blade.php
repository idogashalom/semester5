<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            color: white;
            margin-bottom: 40px;
            font-size: 2.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        /* Form Card Styling */
        .form-card {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            margin-bottom: 40px;
        }

        .form-group {
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 0.95rem;
        }

        input[type="date"],
        select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f9f9f9;
        }

        input[type="date"]:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        button {
            grid-column: 1 / -1;
            padding: 14px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        button:active {
            transform: translateY(0);
        }

        /* Tables */
        .table-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #f5f5f5;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e0e0e0;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            color: #555;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        /* Toast Notification */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            animation: slideInRight 0.4s ease;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .toast {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            min-width: 300px;
            border-left: 4px solid #10b981;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .toast.success {
            border-left-color: #10b981;
        }

        .toast-content {
            flex: 1;
        }

        .toast-message {
            color: #333;
            font-size: 0.95rem;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .toast-button {
            padding: 6px 14px;
            background: #10b981;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .toast-button:hover {
            background: #059669;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.4);
        }

        .toast-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #999;
            cursor: pointer;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.3s ease;
        }

        .toast-close:hover {
            color: #333;
        }

        .toast.hide {
            animation: slideOutRight 0.4s ease forwards;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bank Reports</h1>

        <!-- Toast Notification -->
        @if(session('success'))
            <div class="toast-container" id="toastContainer">
                <div class="toast success" id="toast">
                    <div class="toast-content">
                        <div class="toast-message">{{ session('success') }}</div>
                        <a href="{{ route('reports.index') }}" class="toast-button">View Reports</a>
                    </div>
                    <button class="toast-close" onclick="closeToast()">&times;</button>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="form-card">
            <h2>Generate New Report</h2>
            <form action="{{ route('reports.generate') }}" method="POST">
                @csrf
                <div class="form-group">
                    <div>
                        <label for="start_date">Start Date:</label>
                        <input type="date" name="start_date" id="start_date" required>
                    </div>
                    <div>
                        <label for="end_date">End Date:</label>
                        <input type="date" name="end_date" id="end_date" required>
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label for="transaction_type">Transaction Type:</label>
                        <select name="transaction_type" id="transaction_type" required>
                            <option value="">-- Select Transaction Type --</option>
                            <option value="deposit">Deposit</option>
                            <option value="withdrawal">Withdrawal</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>
                    <div>
                        <label for="format">Format:</label>
                        <select name="format" id="format" required>
                            <option value="">-- Select Format --</option>
                            <option value="PDF">PDF</option>
                            <option value="Excel">Excel</option>
                        </select>
                    </div>
                </div>

                <div class="form-group full">
                    <label for="description">Description (optional):</label>
                    <textarea name="description" id="description" placeholder="Enter description..." rows="4" style="width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 1rem; background-color: #f9f9f9; transition: border-color 0.3s ease;"></textarea>
                </div>

                <div class="form-group full">
                    <button type="submit">Generate Report</button>
                </div>
            </form>
        </div>

        <!-- Reports Table -->
        <div class="table-card">
            <h2>Generated Reports</h2>
            <table>
                <thead>
                    <tr>
                        <th>Report ID</th>
                        <th>Status</th>
                        <th>Format</th>
                        <th>Description</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>#{{ $report->id }}</td>
                            <td>
                                <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;
                                    @if($report->status === 'completed')
                                        background-color: #d1fae5; color: #065f46;
                                    @elseif($report->status === 'processing')
                                        background-color: #fef3c7; color: #92400e;
                                    @else
                                        background-color: #e5e7eb; color: #374151;
                                    @endif
                                ">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </td>
                            <td>{{ $report->format }}</td>
                            <td>{{ $report->description ?? '—' }}</td>
                            <td>{{ $report->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                @if($report->status === 'completed')
                                    <a href="{{ route('reports.download', $report->id) }}">⬇ Download</a>
                                @else
                                    <span style="color: #999;">Processing...</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 20px; text-align: center; color: #666;">No reports have been generated yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function closeToast() {
            const toast = document.getElementById('toast');
            if (toast) {
                toast.classList.add('hide');
                setTimeout(() => {
                    const container = document.getElementById('toastContainer');
                    if (container) {
                        container.remove();
                    }
                }, 400);
            }
        }

        // Auto-hide toast after 5 seconds
        function autoHideToast() {
            const container = document.getElementById('toastContainer');
            if (container) {
                setTimeout(() => {
                    closeToast();
                }, 5000);
            }
        }

        // Initialize toast on page load
        if (document.getElementById('toastContainer')) {
            autoHideToast();
        }
    </script>
</body>
</html>