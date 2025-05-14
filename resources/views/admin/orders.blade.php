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
        .modal-footer {
            border-top: 1px solid #eee;
        }
        .btn-confirm {
            background-color: #5d1d48;
            border-color: #5d1d48;
            color: white;
        }
        .btn-confirm:hover {
            background-color: #4a1739;
            border-color: #4a1739;
            color: white;
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
        .btn-process {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
            padding: 0.375rem 0.75rem;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }
        .btn-process:hover {
            background-color: #218838;
            border-color: #1e7e34;
            color: white;
            transform: translateY(-1px);
        }
        .btn-process:active {
            transform: translateY(0);
        }
        .status-badge {
            padding: 0.5em 1em;
            border-radius: 0.25rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .status-processed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-paid {
            background-color: #cce5ff;
            color: #004085;
        }
        .btn-group {
            display: flex;
            gap: 0.5rem;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
            padding: 0.375rem 0.75rem;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
            color: white;
            transform: translateY(-1px);
        }
        .btn-danger:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>

    @include('components.admin_navbar')

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Status</th>
                <th>Payment Method</th>
                <th>Total</th>
                <th>Created</th>
                <th>Delivery Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ optional($order->user)->name ?? 'Guest' }}</td>
                    <td>
                        @if($order->status === 'cancelled')
                            <span class="status-badge status-cancelled">Cancelled</span>
                        @elseif($order->status === 'delivered')
                            <span class="status-badge status-processed">Delivered</span>
                        @elseif($order->status === 'processed')
                            <span class="status-badge status-processed">Processed</span>
                        @elseif($order->status === 'paid')
                            <span class="status-badge status-paid">Paid</span>
                        @elseif($order->status === 'pending')
                            <span class="status-badge status-pending">Pending</span>
                        @else
                            <span class="status-badge status-pending">{{ ucfirst($order->status) }}</span>
                        @endif
                    </td>
                    <td>{{ ucfirst($order->payment_method) }}</td>
                    <td>${{ number_format($order->total, 2) }}</td>
                    <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        @if ($order->delivery_date)
                            {{ \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if ($order->status === 'pending' || $order->status === 'paid')
                            <form action="{{ route('admin.orders.process', ['id' => $order->id]) }}" method="POST">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-success">Process Order</button>
                            </form>
                        @elseif($order->status === 'processed')
                            <span class="status-badge status-processed">Processed</span>
                        @elseif($order->status === 'delivered')
                            <span class="status-badge status-processed">Delivered</span>
                        @elseif($order->status === 'cancelled')
                            <span class="status-badge status-cancelled">Cancelled</span>
                        @else
                            <span class="status-badge status-pending">{{ ucfirst($order->status) }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
            </div>
        </div>

        <!-- Process Order Modals -->
        @foreach($orders as $order)
            @if($order->status === 'pending' || $order->status === 'paid')
                <div class="modal fade" id="confirmModal{{ $order->id }}" tabindex="-1" aria-labelledby="confirmModalLabel{{ $order->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmModalLabel{{ $order->id }}">Process Order #{{ $order->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.orders.process', ['id' => $order->id]) }}" method="POST" id="processForm{{ $order->id }}">
                                    @csrf
                                    @method('POST')
                                    
                                    <div class="mb-3">
                                        <h6>Order Details</h6>
                                        <p><strong>Customer:</strong> {{ optional($order->user)->name ?? 'Guest' }}</p>
                                        <p><strong>Email:</strong> {{ $order->email }}</p>
                                        <p><strong>Address:</strong> {{ $order->address }}</p>
                                        <p><strong>Total Amount:</strong> ${{ number_format($order->total, 2) }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <h6>Order Items</h6>
                                        <ul class="list-unstyled">
                                            @foreach($order->orderItems as $item)
                                                <li>
                                                    {{ $item->quantity }}x {{ $item->product->name }}
                                                    <small class="text-muted">
                                                        (Stock: {{ $item->product->stock }})
                                                    </small>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="mb-3">
                                        <label for="delivery_date{{ $order->id }}" class="form-label">Delivery Date</label>
                                        <input type="date" 
                                               class="form-control" 
                                               id="delivery_date{{ $order->id }}" 
                                               name="delivery_date" 
                                               value="{{ date('Y-m-d') }}"
                                               min="{{ date('Y-m-d') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="notes{{ $order->id }}" class="form-label">Processing Notes (Optional)</label>
                                        <textarea class="form-control" 
                                                  id="notes{{ $order->id }}" 
                                                  name="notes" 
                                                  rows="3"
                                                  placeholder="Add any notes about this order processing..."></textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" 
                                        form="processForm{{ $order->id }}" 
                                        class="btn btn-confirm">
                                    <i class="fas fa-check me-2"></i>Confirm Processing
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cancel Order Modal -->
                <div class="modal fade" id="cancelModal{{ $order->id }}" tabindex="-1" aria-labelledby="cancelModalLabel{{ $order->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="cancelModalLabel{{ $order->id }}">Cancel Order #{{ $order->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to cancel this order?</p>
                                <p><strong>Customer:</strong> {{ optional($order->user)->name ?? 'Guest' }}</p>
                                <p><strong>Total Amount:</strong> ${{ number_format($order->total, 2) }}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Keep Order</button>
                                <form action="{{ route('admin.orders.cancel', ['id' => $order->id]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-times me-2"></i>Yes, Cancel Order
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>
