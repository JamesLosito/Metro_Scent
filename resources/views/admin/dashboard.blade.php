@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Admin Dashboard</h4>
                </div>
                <div class="card-body">
                    <p class="lead">Welcome, Admin! Use the links below to manage the site.</p>
                    <ul class="list-group">
                        <li class="list-group-item"><a href="{{ route('admin.bestsellers.index') }}">Manage Bestsellers</a></li>
                        <!-- Add more admin links here as needed -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 