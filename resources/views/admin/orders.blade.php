<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Metro Essence - User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        body {
            font-family: 'Times New Roman', serif;
            background-color: #fff;
            color: #333;
        }
        .navbar {
            background-color: #fff;
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }
        .navbar-brand {
            font-family: 'Times New Roman', serif;
            font-weight: 300;
            letter-spacing: 2px;
            color: #5d1d48 !important;
        }
        .nav-link {
            color: #5d1d48 !important;
            font-size: 0.9rem;
            letter-spacing: 1px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .btn-primary {
            background-color: #5d1d48;
            border-color: #5d1d48;
        }
        .btn-primary:hover {
            background-color: #4a1739;
            border-color: #4a1739;
        }
        .btn-outline-primary {
            color: #5d1d48;
            border-color: #5d1d48;
        }
        .btn-outline-primary:hover {
            background-color: #5d1d48;
            border-color: #5d1d48;
        }
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #eee;
            color: #5d1d48;
            font-weight: 600;
        }
        .table td {
            vertical-align: middle;
        }
        .modal-content {
            border: none;
            border-radius: 10px;
        }
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
        }
        .modal-title {
            color: #5d1d48;
            font-weight: 600;
        }
        footer {
            background-color: #f8f8f8;
            padding: 30px 0;
            margin-top: 50px;
            border-top: 1px solid #eee;
        }
        .footer-links {
            list-style: none;
            padding: 0;
        }
        .footer-links li {
            margin-bottom: 10px;
        }
        .footer-links a {
            color: #5d1d48;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .social-icons {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .social-icons li {
            margin: 0 10px;
        }
        .social-icons a {
            color: #5d1d48;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

    @include('components.admin_navbar')

    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>User</th>
                <th>Status</th>
                <th>Delivery Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>{{ optional($order->user)->name ?? 'Guest' }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                        <td>
                            @if ($order->delivery_date)
                                {{ \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }}
                            @else
                                N/A
                            @endif
                          <td>
                                @if ($order->status === 'pending')
                                    {{-- Process Order --}}
                                    <form action="{{ route('admin.orders.process', ['id' => $order->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success mb-2">Process Order</button>
                                    </form>
                                @elseif ($order->status === 'processed')
                                    {{-- Mark as In Transit --}}
                                    <form action="{{ route('admin.orders.intransit', ['id' => $order->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning mb-2">Mark as In Transit</button>
                                    </form>
                                @elseif ($order->status === 'intransit')
                                    {{-- Mark as Delivered --}}
                                    <form action="{{ route('admin.orders.delivered', ['id' => $order->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Mark as Delivered</button>
                                    </form>
                                @elseif ($order->status === 'delivered')
                                    <span class="text-success">Delivered</span>
                                @else
                                    <span class="text-muted">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>

                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
