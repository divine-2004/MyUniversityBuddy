<?php
/**
 * SNSU-FRMS Dashboard
 * Main entry point for the Facility Request and Monitoring System
 */

// Include database connection
require_once 'includes/database.php';

// Get current page
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Page titles for SEO and breadcrumbs
$pageTitles = [
    'dashboard' => 'Dashboard Overview',
    'profile' => 'My Profile',
    'requests' => 'My Requests',
    'facility' => 'Facility List',
    'calendar' => 'Calendar',
    'notifications' => 'Notifications',
    'reports' => 'Reports',
    'faq' => 'Frequently Asked Questions',
    'settings' => 'Settings'
];

$currentTitle = isset($pageTitles[$page]) ? $pageTitles[$page] : 'Dashboard Overview';

// Mock statistics (replace with real database queries)
$statistics = [
    'totalRequests' => getCount("SELECT COUNT(*) FROM requests") ?: 47,
    'pendingRequests' => getCount("SELECT COUNT(*) FROM requests WHERE status = 'Pending'") ?: 12,
    'approvedRequests' => getCount("SELECT COUNT(*) FROM requests WHERE status = 'Approved'") ?: 28,
    'completedRequests' => getCount("SELECT COUNT(*) FROM requests WHERE status = 'Completed'") ?: 35,
    'unreadNotifications' => getCount("SELECT COUNT(*) FROM notifications WHERE status = 'Unread'") ?: 5,
    'activeFacilities' => getCount("SELECT COUNT(*) FROM facilities WHERE status = 'Active'") ?: 24
];

// Function to render page content
function renderPageContent($page, $statistics) {
    switch ($page) {
        case 'dashboard':
            renderDashboardPage($statistics);
            break;
        case 'profile':
            renderProfilePage();
            break;
        case 'requests':
            renderRequestsPage();
            break;
        case 'facility':
            renderFacilityPage();
            break;
        case 'calendar':
            renderCalendarPage();
            break;
        case 'notifications':
            renderNotificationsPage();
            break;
        case 'reports':
            renderReportsPage($statistics);
            break;
        case 'faq':
            renderFAQPage();
            break;
        case 'settings':
            renderSettingsPage();
            break;
        default:
            renderDashboardPage($statistics);
            break;
    }
}

function renderDashboardPage($statistics) {
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
    <?php
}

function renderProfilePage() {
    ?>
    <div class="row g-4">
        <div class="col-xl-4">
            <div class="card dashboard-card">
                <div class="card-body-custom text-center">
                    <img src="assets/icons/profile.svg" alt="Profile" class="rounded-circle mb-3" width="80" height="80" style="background-color: #f8f9fa; padding: 1rem;">
                    <h4>Jane Dela Cruz</h4>
                    <p class="text-muted mb-1">BS Computer Science</p>
                    <p class="text-muted small">Student ID: 20241234</p>
                    <span class="badge bg-primary">Active Student</span>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card dashboard-card">
                <div class="card-header-custom">
                    <h5 class="mb-0">Profile Information</h5>
                </div>
                <div class="card-body-custom">
                    <form class="form-custom">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" value="Jane" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" value="Dela Cruz" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" value="jane.delacruz@snsu.edu.ph" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" value="+63 912 345 6789" readonly>
                            </div>
                            <div class="col-12">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" rows="3" readonly>Main Campus, Surigao del Norte</textarea>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="button" class="btn btn-primary-custom">Edit Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function renderRequestsPage() {
    ?>
    <div class="card dashboard-card">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <h5 class="mb-0">My Requests</h5>
            <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#newRequestModal">
                <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2z"/>
                </svg>
                New Request
            </button>
        </div>
        <div class="card-body-custom">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Facility</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>REQ-2026-001</strong></td>
                            <td>Computer Laboratory 1</td>
                            <td>Equipment Repair</td>
                            <td><span class="badge bg-warning text-dark">Pending</span></td>
                            <td>May 8, 2026</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-1">View</button>
                                <button class="btn btn-sm btn-outline-danger">Cancel</button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>REQ-2026-002</strong></td>
                            <td>Auditorium</td>
                            <td>Event Booking</td>
                            <td><span class="badge bg-success">Approved</span></td>
                            <td>May 7, 2026</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-1">View</button>
                                <button class="btn btn-sm btn-outline-secondary">Download</button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>REQ-2026-003</strong></td>
                            <td>Library Study Room</td>
                            <td>Room Reservation</td>
                            <td><span class="badge bg-primary">In Progress</span></td>
                            <td>May 6, 2026</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-1">View</button>
                                <button class="btn btn-sm btn-outline-info">Update</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- New Request Modal -->
    <div class="modal fade" id="newRequestModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Facility Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form class="form-custom">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="facilitySelect" class="form-label">Facility</label>
                                <select class="form-select" id="facilitySelect">
                                    <option selected>Select facility...</option>
                                    <option>Computer Laboratory 1</option>
                                    <option>Computer Laboratory 2</option>
                                    <option>Auditorium</option>
                                    <option>Library Study Room</option>
                                    <option>Gymnasium</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="requestType" class="form-label">Request Type</label>
                                <select class="form-select" id="requestType">
                                    <option selected>Select type...</option>
                                    <option>Equipment Repair</option>
                                    <option>Room Reservation</option>
                                    <option>Event Booking</option>
                                    <option>Maintenance</option>
                                    <option>Technical Support</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="requestDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="requestDescription" rows="4" placeholder="Describe your request..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="requestDate" class="form-label">Preferred Date</label>
                                <input type="date" class="form-control" id="requestDate">
                            </div>
                            <div class="col-md-6">
                                <label for="requestTime" class="form-label">Preferred Time</label>
                                <input type="time" class="form-control" id="requestTime">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary-custom">Submit Request</button>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function renderFacilityPage() {
    ?>
    <div class="row g-4">
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="card-header-custom">
                    <h5 class="mb-0">Available Facilities</h5>
                </div>
                <div class="card-body-custom">
                    <div class="row g-4">
                        <div class="col-xl-4 col-lg-6">
                            <div class="card h-100 border">
                                <div class="card-body text-center">
                                    <div class="facility-icon mb-3">
                                        <svg width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z"/>
                                        </svg>
                                    </div>
                                    <h6>Computer Laboratory 1</h6>
                                    <p class="text-muted small mb-2">40 workstations, projector, AC</p>
                                    <span class="badge bg-success">Available</span>
                                    <div class="mt-3">
                                        <button class="btn btn-sm btn-outline-primary">Request</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-6">
                            <div class="card h-100 border">
                                <div class="card-body text-center">
                                    <div class="facility-icon mb-3">
                                        <svg width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path d="M5 6.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0zm1.138-1.496a.75.75 0 1 0-.976 1.138l.75.75a.75.75 0 0 0 1.138-.976l-.75-.75zm2.226 0a.75.75 0 1 0-.976-1.138l-.75.75a.75.75 0 0 0 1.138.976l.75-.75z"/>
                                        </svg>
                                    </div>
                                    <h6>Auditorium</h6>
                                    <p class="text-muted small mb-2">500 seats, sound system, stage</p>
                                    <span class="badge bg-warning text-dark">Limited</span>
                                    <div class="mt-3">
                                        <button class="btn btn-sm btn-outline-primary">Request</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-6">
                            <div class="card h-100 border">
                                <div class="card-body text-center">
                                    <div class="facility-icon mb-3">
                                        <svg width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8.5 2.687c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
                                        </svg>
                                    </div>
                                    <h6>Library Study Room</h6>
                                    <p class="text-muted small mb-2">Quiet study area, 8 seats</p>
                                    <span class="badge bg-success">Available</span>
                                    <div class="mt-3">
                                        <button class="btn btn-sm btn-outline-primary">Request</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function renderCalendarPage() {
    ?>
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card dashboard-card">
                <div class="card-header-custom">
                    <h5 class="mb-0">Calendar</h5>
                </div>
                <div class="card-body-custom">
                    <div class="calendar-container">
                        <div class="calendar-header d-flex justify-content-between align-items-center mb-4">
                            <h6 class="mb-0">May 2026</h6>
                            <div>
                                <button class="btn btn-sm btn-outline-secondary me-1">‹</button>
                                <button class="btn btn-sm btn-outline-secondary">›</button>
                            </div>
                        </div>
                        <div class="calendar-grid">
                            <div class="calendar-day-header">Sun</div>
                            <div class="calendar-day-header">Mon</div>
                            <div class="calendar-day-header">Tue</div>
                            <div class="calendar-day-header">Wed</div>
                            <div class="calendar-day-header">Thu</div>
                            <div class="calendar-day-header">Fri</div>
                            <div class="calendar-day-header">Sat</div>

                            <?php for ($i = 1; $i <= 31; $i++): ?>
                                <div class="calendar-day <?php echo $i === 8 ? 'today' : ''; ?> <?php echo in_array($i, [10, 15, 22]) ? 'has-events' : ''; ?>">
                                    <span class="day-number"><?php echo $i; ?></span>
                                    <?php if (in_array($i, [10, 15, 22])): ?>
                                        <div class="event-indicator"></div>
                                    <?php endif; ?>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card dashboard-card">
                <div class="card-header-custom">
                    <h5 class="mb-0">Upcoming Events</h5>
                </div>
                <div class="card-body-custom">
                    <div class="event-list">
                        <div class="event-item mb-3">
                            <div class="event-date">
                                <span class="event-day">10</span>
                                <span class="event-month">May</span>
                            </div>
                            <div class="event-details">
                                <h6 class="event-title">Facility Inspection</h6>
                                <p class="event-desc text-muted small">Computer Lab 1 maintenance check</p>
                            </div>
                        </div>

                        <div class="event-item mb-3">
                            <div class="event-date">
                                <span class="event-day">15</span>
                                <span class="event-month">May</span>
                            </div>
                            <div class="event-details">
                                <h6 class="event-title">Auditorium Booking</h6>
                                <p class="event-desc text-muted small">Student Council Meeting</p>
                            </div>
                        </div>

                        <div class="event-item">
                            <div class="event-date">
                                <span class="event-day">22</span>
                                <span class="event-month">May</span>
                            </div>
                            <div class="event-details">
                                <h6 class="event-title">Library Maintenance</h6>
                                <p class="event-desc text-muted small">Study rooms cleaning</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function renderNotificationsPage() {
    ?>
    <div class="card dashboard-card">
        <div class="card-header-custom">
            <h5 class="mb-0">Notifications</h5>
        </div>
        <div class="card-body-custom">
            <div class="notification-list">
                <div class="notification-item unread">
                    <div class="notification-icon">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2z"/>
                            <path d="M8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.57-.215 1.928.188.396.657.799 1.334.799h6.334c.677 0 1.146-.403 1.334-.799.161-.358-.055-1.161-.215-1.928C10.134 8.197 10 6.628 10 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917z"/>
                        </svg>
                    </div>
                    <div class="notification-content">
                        <h6 class="notification-title">Request Approved</h6>
                        <p class="notification-message">Your request for Computer Lab 1 has been approved.</p>
                        <small class="notification-time">2 hours ago</small>
                    </div>
                    <div class="notification-actions">
                        <button class="btn btn-sm btn-outline-primary">View</button>
                    </div>
                </div>

                <div class="notification-item">
                    <div class="notification-icon">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2z"/>
                            <path d="M8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.57-.215 1.928.188.396.657.799 1.334.799h6.334c.677 0 1.146-.403 1.334-.799.161-.358-.055-1.161-.215-1.928C10.134 8.197 10 6.628 10 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917z"/>
                        </svg>
                    </div>
                    <div class="notification-content">
                        <h6 class="notification-title">Maintenance Scheduled</h6>
                        <p class="notification-message">Library maintenance scheduled for May 22, 2026.</p>
                        <small class="notification-time">1 day ago</small>
                    </div>
                    <div class="notification-actions">
                        <button class="btn btn-sm btn-outline-primary">View</button>
                    </div>
                </div>

                <div class="notification-item">
                    <div class="notification-icon">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2z"/>
                            <path d="M8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.57-.215 1.928.188.396.657.799 1.334.799h6.334c.677 0 1.146-.403 1.334-.799.161-.358-.055-1.161-.215-1.928C10.134 8.197 10 6.628 10 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917z"/>
                        </svg>
                    </div>
                    <div class="notification-content">
                        <h6 class="notification-title">System Update</h6>
                        <p class="notification-message">FRMS has been updated to version 1.0.1.</p>
                        <small class="notification-time">3 days ago</small>
                    </div>
                    <div class="notification-actions">
                        <button class="btn btn-sm btn-outline-primary">View</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function renderReportsPage($statistics) {
    ?>
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card dashboard-card">
                <div class="card-header-custom">
                    <h5 class="mb-0">Request Statistics</h5>
                </div>
                <div class="card-body-custom">
                    <div class="chart-placeholder mb-4">
                        <div class="text-center text-muted py-5">
                            <svg width="64" height="64" fill="currentColor" viewBox="0 0 16 16" class="mb-3 opacity-50">
                                <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zm8 0A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm-8 8A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm8 0A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3z"/>
                            </svg>
                            <p>Chart visualization would be displayed here</p>
                            <small>Integration with Chart.js or similar library</small>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="metric-card">
                                <h6>Total Requests</h6>
                                <div class="metric-value"><?php echo number_format($statistics['totalRequests']); ?></div>
                                <div class="progress progress-custom mt-2">
                                    <div class="progress-bar progress-bar-custom" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="metric-card">
                                <h6>Completion Rate</h6>
                                <div class="metric-value"><?php echo round(($statistics['completedRequests'] / $statistics['totalRequests']) * 100); ?>%</div>
                                <div class="progress progress-custom mt-2">
                                    <div class="progress-bar progress-bar-custom" style="width: <?php echo round(($statistics['completedRequests'] / $statistics['totalRequests']) * 100); ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card dashboard-card">
                <div class="card-header-custom">
                    <h5 class="mb-0">Export Reports</h5>
                </div>
                <div class="card-body-custom">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary">
                            <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 1 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.16.846.195.381.56.854 1.035 1.385zm.63 1.142a7.424 7.424 0 0 1-.99-.501 6.513 6.513 0 0 1-.29-.285c-.2-.256-.197-.51-.055-.724.26-.412.687-.29 1.055.17a6.973 6.973 0 0 1 .636.89zm-.684-3.652c.156.05.32.086.496.11a4.05 4.05 0 0 1 .777.12c.16.049.318.115.47.187a.72.72 0 0 1 .34.265c.068.093.09.2.077.307a1.44 1.44 0 0 1-.217.39.8.8 0 0 1-.263.196c-.293.118-.654.127-.885-.028a2.65 2.65 0 0 1-.616-.614 1.385 1.385 0 0 1-.144-.3c-.008-.107.02-.21.087-.29.096-.115.263-.183.52-.198a1.74 1.74 0 0 1 .46.086z"/>
                            </svg>
                            Export PDF
                        </button>
                        <button class="btn btn-outline-success">
                            <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8.5 2.687c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
                            </svg>
                            Export Excel
                        </button>
                        <button class="btn btn-outline-info">
                            <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                            </svg>
                            Generate Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function renderFAQPage() {
    ?>
    <div class="card dashboard-card">
        <div class="card-header-custom">
            <h5 class="mb-0">Frequently Asked Questions</h5>
        </div>
        <div class="card-body-custom">
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            How do I submit a facility request?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            To submit a facility request, navigate to the "My Requests" page and click the "New Request" button. Fill out the required information including the facility, request type, description, and preferred date/time.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            How long does it take to process a request?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Most requests are processed within 24-48 hours during business days. Complex requests or those requiring special equipment may take longer. You'll receive notifications about your request status.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            What facilities are available for booking?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Available facilities include computer laboratories, auditorium, library study rooms, gymnasium, and various classrooms. Check the "Facility List" page for current availability and details.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            How do I cancel or modify a request?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            You can cancel or modify pending requests from the "My Requests" page. Click on the request and use the available action buttons. Approved requests may require contacting facilities management directly.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                            Who do I contact for technical support?
                        </button>
                    </h2>
                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            For technical support with the FRMS system, contact the IT department at it@snsu.edu.ph or call extension 1234. For facility-related issues, contact facilities management at facilities@snsu.edu.ph.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function renderSettingsPage() {
    ?>
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card dashboard-card">
                <div class="card-header-custom">
                    <h5 class="mb-0">Account Settings</h5>
                </div>
                <div class="card-body-custom">
                    <form class="form-custom">
                        <h6 class="mb-3">Personal Information</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" value="Jane">
                            </div>
                            <div class="col-md-6">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" value="Dela Cruz">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" value="jane.delacruz@snsu.edu.ph">
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" value="+63 912 345 6789">
                            </div>
                        </div>

                        <h6 class="mb-3">Notification Preferences</h6>
                        <div class="mb-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                                <label class="form-check-label" for="emailNotifications">
                                    Email notifications for request updates
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="smsNotifications" checked>
                                <label class="form-check-label" for="smsNotifications">
                                    SMS notifications for urgent updates
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="systemNotifications" checked>
                                <label class="form-check-label" for="systemNotifications">
                                    System maintenance notifications
                                </label>
                            </div>
                        </div>

                        <h6 class="mb-3">Security</h6>
                        <div class="mb-4">
                            <button type="button" class="btn btn-outline-primary mb-2" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                Change Password
                            </button>
                            <br>
                            <small class="text-muted">Last password change: 30 days ago</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary-custom">Save Changes</button>
                            <button type="reset" class="btn btn-outline-secondary">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card dashboard-card">
                <div class="card-header-custom">
                    <h5 class="mb-0">System Information</h5>
                </div>
                <div class="card-body-custom">
                    <dl class="row">
                        <dt class="col-sm-5">Version:</dt>
                        <dd class="col-sm-7">1.0.0</dd>
                        <dt class="col-sm-5">Last Login:</dt>
                        <dd class="col-sm-7"><?php echo date('M j, Y g:i A'); ?></dd>
                        <dt class="col-sm-5">Account Type:</dt>
                        <dd class="col-sm-7">Student</dd>
                        <dt class="col-sm-5">Status:</dt>
                        <dd class="col-sm-7"><span class="badge bg-success">Active</span></dd>
                    </dl>
                </div>
            </div>

            <div class="card dashboard-card mt-4">
                <div class="card-header-custom">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body-custom">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-danger">
                            <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                            Delete Account
                        </button>
                        <button class="btn btn-outline-warning">
                            <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M5 6.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0zm1.138-1.496a.75.75 0 1 0-.976 1.138l.75.75a.75.75 0 0 0 1.138-.976l-.75-.75zm2.226 0a.75.75 0 1 0-.976-1.138l-.75.75a.75.75 0 0 0 1.138.976l.75-.75z"/>
                            </svg>
                            Report Issue
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form class="form-custom">
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="currentPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="newPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmPassword" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary-custom">Change Password</button>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($currentTitle); ?> | SNSU-FRMS</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/dashboard.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="images/snsu logo.png">
</head>
<body>
    <!-- Dashboard Wrapper -->
    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navigation -->
            <?php include 'includes/header.php'; ?>

            <!-- Content Area -->
            <main class="content-area">
                <div class="page-header">
                    <h1 class="page-title"><?php echo htmlspecialchars($currentTitle); ?></h1>
                    <p class="page-subtitle">Manage your facility requests and access university resources efficiently.</p>
                </div>

                <!-- Page Content -->
                <?php renderPageContent($page, $statistics); ?>
            </main>

            <!-- Footer -->
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (sidebarToggle && sidebarOverlay) {
                sidebarToggle.addEventListener('click', function() {
                    document.body.classList.toggle('sidebar-open');
                });

                sidebarOverlay.addEventListener('click', function() {
                    document.body.classList.remove('sidebar-open');
                });
            }

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>
</body>
</html>