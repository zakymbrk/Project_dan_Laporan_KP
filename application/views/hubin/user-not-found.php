<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view') ?>"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('hubin/view/daftar-siswa') ?>">Data Siswa</a></li>
        <li class="breadcrumb-item active">User Not Found</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-user-times me-2"></i>User Not Found</h2>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-user-times text-danger" style="font-size: 4rem;"></i>
                </div>
                <h3 class="mb-3">User Not Found</h3>
                <p class="lead mb-4">
                    The user with code <code class="bg-light px-2 py-1 rounded"><?php echo htmlspecialchars($requested_code); ?></code> could not be found.
                </p>
                
                <div class="alert alert-warning text-start mx-auto" style="max-width: 500px;">
                    <h5><i class="fas fa-exclamation-triangle me-2"></i>Possible Reasons:</h5>
                    <ul class="mb-0">
                        <li>The user may have been deleted</li>
                        <li>The user code in the URL is incorrect</li>
                        <li>The link you clicked may be outdated</li>
                    </ul>
                </div>
                
                <div class="mt-4">
                    <a href="<?php echo base_url('hubin/view/daftar-siswa') ?>" class="btn btn-primary btn-lg me-2">
                        <i class="fas fa-users me-2"></i>View All Users
                    </a>
                    <button onclick="history.back()" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>Go Back
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>