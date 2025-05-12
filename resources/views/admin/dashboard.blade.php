@extends('components.navbar')

@section('content')
<div class="container">
  <h1>Admin Dashboard</h1>

  <div class="row mt-4">
    <div class="col-md-4">
      <div class="card p-3">
        <h5>Total Users</h5>
        <p class="display-4">{{ $usersCount }}</p>
        <a href="{{ route('admin.users') }}">Manage Users</a>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card p-3">
        <h5>Total Products</h5>
        <p class="display-4">{{ $productsCount }}</p>
        <a href="{{ route('admin.products') }}">Manage Products</a>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card p-3">
        <h5>Pending Orders</h5>
        <p class="display-4">{{ $ordersPending }}</p>
        <a href="{{ route('admin.orders') }}">Manage Orders</a>
      </div>
    </div>
  </div>
</div>
@endsection
