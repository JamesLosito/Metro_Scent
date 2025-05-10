@extends('layouts.app') {{-- or include your navbar/footer manually --}}

@section('content')
<div class="container mt-5">
    <h2 class="section-title">Your Cart</h2>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (count($cart) > 0)
        <ul class="list-group">
            @foreach ($cart as $item)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $item['name'] }} - ₱{{ number_format($item['price'], 2) }} x {{ $item['quantity'] }}
                    <span>₱{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                </li>
            @endforeach
        </ul>
    @else
        <p>Your cart is empty.</p>
    @endif
</div>
@endsection
