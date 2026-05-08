<?php
/**
 * Footer component for SNSU-FRMS
 */
?>

<!-- Footer -->
<footer class="bg-white border-top py-4 mt-auto">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0 text-muted">
                    &copy; <?php echo date('Y'); ?> Surigao del Norte State University.
                    All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0 text-muted">
                    SNSU-FRMS v1.0.0 | Facility Request and Monitoring System
                </p>
            </div>
        </div>

        <!-- Additional footer links -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-3">
                    <a href="dashboard.php?page=faq" class="text-decoration-none text-muted small">Help & FAQ</a>
                    <span class="text-muted small">|</span>
                    <a href="dashboard.php?page=reports" class="text-decoration-none text-muted small">Reports</a>
                    <span class="text-muted small">|</span>
                    <a href="mailto:support@snsu.edu.ph" class="text-decoration-none text-muted small">Contact Support</a>
                    <span class="text-muted small">|</span>
                    <a href="https://www.snsu.edu.ph" target="_blank" class="text-decoration-none text-muted small">SNSU Website</a>
                </div>
            </div>
        </div>
    </div>
</footer>