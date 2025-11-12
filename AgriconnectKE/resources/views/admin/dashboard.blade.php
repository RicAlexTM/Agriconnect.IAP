<!-- resources/views/admin/dashboard.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Admin Dashboard</h1>
        <p>Welcome to the admin dashboard!</p>
        
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <h2 class="card-text">{{ $stats['total_users'] ?? 0 }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Farmers</h5>
                        <h2 class="card-text">{{ $stats['total_farmers'] ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>