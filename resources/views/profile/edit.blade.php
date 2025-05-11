<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Metro Essence - Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        body {
            font-family: 'Times New Roman', serif;
            background-color: #fdf8f6;
            color: #333;
        }
        .navbar, footer {
            background-color: #fff;
            border-bottom: 1px solid #eee;
        }
        .navbar-brand {
            font-weight: 300;
            letter-spacing: 2px;
            color: #5d1d48 !important;
        }
        .section-title {
            font-size: 1.7rem;
            font-weight: bold;
            color: #5d1d48;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .profile-card {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            padding: 30px;
            margin-bottom: 30px;
        }
        .form-label {
            font-weight: bold;
            color: #5d1d48;
        }
        .btn-primary {
            background-color: #5d1d48;
            border-color: #5d1d48;
        }
        .btn-primary:hover {
            background-color: #4a1839;
            border-color: #4a1839;
        }
        footer {
            background-color: #f8f8f8;
            padding: 30px 0;
            margin-top: 50px;
            border-top: 1px solid #eee;
        }
        .footer-links {
            list-style: none;
            padding: 0;
        }
        .footer-links li {
            margin-bottom: 10px;
        }
        .footer-links a {
            color: #5d1d48;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .social-icons {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .social-icons li {
            margin: 0 10px;
        }
        .social-icons a {
            color: #5d1d48;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container py-5">
        <h2 class="section-title">My Profile</h2>

        <div class="row justify-content-center">
            <div class="col-md-8">

                <!-- Update Profile Info -->
                <div class="profile-card">
                    <h4 class="mb-4"><i class="fas fa-user-edit me-2"></i> Update Profile Information</h4>
                    @include('profile.partials.update-profile-information-form')
                </div>

                <!-- Update Password -->
                <div class="profile-card">
                    <h4 class="mb-4"><i class="fas fa-lock me-2"></i> Change Password</h4>
                    @include('profile.partials.update-password-form')
                </div>

                <!-- Delete Account -->
                <div class="profile-card">
                    <h4 class="mb-4 text-danger"><i class="fas fa-user-times me-2"></i> Delete Account</h4>
                    <p class="text-muted">Once your account is deleted, all your data will be permanently removed. Please proceed with caution.</p>
                    @include('profile.partials.delete-user-form')
                </div>

            </div>
        </div>
    </div>

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
