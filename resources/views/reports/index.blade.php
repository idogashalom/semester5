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
            background: white;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            color: #333;
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
        input[type="text"],
        input[type="email"],
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
        input[type="text"]:focus,
        input[type="email"]:focus,
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

        /* Button Styling */
        .btn {
            display: inline-block;
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-right: 8px;
            margin-bottom: 6px;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        .btn-primary {
            background-color: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background-color: #5568d3;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-success {
            background-color: #10b981;
            color: white;
        }

        .btn-success:hover {
            background-color: #059669;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .btn-warning {
            background-color: #f59e0b;
            color: white;
        }

        .btn-warning:hover {
            background-color: #d97706;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
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
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
            <h1 style="margin-bottom: 0;">Bank Reports</h1>
            <a href="{{ route('admin.index') }}" class="btn btn-primary" style="padding: 10px 20px; font-size: 1rem;">Admin Dashboard</a>
        </div>
        <!-- Toast Notification -->
        @if (session('success'))
            <div class="toast-container" id="toastContainer">
                <div class="toast success" id="toast">
                    <div class="toast-content">
                        <div class="toast-message">{{ session('success') }}</div>
                        <a href="{{ route('reports.index') }}" class="toast-button">View Reports</a>
                    </div>
                    <button class="toast-close" onclick="closeToast('toastContainer', 'toast')">&times;</button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="toast-container" id="toastErrorContainer" style="top: 90px;">
                <div class="toast" id="toastError" style="border-left-color: #ef4444;">
                    <div class="toast-content">
                        <div class="toast-message" style="color: #ef4444;">{{ session('error') }}</div>
                    </div>
                    <button class="toast-close" onclick="closeToast('toastErrorContainer', 'toastError')">&times;</button>
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
                        <label for="name">Customer Name:</label>
                        <input type="text" name="name" id="name" placeholder="Enter customer name" required
                            style="width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 1rem; background-color: #f9f9f9; transition: all 0.3s ease;">
                    </div>
                    <div>
                        <label for="email">Customer Email:</label>
                        <input type="email" name="email" id="email" placeholder="Enter customer email" required
                            style="width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 1rem; background-color: #f9f9f9; transition: all 0.3s ease;">
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
                    <textarea name="description" id="description" placeholder="Enter description..." rows="4"
                        style="width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 1rem; background-color: #f9f9f9; transition: border-color 0.3s ease;"></textarea>
                </div>

                <div class="form-group full">
                    <button type="submit">Generate Report</button>
                </div>
            </form>
        </div>

        <!-- Reports Table -->
        <div class="table-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin-bottom: 0;">Generated Reports</h2>
                <button type="submit" form="deleteForm" class="btn" style="background-color: #ef4444; color: white;" onclick="return confirm('Are you sure you want to delete selected reports?')">Delete Selected</button>
            </div>
            <form id="deleteForm" action="{{ route('reports.deleteSelected') }}" method="POST">
                @csrf
                <table>
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;"><input type="checkbox" onclick="document.querySelectorAll('.report-checkbox').forEach(cb => cb.checked = this.checked)"></th>
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
                            <td style="text-align: center;"><input type="checkbox" name="report_ids[]" value="{{ $report->id }}" class="report-checkbox"></td>
                            <td>#{{ $report->id }}</td>
                            <td>
                                @php
                                    $statusStyle = 'background-color: #e5e7eb; color: #374151;';
                                    if ($report->status === 'completed' || $report->status === 'done') {
                                        $statusStyle = 'background-color: #d1fae5; color: #065f46;';
                                    } elseif ($report->status === 'processing') {
                                        $statusStyle = 'background-color: #fef3c7; color: #92400e;';
                                    }
                                @endphp
                                <span style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; text-transform: capitalize; {{ $statusStyle }}">        
                                    {{ $report->status }}
                                </span>
                            </td>
                            <td>{{ $report->format }}</td>
                            <td>{{ $report->description ?? '—' }}</td>
                            <td>{{ $report->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                <!-- SINGLE DOWNLOAD BUTTON WITH DROPDOWN -->
                                <div style="position: relative; display: inline-block;" class="dropdown-container">
                                    <button type="button" class="btn btn-sm btn-primary" onclick="toggleDropdown('{{ $report->id }}')" style="margin-bottom: 0;">
                                        Download &#9662;
                                    </button>
                                    <div id="dropdown-{{ $report->id }}" class="dropdown-menu" style="display: none; position: absolute; background-color: #fff; min-width: 120px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 10; border-radius: 6px; overflow: hidden; top: 100%; left: 0; border: 1px solid #eee;">
                                        <a href="{{ route('reports.download', ['id' => $report->id, 'type' => 'pdf']) }}" style="display: block; padding: 8px 12px; font-weight: normal; color: #333; border-bottom: 1px solid #f0f0f0;">PDF</a>
                                        <a href="{{ route('reports.download', ['id' => $report->id, 'type' => 'excel']) }}" style="display: block; padding: 8px 12px; font-weight: normal; color: #333;">Excel</a>
                                    </div>
                                </div>

                                <!-- SEND EMAIL BUTTON -->
                                <a href="{{ route('reports.email', ['id' => $report->id]) }}"
                                    class="btn btn-sm btn-warning" style="margin-bottom: 0; background-color: #f59e0b; color: white; decoration: none;">
                                    Send to Email
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 20px; text-align: center; color: #666;">No reports have
                                been generated yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </form>
        </div>
    </div>

    <script>
        function closeToast(containerId = 'toastContainer', toastId = 'toast') {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.classList.add('hide');
                setTimeout(() => {
                    const container = document.getElementById(containerId);
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
                    closeToast('toastContainer', 'toast');
                }, 5000);
            }
            
            const errorContainer = document.getElementById('toastErrorContainer');
            if (errorContainer) {
                setTimeout(() => {
                    closeToast('toastErrorContainer', 'toastError');
                }, 5000);
            }
        }

        // Initialize toast on page load
        autoHideToast();

        function toggleDropdown(id) {
            const dropdown = document.getElementById('dropdown-' + id);
            const isVisible = dropdown.style.display === 'block';

            document.querySelectorAll('.dropdown-menu').forEach(el => el.style.display = 'none');

            if (!isVisible) {
                dropdown.style.display = 'block';
            }
        }

        window.onclick = function(event) {
            if (!event.target.matches('.btn-primary') && !event.target.closest('.dropdown-container')) {
                document.querySelectorAll('.dropdown-menu').forEach(el => el.style.display = 'none');
            }
        }
    </script>
</body>

</html>
