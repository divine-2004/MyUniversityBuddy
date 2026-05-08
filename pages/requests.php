<?php
/**
 * Requests Page Content
 * Displays and manages user facility requests
 */

// Include database connection
require_once '../includes/database.php';

// Mock requests data (replace with real database queries)
$requests = [
    [
        'id' => 'REQ-2026-001',
        'facility' => 'Computer Laboratory 1',
        'type' => 'Equipment Repair',
        'status' => 'Pending',
        'submitted' => '2026-05-08',
        'description' => 'Projector not working properly'
    ],
    [
        'id' => 'REQ-2026-002',
        'facility' => 'Auditorium',
        'type' => 'Event Booking',
        'status' => 'Approved',
        'submitted' => '2026-05-07',
        'description' => 'Student Council Meeting - 50 attendees'
    ],
    [
        'id' => 'REQ-2026-003',
        'facility' => 'Library Study Room',
        'type' => 'Room Reservation',
        'status' => 'In Progress',
        'submitted' => '2026-05-06',
        'description' => 'Group study session for finals'
    ],
    [
        'id' => 'REQ-2026-004',
        'facility' => 'Gymnasium',
        'type' => 'Event Booking',
        'status' => 'Completed',
        'submitted' => '2026-05-05',
        'description' => 'Basketball tournament'
    ]
];

// Status badge classes
$statusClasses = [
    'Pending' => 'bg-warning text-dark',
    'Approved' => 'bg-success',
    'In Progress' => 'bg-primary',
    'Completed' => 'bg-info',
    'Rejected' => 'bg-danger'
];
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
        <!-- Filters -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="typeFilter">
                    <option value="">All Types</option>
                    <option value="Equipment Repair">Equipment Repair</option>
                    <option value="Event Booking">Event Booking</option>
                    <option value="Room Reservation">Room Reservation</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Technical Support">Technical Support</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control" id="dateFilter" placeholder="Filter by date">
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-secondary w-100" id="clearFilters">Clear Filters</button>
            </div>
        </div>

        <!-- Requests Table -->
        <div class="table-responsive">
            <table class="table table-custom" id="requestsTable">
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
                    <?php foreach ($requests as $request): ?>
                    <tr data-status="<?php echo htmlspecialchars($request['status']); ?>" data-type="<?php echo htmlspecialchars($request['type']); ?>">
                        <td><strong><?php echo htmlspecialchars($request['id']); ?></strong></td>
                        <td><?php echo htmlspecialchars($request['facility']); ?></td>
                        <td><?php echo htmlspecialchars($request['type']); ?></td>
                        <td><span class="badge <?php echo $statusClasses[$request['status']] ?? 'bg-secondary'; ?>"><?php echo htmlspecialchars($request['status']); ?></span></td>
                        <td><?php echo date('M j, Y', strtotime($request['submitted'])); ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-primary" onclick="viewRequest('<?php echo htmlspecialchars($request['id']); ?>')">View</button>
                                <?php if ($request['status'] === 'Pending'): ?>
                                <button class="btn btn-sm btn-outline-danger" onclick="cancelRequest('<?php echo htmlspecialchars($request['id']); ?>')">Cancel</button>
                                <?php elseif ($request['status'] === 'Approved'): ?>
                                <button class="btn btn-sm btn-outline-secondary" onclick="downloadApproval('<?php echo htmlspecialchars($request['id']); ?>')">Download</button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="Requests pagination" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
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
                <form class="form-custom" id="newRequestForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="facilitySelect" class="form-label">Facility <span class="text-danger">*</span></label>
                            <select class="form-select" id="facilitySelect" name="facility" required>
                                <option value="">Select facility...</option>
                                <option value="Computer Laboratory 1">Computer Laboratory 1</option>
                                <option value="Computer Laboratory 2">Computer Laboratory 2</option>
                                <option value="Auditorium">Auditorium</option>
                                <option value="Library Study Room">Library Study Room</option>
                                <option value="Gymnasium">Gymnasium</option>
                                <option value="Conference Room A">Conference Room A</option>
                                <option value="Conference Room B">Conference Room B</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="requestType" class="form-label">Request Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="requestType" name="requestType" required>
                                <option value="">Select type...</option>
                                <option value="Equipment Repair">Equipment Repair</option>
                                <option value="Event Booking">Event Booking</option>
                                <option value="Room Reservation">Room Reservation</option>
                                <option value="Maintenance">Maintenance</option>
                                <option value="Technical Support">Technical Support</option>
                                <option value="Facility Access">Facility Access</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="requestDescription" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="requestDescription" name="description" rows="4" placeholder="Describe your request in detail..." required></textarea>
                            <div class="form-text">Please provide specific details about your request to help us process it efficiently.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="requestDate" class="form-label">Preferred Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="requestDate" name="preferredDate" min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="requestTime" class="form-label">Preferred Time</label>
                            <input type="time" class="form-control" id="requestTime" name="preferredTime">
                        </div>
                        <div class="col-md-6">
                            <label for="duration" class="form-label">Duration (hours)</label>
                            <input type="number" class="form-control" id="duration" name="duration" min="1" max="24" placeholder="e.g., 2">
                        </div>
                        <div class="col-md-6">
                            <label for="attendees" class="form-label">Number of Attendees</label>
                            <input type="number" class="form-control" id="attendees" name="attendees" min="1" placeholder="e.g., 50">
                        </div>
                        <div class="col-12">
                            <label for="additionalNotes" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="additionalNotes" name="additionalNotes" rows="2" placeholder="Any additional information or special requirements..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary-custom" id="submitRequestBtn">Submit Request</button>
            </div>
        </div>
    </div>
</div>

<!-- View Request Modal -->
<div class="modal fade" id="viewRequestModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="requestDetailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<script>
// Request management functionality
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    const dateFilter = document.getElementById('dateFilter');
    const clearFilters = document.getElementById('clearFilters');
    const requestsTable = document.getElementById('requestsTable');
    const submitRequestBtn = document.getElementById('submitRequestBtn');
    const newRequestForm = document.getElementById('newRequestForm');

    // Filter functionality
    function filterRequests() {
        const statusValue = statusFilter.value.toLowerCase();
        const typeValue = typeFilter.value.toLowerCase();
        const dateValue = dateFilter.value;

        const rows = requestsTable.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const status = row.dataset.status.toLowerCase();
            const type = row.dataset.type.toLowerCase();
            const rowDate = row.cells[4].textContent; // Submitted date column

            const statusMatch = !statusValue || status === statusValue;
            const typeMatch = !typeValue || type === typeValue;
            const dateMatch = !dateValue || rowDate.includes(dateValue);

            row.style.display = (statusMatch && typeMatch && dateMatch) ? '' : 'none';
        });
    }

    statusFilter.addEventListener('change', filterRequests);
    typeFilter.addEventListener('change', filterRequests);
    dateFilter.addEventListener('change', filterRequests);

    clearFilters.addEventListener('click', function() {
        statusFilter.value = '';
        typeFilter.value = '';
        dateFilter.value = '';
        filterRequests();
    });

    // Submit new request
    submitRequestBtn.addEventListener('click', function() {
        if (newRequestForm.checkValidity()) {
            // Here you would typically send the data to the server
            const formData = new FormData(newRequestForm);

            // Close modal and show success message
            bootstrap.Modal.getInstance(document.getElementById('newRequestModal')).hide();
            newRequestForm.reset();

            showAlert('Request submitted successfully! You will receive a confirmation email shortly.', 'success');

            // Refresh the page after a short delay
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            newRequestForm.reportValidity();
        }
    });
});

// Global functions for button actions
function viewRequest(requestId) {
    // Mock request details - in real app, fetch from server
    const requestDetails = {
        'REQ-2026-001': {
            id: 'REQ-2026-001',
            facility: 'Computer Laboratory 1',
            type: 'Equipment Repair',
            status: 'Pending',
            submitted: 'May 8, 2026',
            description: 'Projector not working properly - screen is flickering and colors are distorted.',
            preferredDate: 'May 15, 2026',
            preferredTime: '2:00 PM',
            priority: 'Medium',
            assignedTo: 'Pending Assignment',
            estimatedCompletion: 'TBD'
        },
        'REQ-2026-002': {
            id: 'REQ-2026-002',
            facility: 'Auditorium',
            type: 'Event Booking',
            status: 'Approved',
            submitted: 'May 7, 2026',
            description: 'Student Council Meeting - 50 attendees expected. Need sound system and projector.',
            preferredDate: 'May 20, 2026',
            preferredTime: '3:00 PM',
            duration: '2 hours',
            attendees: '50',
            approvedBy: 'Facilities Manager',
            approvalDate: 'May 8, 2026'
        }
    };

    const details = requestDetails[requestId];
    if (details) {
        const content = document.getElementById('requestDetailsContent');
        content.innerHTML = `
            <div class="row g-3">
                <div class="col-md-6">
                    <strong>Request ID:</strong> ${details.id}
                </div>
                <div class="col-md-6">
                    <strong>Status:</strong> <span class="badge ${details.status === 'Approved' ? 'bg-success' : 'bg-warning text-dark'}">${details.status}</span>
                </div>
                <div class="col-md-6">
                    <strong>Facility:</strong> ${details.facility}
                </div>
                <div class="col-md-6">
                    <strong>Type:</strong> ${details.type}
                </div>
                <div class="col-md-6">
                    <strong>Submitted:</strong> ${details.submitted}
                </div>
                <div class="col-md-6">
                    <strong>Preferred Date:</strong> ${details.preferredDate}
                </div>
                ${details.preferredTime ? `<div class="col-md-6"><strong>Preferred Time:</strong> ${details.preferredTime}</div>` : ''}
                ${details.duration ? `<div class="col-md-6"><strong>Duration:</strong> ${details.duration}</div>` : ''}
                ${details.attendees ? `<div class="col-md-6"><strong>Attendees:</strong> ${details.attendees}</div>` : ''}
                ${details.approvedBy ? `<div class="col-md-6"><strong>Approved By:</strong> ${details.approvedBy}</div>` : ''}
                ${details.approvalDate ? `<div class="col-md-6"><strong>Approval Date:</strong> ${details.approvalDate}</div>` : ''}
                ${details.assignedTo ? `<div class="col-md-6"><strong>Assigned To:</strong> ${details.assignedTo}</div>` : ''}
                ${details.estimatedCompletion ? `<div class="col-md-6"><strong>Est. Completion:</strong> ${details.estimatedCompletion}</div>` : ''}
                <div class="col-12">
                    <strong>Description:</strong><br>
                    <p class="mt-2">${details.description}</p>
                </div>
            </div>
        `;

        new bootstrap.Modal(document.getElementById('viewRequestModal')).show();
    }
}

function cancelRequest(requestId) {
    if (confirm(`Are you sure you want to cancel request ${requestId}?`)) {
        // Here you would send cancellation request to server
        showAlert(`Request ${requestId} has been cancelled.`, 'info');
        // Refresh the page after a short delay
        setTimeout(() => {
            location.reload();
        }, 1500);
    }
}

function downloadApproval(requestId) {
    // Mock download - in real app, trigger file download
    showAlert(`Downloading approval document for ${requestId}...`, 'info');
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