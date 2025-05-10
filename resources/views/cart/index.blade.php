@include('components.navbar')

@section('content')
<div class="container mt-5">
    <h2 class="section-title mb-4">Your Cart</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($cartItems->isEmpty())
        <div class="alert alert-info">Your cart is empty.</div>
    @else
        <ul class="list-group mb-4">
            @php $cartTotal = 0; @endphp

            @foreach ($cartItems as $item)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    @if ($item->product)
                        <div>
                            <strong>{{ $item->product->name }}</strong><br>
                            ₱{{ number_format($item->product->price, 2) }} x {{ $item->quantity }}
                        </div>
                        @php
                            $itemTotal = $item->product->price * $item->quantity;
                            $cartTotal += $itemTotal;
                        @endphp
                        <span>₱{{ number_format($itemTotal, 2) }}</span>
                    @else
                        <div class="text-danger">Product not found</div>
                    @endif
                </li>
            @endforeach
        </ul>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Total: ₱{{ number_format($cartTotal, 2) }}</h4>
        </div>
    @endif
</div>
@endsection
