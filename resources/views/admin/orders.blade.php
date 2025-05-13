@include('components.admin_navbar')

@section('content')
<div class="container">
    <h2>Order Management</h2>

    <table class="table">
        <thead>
            <tr><th>User</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td>{{ $order->user->full_name }}</td>
                <td>{{ $order->status }}</td>
                <td>
                    @if ($order->status !== 'processed')
                    <form method="POST" action="{{ route('admin.orders.process', $order->id) }}">
                        @csrf @method('PUT')
                        <button class="btn btn-success btn-sm">Mark as Processed</button>
                    </form>
                    @else
                    <span class="text-success">Processed</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
