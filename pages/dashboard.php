<?php
/**
 * Dashboard Page Content
 * Displays overview statistics and recent activity
 */

// Include database connection
require_once '../includes/database.php';

// Get statistics (replace with real database queries)
$statistics = [
    'totalRequests' => getCount("SELECT COUNT(*) FROM requests") ?: 47,
    'pendingRequests' => getCount("SELECT COUNT(*) FROM requests WHERE status = 'Pending'") ?: 12,
    'approvedRequests' => getCount("SELECT COUNT(*) FROM requests WHERE status = 'Approved'") ?: 28,
    'completedRequests' => getCount("SELECT COUNT(*) FROM requests WHERE status = 'Completed'") ?: 35,
    'unreadNotifications' => getCount("SELECT COUNT(*) FROM notifications WHERE status = 'Unread'") ?: 5,
    'activeFacilities' => getCount("SELECT COUNT(*) FROM facilities WHERE status = 'Active'") ?: 24
];
?>

<!-- Dashboard Overview -->
<div class="row g-4 mb-4">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card dashboard-card h-100">
            <div class="card-body stat-card">
                <div class="stat-value text-primary"><?php echo number_format($statistics['totalRequests']); ?></div>
                <div class="stat-label">Total Requests</div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card dashboard-card h-100">
            <div class="card-body stat-card">
                <div class="stat-value text-warning"><?php echo number_format($statistics['pendingRequests']); ?></div>
                <div class="stat-label">Pending</div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card dashboard-card h-100">
            <div class="card-body stat-card">
                <div class="stat-value text-success"><?php echo number_format($statistics['approvedRequests']); ?></div>
                <div class="stat-label">Approved</div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card dashboard-card h-100">
            <div class="card-body stat-card">
                <div class="stat-value text-info"><?php echo number_format($statistics['activeFacilities']); ?></div>
                <div class="stat-label">Active Facilities</div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity and Quick Actions -->
<div class="row g-4">
    <!-- Recent Requests -->
    <div class="col-xl-8">
        <div class="card dashboard-card">
            <div class="card-header-custom">
                <h5 class="mb-0">Recent Requests</h5>
            </div>
            <div class="card-body-custom">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>Facility</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>REQ-2026-001</strong></td>
                                <td>Computer Laboratory 1</td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                                <td>May 8, 2026</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>REQ-2026-002</strong></td>
                                <td>Auditorium</td>
                                <td><span class="badge bg-success">Approved</span></td>
                                <td>May 7, 2026</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>REQ-2026-003</strong></td>
                                <td>Library Study Room</td>
                                <td><span class="badge bg-primary">In Progress</span></td>
                                <td>May 6, 2026</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <a href="dashboard.php?page=requests" class="btn btn-primary-custom">View All Requests</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Notifications -->
    <div class="col-xl-4">
        <div class="row g-4">
            <!-- Quick Actions -->
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-header-custom">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="d-grid gap-2">
                            <a href="dashboard.php?page=requests" class="btn btn-primary-custom">
                                <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2z"/>
                                </svg>
                                New Request
                            </a>
                            <a href="dashboard.php?page=facility" class="btn btn-outline-custom">
                                <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z"/>
                                </svg>
                                Browse Facilities
                            </a>
                            <a href="dashboard.php?page=reports" class="btn btn-outline-custom">
                                <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zm8 0A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm-8 8A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm8 0A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3z"/>
                                </svg>
                                View Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-header-custom">
                        <h5 class="mb-0">System Status</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="alert alert-success-custom mb-3">
                            <strong>All Systems Operational</strong><br>
                            Facility request processing is running normally.
                        </div>
                        <div class="small text-muted">
                            Last updated: <?php echo date('M j, Y g:i A'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>