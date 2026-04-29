<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f3f4f6; min-height: 100vh; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        h1 { color: #111827; font-size: 2rem; }
        .nav-links a { color: #4f46e5; text-decoration: none; font-weight: 600; margin-left: 15px; }
        .nav-links a:hover { text-decoration: underline; }
        
        .card { background: white; border-radius: 10px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background-color: #f9fafb; color: #374151; font-weight: 600; text-transform: uppercase; font-size: 0.8rem; }
        td { color: #4b5563; font-size: 0.95rem; }
        tr:hover { background-color: #f9fafb; }
        
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 9999px; font-size: 0.8rem; font-weight: 600; text-transform: capitalize; }
        .status-processing { background-color: #fef3c7; color: #92400e; }
        .status-done { background-color: #d1fae5; color: #065f46; }
        
        .btn { padding: 6px 12px; border: none; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.2s; }
        .btn-primary { background-color: #4f46e5; color: white; }
        .btn-primary:hover { background-color: #4338ca; }
        
        .status-form { display: flex; gap: 8px; align-items: center; }
        select { padding: 6px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.85rem; }
        
        .toast { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; }
        .toast-success { background-color: #d1fae5; color: #065f46; border: 1px solid #34d399; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-bar">
            <h1>Admin Dashboard</h1>
            <div class="nav-links">
                <a href="{{ route('reports.index') }}">Go to Reports Page</a>
            </div>
        </div>

        @if (session('success'))
            <div class="toast toast-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <h2>Manage Report Requests</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer Name</th>
                        <th>Email</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Type</th>
                        <th>Format</th>
                        <th>Description</th>
                        <th>Created Date</th>
                        <th>Current Status</th>
                        <th>Change Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>#{{ $report->id }}</td>
                            <td>{{ $report->name }}</td>
                            <td>{{ $report->email }}</td>
                            <td>{{ $report->start_date }}</td>
                            <td>{{ $report->end_date }}</td>
                            <td>{{ ucfirst($report->transaction_type) }}</td>
                            <td>{{ $report->format }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($report->description, 20) ?: '—' }}</td>
                            <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <span class="status-badge status-{{ strtolower($report->status) == 'completed' ? 'done' : strtolower($report->status) }}">
                                    {{ $report->status }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.status.update', $report->id) }}" method="POST" class="status-form">
                                    @csrf
                                    <select name="status">
                                        <option value="processing" {{ $report->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="done" {{ in_array(strtolower($report->status), ['done', 'completed']) ? 'selected' : '' }}>Done</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" style="text-align: center; padding: 20px;">No report requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
