@extends('components.navbar')

@section('content')
<div class="container">
    <h2>Product Management</h2>

    <!-- Add Product Form -->
    <form method="POST" action="{{ route('admin.products.store') }}">
        @csrf
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="text" name="price" placeholder="Price" required>
        <textarea name="description" placeholder="Description"></textarea>
        <button type="submit">Add Product</button>
    </form>

    <!-- Product List -->
    <table class="table mt-4">
        <thead>
            <tr><th>Name</th><th>Price</th><th>Description</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <form method="POST" action="{{ route('admin.products.update', $product->id) }}">
                        @csrf @method('PUT')
                        <td><input type="text" name="name" value="{{ $product->name }}"></td>
                        <td><input type="text" name="price" value="{{ $product->price }}"></td>
                        <td><input type="text" name="description" value="{{ $product->description }}"></td>
                        <td>
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </form>
                    <form method="POST" action="{{ route('admin.products.delete', $product->id) }}" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
