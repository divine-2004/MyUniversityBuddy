<?php
/**
 * Reports Page Content
 * Displays analytics and reports for facility usage
 */

// Include database connection
require_once '../includes/database.php';

// Mock statistics for reports
$reportStats = [
    'totalRequests' => getCount("SELECT COUNT(*) FROM requests") ?: 47,
    'approvedRequests' => getCount("SELECT COUNT(*) FROM requests WHERE status = 'Approved'") ?: 28,
    'completedRequests' => getCount("SELECT COUNT(*) FROM requests WHERE status = 'Completed'") ?: 35,
    'pendingRequests' => getCount("SELECT COUNT(*) FROM requests WHERE status = 'Pending'") ?: 12,
    'rejectedRequests' => getCount("SELECT COUNT(*) FROM requests WHERE status = 'Rejected'") ?: 2,
    'totalFacilities' => getCount("SELECT COUNT(*) FROM facilities") ?: 24,
    'activeFacilities' => getCount("SELECT COUNT(*) FROM facilities WHERE status = 'Active'") ?: 22,
    'maintenanceFacilities' => getCount("SELECT COUNT(*) FROM facilities WHERE status = 'Maintenance'") ?: 2,
    'totalBookings' => getCount("SELECT COUNT(*) FROM bookings") ?: 156,
    'thisMonthBookings' => getCount("SELECT COUNT(*) FROM bookings WHERE MONTH(created_at) = MONTH(CURRENT_DATE())") ?: 23,
    'avgProcessingTime' => 2.5, // days
    'satisfactionRate' => 94.2 // percentage
];

// Monthly data for charts (mock data)
$monthlyData = [
    ['month' => 'Jan', 'requests' => 45, 'bookings' => 120],
    ['month' => 'Feb', 'requests' => 52, 'bookings' => 135],
    ['month' => 'Mar', 'requests' => 48, 'bookings' => 142],
    ['month' => 'Apr', 'requests' => 61, 'bookings' => 158],
    ['month' => 'May', 'requests' => 47, 'bookings' => 156]
];

// Facility usage data
$facilityUsage = [
    ['facility' => 'Computer Laboratory 1', 'usage' => 85, 'requests' => 23],
    ['facility' => 'Auditorium', 'usage' => 72, 'requests' => 18],
    ['facility' => 'Library Study Room A', 'usage' => 68, 'requests' => 15],
    ['facility' => 'Gymnasium', 'usage' => 45, 'requests' => 12],
    ['facility' => 'Conference Room A', 'usage' => 38, 'requests' => 9]
];
?>

<div class="row g-4">
    <!-- Report Filters -->
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-body-custom">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="reportType" class="form-label">Report Type</label>
                        <select class="form-select" id="reportType">
                            <option value="overview">Overview Report</option>
                            <option value="usage">Facility Usage</option>
                            <option value="requests">Request Analysis</option>
                            <option value="performance">Performance Metrics</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="dateRange" class="form-label">Date Range</label>
                        <select class="form-select" id="dateRange">
                            <option value="this-month">This Month</option>
                            <option value="last-month">Last Month</option>
                            <option value="last-3-months">Last 3 Months</option>
                            <option value="this-year">This Year</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                    <div class="col-md-2" id="startDateContainer" style="display: none;">
                        <label for="startDate" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="startDate">
                    </div>
                    <div class="col-md-2" id="endDateContainer" style="display: none;">
                        <label for="endDate" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="endDate">
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary-custom me-2" id="generateReport">Generate Report</button>
                        <button class="btn btn-outline-secondary" id="exportReport">Export</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="col-12">
        <div class="row g-4">
            <div class="col-xl-3 col-lg-6">
                <div class="card dashboard-card h-100">
                    <div class="card-body stat-card">
                        <div class="stat-value text-primary"><?php echo number_format($reportStats['totalRequests']); ?></div>
                        <div class="stat-label">Total Requests</div>
                        <div class="stat-change text-success">+12% from last month</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6">
                <div class="card dashboard-card h-100">
                    <div class="card-body stat-card">
                        <div class="stat-value text-success"><?php echo number_format($reportStats['approvedRequests'] / $reportStats['totalRequests'] * 100, 1); ?>%</div>
                        <div class="stat-label">Approval Rate</div>
                        <div class="stat-change text-success">+5% from last month</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6">
                <div class="card dashboard-card h-100">
                    <div class="card-body stat-card">
                        <div class="stat-value text-info"><?php echo number_format($reportStats['avgProcessingTime'], 1); ?> days</div>
                        <div class="stat-label">Avg Processing Time</div>
                        <div class="stat-change text-danger">+0.3 days from last month</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6">
                <div class="card dashboard-card h-100">
                    <div class="card-body stat-card">
                        <div class="stat-value text-warning"><?php echo number_format($reportStats['satisfactionRate'], 1); ?>%</div>
                        <div class="stat-label">Satisfaction Rate</div>
                        <div class="stat-change text-success">+2.1% from last month</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics -->
    <div class="col-xl-8">
        <div class="card dashboard-card">
            <div class="card-header-custom">
                <h5 class="mb-0">Monthly Trends</h5>
            </div>
            <div class="card-body-custom">
                <div class="chart-placeholder mb-4">
                    <div class="text-center text-muted py-5">
                        <svg width="64" height="64" fill="currentColor" viewBox="0 0 16 16" class="mb-3 opacity-50">
                            <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z"/>
                        </svg>
                        <p>Interactive chart would be displayed here</p>
                        <small class="text-muted">Integration with Chart.js or similar library</small>
                    </div>
                </div>

                <!-- Monthly Data Table -->
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Requests</th>
                                <th>Bookings</th>
                                <th>Growth</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($monthlyData as $index => $data): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($data['month']); ?></td>
                                <td><?php echo number_format($data['requests']); ?></td>
                                <td><?php echo number_format($data['bookings']); ?></td>
                                <td>
                                    <?php
                                    if ($index > 0) {
                                        $prevRequests = $monthlyData[$index - 1]['requests'];
                                        $growth = (($data['requests'] - $prevRequests) / $prevRequests) * 100;
                                        $growthClass = $growth >= 0 ? 'text-success' : 'text-danger';
                                        echo '<span class="' . $growthClass . '">' . ($growth >= 0 ? '+' : '') . number_format($growth, 1) . '%</span>';
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Facility Usage & Export Options -->
    <div class="col-xl-4">
        <div class="row g-4">
            <!-- Facility Usage -->
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-header-custom">
                        <h5 class="mb-0">Facility Usage</h5>
                    </div>
                    <div class="card-body-custom">
                        <?php foreach ($facilityUsage as $facility): ?>
                        <div class="facility-usage-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="facility-name"><?php echo htmlspecialchars($facility['facility']); ?></span>
                                <span class="usage-percentage"><?php echo $facility['usage']; ?>%</span>
                            </div>
                            <div class="progress progress-custom">
                                <div class="progress-bar progress-bar-custom" style="width: <?php echo $facility['usage']; ?>%"></div>
                            </div>
                            <small class="text-muted"><?php echo $facility['requests']; ?> requests this month</small>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Export Options -->
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-header-custom">
                        <h5 class="mb-0">Export Reports</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary" onclick="exportReport('pdf')">
                                <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                    <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 1 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.16.846.195.381.56.854 1.035 1.385zm.63 1.142a7.424 7.424 0 0 1-.99-.501 6.513 6.513 0 0 1-.29-.285c-.2-.256-.197-.51-.055-.724.26-.412.687-.29 1.055.17a6.973 6.973 0 0 1 .636.89zm-.684-3.652c.156.05.32.086.496.11a4.05 4.05 0 0 1 .777.12c.16.049.318.115.47.187a.72.72 0 0 1 .34.265c.068.093.09.2.077.307a1.44 1.44 0 0 1-.217.39.8.8 0 0 1-.263.196c-.293.118-.654.127-.885-.028a2.65 2.65 0 0 1-.616-.614 1.385 1.385 0 0 1-.144-.3c-.008-.107.02-.21.087-.29.096-.115.263-.183.52-.198a1.74 1.74 0 0 1 .46.086z"/>
                                </svg>
                                Export PDF
                            </button>
                            <button class="btn btn-outline-success" onclick="exportReport('excel')">
                                <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8.5 2.687c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
                                </svg>
                                Export Excel
                            </button>
                            <button class="btn btn-outline-info" onclick="exportReport('csv')">
                                <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                </svg>
                                Export CSV
                            </button>
                            <button class="btn btn-outline-warning" onclick="scheduleReport()">
                                <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                                </svg>
                                Schedule Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Report Modal -->
<div class="modal fade" id="scheduleReportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form class="form-custom" id="scheduleReportForm">
                    <div class="mb-3">
                        <label for="reportName" class="form-label">Report Name</label>
                        <input type="text" class="form-control" id="reportName" placeholder="Monthly Usage Report" required>
                    </div>
                    <div class="mb-3">
                        <label for="frequency" class="form-label">Frequency</label>
                        <select class="form-select" id="frequency" required>
                            <option value="">Select frequency...</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">Quarterly</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="recipients" class="form-label">Recipients</label>
                        <input type="email" class="form-control" id="recipients" placeholder="email@example.com" multiple required>
                        <div class="form-text">Separate multiple emails with commas</div>
                    </div>
                    <div class="mb-3">
                        <label for="format" class="form-label">Format</label>
                        <select class="form-select" id="format" required>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary-custom" id="saveScheduleBtn">Schedule Report</button>
            </div>
        </div>
    </div>
</div>

<script>
// Report functionality
document.addEventListener('DOMContentLoaded', function() {
    const dateRange = document.getElementById('dateRange');
    const startDateContainer = document.getElementById('startDateContainer');
    const endDateContainer = document.getElementById('endDateContainer');
    const generateReport = document.getElementById('generateReport');
    const exportReportBtn = document.getElementById('exportReport');
    const saveScheduleBtn = document.getElementById('saveScheduleBtn');

    // Show/hide custom date range
    dateRange.addEventListener('change', function() {
        if (this.value === 'custom') {
            startDateContainer.style.display = 'block';
            endDateContainer.style.display = 'block';
        } else {
            startDateContainer.style.display = 'none';
            endDateContainer.style.display = 'none';
        }
    });

    // Generate report
    generateReport.addEventListener('click', function() {
        const reportType = document.getElementById('reportType').value;
        const dateRangeValue = dateRange.value;

        showAlert(`Generating ${reportType} report for ${dateRangeValue}...`, 'info');

        // Here you would typically send the request to generate the report
        setTimeout(() => {
            showAlert('Report generated successfully!', 'success');
        }, 2000);
    });

    // Export report
    exportReportBtn.addEventListener('click', function() {
        exportReport('pdf'); // Default to PDF
    });

    // Save scheduled report
    saveScheduleBtn.addEventListener('click', function() {
        const form = document.getElementById('scheduleReportForm');
        if (form.checkValidity()) {
            // Here you would send the schedule data to the server
            bootstrap.Modal.getInstance(document.getElementById('scheduleReportModal')).hide();
            form.reset();
            showAlert('Report scheduled successfully!', 'success');
        } else {
            form.reportValidity();
        }
    });
});

// Global functions
function exportReport(format) {
    showAlert(`Exporting report as ${format.toUpperCase()}...`, 'info');

    // Mock export - in real app, trigger file download
    setTimeout(() => {
        showAlert(`Report exported as ${format.toUpperCase()} successfully!`, 'success');
    }, 2000);
}

function scheduleReport() {
    new bootstrap.Modal(document.getElementById('scheduleReportModal')).show();
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(alertDiv);

    // Auto remove after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>