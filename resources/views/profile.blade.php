<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Georgia', serif;
            background-color: #ffffff;
            color: #000;
        }
        .profile-container {
            margin-top: 40px;
        }
        .profile-box {
            background-color: #fdfbfc;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .profile-title {
            text-align: center;
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .form-label {
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
        }
        .form-control[readonly] {
            background-color: #e9ecef;
        }
        .btn-link, .btn-action {
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            margin-left: 10px;
        }
        .btn-danger {
            color: red;
        }
        .profile-icon {
            width: 150px;
            height: 150px;
        }
    </style>
</head>
<body>

<div class="container profile-container">
    <div class="row">
        <!-- Icon -->
        <div class="col-md-4 d-flex justify-content-center align-items-start">
            <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="User Icon" class="profile-icon">
        </div>

        <!-- Info -->
        <div class="col-md-8">
            <div class="profile-box">
                <div class="profile-title">MY ACCOUNT</div>

                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Name:</label>
                            <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Email:</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Password:</label>
                        <input type="password" class="form-control" value="********" readonly>
                    </div>

                    <div class="d-flex justify-content-end align-items-center">
                        <!-- Logout -->
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="btn-link">LOGOUT</a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>

                        <!-- Edit -->
                        <a href="{{ route('profile.edit') }}" class="btn-action btn btn-secondary">EDIT</a>

                        <!-- Delete -->
                        <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your account?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn btn-danger">DELETE ACCOUNT</button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
