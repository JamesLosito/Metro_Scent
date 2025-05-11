@php
    $folder = strtoupper($product->type);
    $imagePath = 'images/' . $folder . '/' . $product->image;
@endphp

<div class="col-md-4">
    <div class="product-card">
        <img src="{{ asset($imagePath) }}" alt="{{ $product->name }}" class="product-img">
        <h5 class="mt-3">{{ $product->name }}</h5>
        <h6 class="text-muted">{{ $product->price }} PHP</h6>
        <p>{{ $product->description }}</p>
        @auth
        <form method="POST" action="{{ url('/cart/add') }}">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->product_id }}">
            <button type="submit" class="btn btn-primary mt-3">Add to Cart</button>
        </form>
        @else
        <a href="{{ url('/register') }}" class="btn btn-primary mt-3">Add to Cart</a>
        @endauth
    </div>
</div>
