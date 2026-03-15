<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view/daftar-siswa') ?>">Data Siswa</a></li>
        <li class="breadcrumb-item active">Test User Detail</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-user me-2"></i>User Detail Test</h2>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-info-circle me-2"></i>Test Results
        </h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <h4><i class="fas fa-info-circle me-2"></i>Routing Test Successful</h4>
            <p>The routing is working correctly. However, the user with code <code><?php echo htmlspecialchars($user_code); ?></code> was not found in the database.</p>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>User Not Found</h5>
                    </div>
                    <div class="card-body">
                        <p>No user exists with the code: <code><?php echo htmlspecialchars($user_code); ?></code></p>
                        <p>This could be because:</p>
                        <ul>
                            <li>The user code is incorrect</li>
                            <li>The user has been deleted</li>
                            <li>The link you clicked is outdated</li>
                        </ul>
                        <a href="<?php echo base_url('hubin/view/daftar-siswa') ?>" class="btn btn-primary">
                            <i class="fas fa-users me-2"></i>View All Users
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>System Status</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Routing: <span class="badge bg-success">Working</span></li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Controller Method: <span class="badge bg-success">Accessible</span></li>
                            <li class="mb-2"><i class="fas fa-times text-danger me-2"></i> User Data: <span class="badge bg-danger">Not Found</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>