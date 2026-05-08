<?php
/**
 * Facility Page Content
 * Displays available facilities for booking
 */

// Include database connection
require_once '../includes/database.php';

// Mock facilities data (replace with real database queries)
$facilities = [
    [
        'id' => 1,
        'name' => 'Computer Laboratory 1',
        'type' => 'Computer Lab',
        'capacity' => 40,
        'location' => 'IT Building, 2nd Floor',
        'status' => 'Available',
        'description' => '40 workstations, projector, AC, internet access',
        'amenities' => ['Computers', 'Projector', 'Air Conditioning', 'WiFi', 'Printer'],
        'image' => '../assets/icons/facility.svg'
    ],
    [
        'id' => 2,
        'name' => 'Auditorium',
        'type' => 'Event Space',
        'capacity' => 500,
        'location' => 'Main Building, Ground Floor',
        'status' => 'Limited',
        'description' => '500 seats, sound system, stage lighting, projection equipment',
        'amenities' => ['Sound System', 'Stage', 'Lighting', 'Projector', 'Microphones', 'Air Conditioning'],
        'image' => '../assets/icons/facility.svg'
    ],
    [
        'id' => 3,
        'name' => 'Library Study Room A',
        'type' => 'Study Room',
        'capacity' => 8,
        'location' => 'Library Building, 3rd Floor',
        'status' => 'Available',
        'description' => 'Quiet study area with tables, chairs, and power outlets',
        'amenities' => ['Tables', 'Chairs', 'Power Outlets', 'WiFi', 'Whiteboard'],
        'image' => '../assets/icons/facility.svg'
    ],
    [
        'id' => 4,
        'name' => 'Gymnasium',
        'type' => 'Sports Facility',
        'capacity' => 200,
        'location' => 'Sports Complex',
        'status' => 'Available',
        'description' => 'Multi-purpose gymnasium for sports and events',
        'amenities' => ['Basketball Court', 'Volleyball Nets', 'Bleachers', 'Lockers', 'Showers'],
        'image' => '../assets/icons/facility.svg'
    ],
    [
        'id' => 5,
        'name' => 'Conference Room A',
        'type' => 'Meeting Room',
        'capacity' => 20,
        'location' => 'Administration Building, 1st Floor',
        'status' => 'Available',
        'description' => 'Modern conference room with video conferencing capabilities',
        'amenities' => ['Video Conferencing', 'Projector', 'Whiteboard', 'WiFi', 'Coffee Station'],
        'image' => '../assets/icons/facility.svg'
    ],
    [
        'id' => 6,
        'name' => 'Computer Laboratory 2',
        'type' => 'Computer Lab',
        'capacity' => 30,
        'location' => 'IT Building, 3rd Floor',
        'status' => 'Maintenance',
        'description' => '30 workstations, specialized software for design and development',
        'amenities' => ['Computers', 'Design Software', 'Scanners', 'Printers', 'WiFi'],
        'image' => '../assets/icons/facility.svg'
    ]
];

// Status badge classes
$statusClasses = [
    'Available' => 'bg-success',
    'Limited' => 'bg-warning text-dark',
    'Maintenance' => 'bg-danger',
    'Reserved' => 'bg-info'
];
?>

<div class="row g-4">
    <!-- Filters and Search -->
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-body-custom">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="facilitySearch" class="form-label">Search Facilities</label>
                        <input type="text" class="form-control" id="facilitySearch" placeholder="Search by name, type, or location...">
                    </div>
                    <div class="col-md-2">
                        <label for="typeFilter" class="form-label">Type</label>
                        <select class="form-select" id="typeFilter">
                            <option value="">All Types</option>
                            <option value="Computer Lab">Computer Lab</option>
                            <option value="Event Space">Event Space</option>
                            <option value="Study Room">Study Room</option>
                            <option value="Sports Facility">Sports Facility</option>
                            <option value="Meeting Room">Meeting Room</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="statusFilter" class="form-label">Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="Available">Available</option>
                            <option value="Limited">Limited</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Reserved">Reserved</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="capacityFilter" class="form-label">Min Capacity</label>
                        <input type="number" class="form-control" id="capacityFilter" placeholder="e.g., 20">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" id="clearFilters">Clear Filters</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Facilities Grid -->
    <div class="col-12">
        <div class="row g-4" id="facilitiesGrid">
            <?php foreach ($facilities as $facility): ?>
            <div class="col-xl-4 col-lg-6 facility-card" data-type="<?php echo htmlspecialchars($facility['type']); ?>" data-status="<?php echo htmlspecialchars($facility['status']); ?>" data-capacity="<?php echo $facility['capacity']; ?>">
                <div class="card h-100 border">
                    <div class="card-body text-center">
                        <div class="facility-icon mb-3">
                            <svg width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z"/>
                            </svg>
                        </div>
                        <h6><?php echo htmlspecialchars($facility['name']); ?></h6>
                        <p class="text-muted small mb-2"><?php echo htmlspecialchars($facility['description']); ?></p>
                        <div class="facility-details mb-3">
                            <small class="text-muted d-block">
                                <strong>Type:</strong> <?php echo htmlspecialchars($facility['type']); ?>
                            </small>
                            <small class="text-muted d-block">
                                <strong>Capacity:</strong> <?php echo $facility['capacity']; ?> persons
                            </small>
                            <small class="text-muted d-block">
                                <strong>Location:</strong> <?php echo htmlspecialchars($facility['location']); ?>
                            </small>
                        </div>
                        <span class="badge <?php echo $statusClasses[$facility['status']] ?? 'bg-secondary'; ?> mb-3">
                            <?php echo htmlspecialchars($facility['status']); ?>
                        </span>
                        <div class="mt-3">
                            <button class="btn btn-sm btn-outline-primary me-2" onclick="viewFacilityDetails(<?php echo $facility['id']; ?>)">Details</button>
                            <?php if ($facility['status'] === 'Available' || $facility['status'] === 'Limited'): ?>
                            <button class="btn btn-sm btn-primary-custom" onclick="requestFacility(<?php echo $facility['id']; ?>, '<?php echo htmlspecialchars($facility['name']); ?>')">Request</button>
                            <?php else: ?>
                            <button class="btn btn-sm btn-outline-secondary" disabled>Unavailable</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Facility Details Modal -->
<div class="modal fade" id="facilityDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="facilityDetailsTitle">Facility Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="facilityDetailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary-custom" id="requestFromDetailsBtn">Request This Facility</button>
            </div>
        </div>
    </div>
</div>

<script>
// Facility filtering and search functionality
document.addEventListener('DOMContentLoaded', function() {
    const facilitySearch = document.getElementById('facilitySearch');
    const typeFilter = document.getElementById('typeFilter');
    const statusFilter = document.getElementById('statusFilter');
    const capacityFilter = document.getElementById('capacityFilter');
    const clearFilters = document.getElementById('clearFilters');
    const facilitiesGrid = document.getElementById('facilitiesGrid');

    function filterFacilities() {
        const searchValue = facilitySearch.value.toLowerCase();
        const typeValue = typeFilter.value;
        const statusValue = statusFilter.value;
        const capacityValue = parseInt(capacityFilter.value) || 0;

        const cards = facilitiesGrid.querySelectorAll('.facility-card');

        cards.forEach(card => {
            const name = card.querySelector('h6').textContent.toLowerCase();
            const description = card.querySelector('p').textContent.toLowerCase();
            const location = card.querySelector('.facility-details small:last-child').textContent.toLowerCase();
            const type = card.dataset.type;
            const status = card.dataset.status;
            const capacity = parseInt(card.dataset.capacity);

            const searchMatch = !searchValue ||
                name.includes(searchValue) ||
                description.includes(searchValue) ||
                location.includes(searchValue);

            const typeMatch = !typeValue || type === typeValue;
            const statusMatch = !statusValue || status === statusValue;
            const capacityMatch = capacity >= capacityValue;

            card.style.display = (searchMatch && typeMatch && statusMatch && capacityMatch) ? '' : 'none';
        });
    }

    facilitySearch.addEventListener('input', filterFacilities);
    typeFilter.addEventListener('change', filterFacilities);
    statusFilter.addEventListener('change', filterFacilities);
    capacityFilter.addEventListener('input', filterFacilities);

    clearFilters.addEventListener('click', function() {
        facilitySearch.value = '';
        typeFilter.value = '';
        statusFilter.value = '';
        capacityFilter.value = '';
        filterFacilities();
    });
});

// Facility data for modal
const facilitiesData = <?php echo json_encode($facilities); ?>;

// Global functions for facility actions
function viewFacilityDetails(facilityId) {
    const facility = facilitiesData.find(f => f.id === facilityId);
    if (facility) {
        document.getElementById('facilityDetailsTitle').textContent = facility.name;

        const content = document.getElementById('facilityDetailsContent');
        content.innerHTML = `
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="facility-image mb-3">
                        <svg width="100" height="100" fill="currentColor" viewBox="0 0 16 16" class="text-primary opacity-50">
                            <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z"/>
                        </svg>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="text-primary">${facility.type}</h6>
                    <p class="mb-2">${facility.description}</p>
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted d-block"><strong>Capacity:</strong></small>
                            ${facility.capacity} persons
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block"><strong>Status:</strong></small>
                            <span class="badge ${facility.status === 'Available' ? 'bg-success' : facility.status === 'Limited' ? 'bg-warning text-dark' : 'bg-danger'}">${facility.status}</span>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block"><strong>Location:</strong></small>
                            ${facility.location}
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <h6>Amenities</h6>
                    <div class="amenities-list">
                        ${facility.amenities.map(amenity => `<span class="badge bg-light text-dark me-1 mb-1">${amenity}</span>`).join('')}
                    </div>
                </div>
                <div class="col-12">
                    <h6>Booking Information</h6>
                    <div class="alert alert-info">
                        <small>
                            <strong>Booking Hours:</strong> Monday-Friday 8:00 AM - 8:00 PM<br>
                            <strong>Advance Booking:</strong> Up to 30 days in advance<br>
                            <strong>Cancellation:</strong> At least 24 hours notice required
                        </small>
                    </div>
                </div>
            </div>
        `;

        // Update request button
        const requestBtn = document.getElementById('requestFromDetailsBtn');
        requestBtn.onclick = () => requestFacility(facility.id, facility.name);
        if (facility.status !== 'Available' && facility.status !== 'Limited') {
            requestBtn.disabled = true;
            requestBtn.textContent = 'Currently Unavailable';
        } else {
            requestBtn.disabled = false;
            requestBtn.textContent = 'Request This Facility';
        }

        new bootstrap.Modal(document.getElementById('facilityDetailsModal')).show();
    }
}

function requestFacility(facilityId, facilityName) {
    // Redirect to requests page with facility pre-selected
    window.location.href = `../dashboard.php?page=requests&facility=${encodeURIComponent(facilityName)}`;
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