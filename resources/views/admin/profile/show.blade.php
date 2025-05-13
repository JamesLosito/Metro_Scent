@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Admin Profile</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <h5>Name</h5>
                        <p>{{ $user->name }}</p>
                    </div>

                    <div class="mb-3">
                        <h5>Email</h5>
                        <p>{{ $user->email }}</p>
                    </div>

                    <div class="mb-3">
                        <h5>Role</h5>
                        <p>Administrator</p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 