@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Order Management</h2>

    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>User</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <!-- Fetch the full_name of the user or display 'Guest' if not available -->
                    <td>{{ optional($order->user)->full_name ?? 'Guest' }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>
                        @if ($order->status !== 'processed')
                            <form method="POST" action="{{ route('admin.orders.process', $order->id) }}">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-success btn-sm">Mark as Processed</button>
                            </form>
                        @else
                            <span class="text-success">Processed</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">No orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
