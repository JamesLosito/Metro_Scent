@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            @php
                $folder = strtoupper($product->type);
                $imagePath = 'images/' . $folder . '/' . $product->image;
            @endphp
            <img src="{{ asset($imagePath) }}" alt="{{ $product->name }}" class="img-fluid rounded shadow">
        </div>
        <div class="col-md-6">
            <h2>{{ $product->name }}</h2>
            <h4 class="text-muted">{{ $product->price }} PHP</h4>
            <p>{{ $product->description }}</p>

            @auth
                <form method="POST" action="{{ url('/cart/add') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                    <button type="submit" class="btn btn-primary mt-3">Add to Cart</button>
                    <a href="{{ url('/checkout') }}" class="btn btn-success mt-3">Checkout Now</a>
                </form>
            @else
                <a href="{{ url('/login') }}" class="btn btn-primary mt-3">Login to Purchase</a>
            @endauth
        </div>
    </div>
</div>
@endsection
