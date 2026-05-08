<?php
/**
 * Calendar Page Content
 * Displays facility bookings and events calendar
 */

// Include database connection
require_once '../includes/database.php';

// Mock events data (replace with real database queries)
$events = [
    [
        'id' => 1,
        'title' => 'Facility Inspection',
        'facility' => 'Computer Lab 1',
        'date' => '2026-05-10',
        'type' => 'maintenance',
        'description' => 'Regular maintenance and inspection of computer laboratory equipment'
    ],
    [
        'id' => 2,
        'title' => 'Student Council Meeting',
        'facility' => 'Auditorium',
        'date' => '2026-05-15',
        'type' => 'event',
        'description' => 'Monthly student council meeting with department heads'
    ],
    [
        'id' => 3,
        'title' => 'Library Maintenance',
        'facility' => 'Library Study Rooms',
        'date' => '2026-05-22',
        'type' => 'maintenance',
        'description' => 'Cleaning and maintenance of study rooms and common areas'
    ],
    [
        'id' => 4,
        'title' => 'Basketball Tournament',
        'facility' => 'Gymnasium',
        'date' => '2026-05-25',
        'type' => 'event',
        'description' => 'Inter-department basketball championship finals'
    ],
    [
        'id' => 5,
        'title' => 'Conference Room Booking',
        'facility' => 'Conference Room A',
        'date' => '2026-05-28',
        'type' => 'meeting',
        'description' => 'Department meeting for curriculum planning'
    ]
];

// Current month and year
$currentMonth = date('n');
$currentYear = date('Y');
$monthName = date('F Y');

// Calendar generation
function generateCalendar($month, $year, $events) {
    $firstDay = mktime(0, 0, 0, $month, 1, $year);
    $daysInMonth = date('t', $firstDay);
    $dayOfWeek = date('w', $firstDay);

    // Event dates for quick lookup
    $eventDates = [];
    foreach ($events as $event) {
        $eventDate = date('j', strtotime($event['date']));
        $eventDates[$eventDate] = true;
    }

    $calendar = '<div class="calendar-grid">';
    $calendar .= '<div class="calendar-day-header">Sun</div>';
    $calendar .= '<div class="calendar-day-header">Mon</div>';
    $calendar .= '<div class="calendar-day-header">Tue</div>';
    $calendar .= '<div class="calendar-day-header">Wed</div>';
    $calendar .= '<div class="calendar-day-header">Thu</div>';
    $calendar .= '<div class="calendar-day-header">Fri</div>';
    $calendar .= '<div class="calendar-day-header">Sat</div>';

    // Empty cells for days before the first day of the month
    for ($i = 0; $i < $dayOfWeek; $i++) {
        $calendar .= '<div class="calendar-day empty"></div>';
    }

    // Days of the month
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $isToday = ($day == date('j') && $month == date('n') && $year == date('Y'));
        $hasEvents = isset($eventDates[$day]);
        $classes = 'calendar-day' . ($isToday ? ' today' : '') . ($hasEvents ? ' has-events' : '');

        $calendar .= "<div class=\"$classes\" data-day=\"$day\">";
        $calendar .= "<span class=\"day-number\">$day</span>";
        if ($hasEvents) {
            $calendar .= '<div class="event-indicator"></div>';
        }
        $calendar .= '</div>';
    }

    $calendar .= '</div>';
    return $calendar;
}
?>

<div class="row g-4">
    <!-- Calendar -->
    <div class="col-xl-8">
        <div class="card dashboard-card">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <h5 class="mb-0" id="calendarTitle"><?php echo $monthName; ?></h5>
                <div class="calendar-navigation">
                    <button class="btn btn-sm btn-outline-secondary me-1" id="prevMonth">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" id="nextMonth">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="card-body-custom">
                <div class="calendar-container">
                    <?php echo generateCalendar($currentMonth, $currentYear, $events); ?>
                </div>

                <!-- Calendar Legend -->
                <div class="calendar-legend mt-3">
                    <div class="legend-item">
                        <div class="legend-color today-color"></div>
                        <span>Today</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color event-color"></div>
                        <span>Events/Bookings</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Events & Quick Actions -->
    <div class="col-xl-4">
        <div class="row g-4">
            <!-- Upcoming Events -->
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-header-custom">
                        <h5 class="mb-0">Upcoming Events</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="event-list">
                            <?php foreach ($events as $event): ?>
                            <div class="event-item mb-3">
                                <div class="event-date">
                                    <span class="event-day"><?php echo date('j', strtotime($event['date'])); ?></span>
                                    <span class="event-month"><?php echo date('M', strtotime($event['date'])); ?></span>
                                </div>
                                <div class="event-details">
                                    <h6 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h6>
                                    <p class="event-desc text-muted small mb-1"><?php echo htmlspecialchars($event['description']); ?></p>
                                    <small class="text-muted">
                                        <strong>Facility:</strong> <?php echo htmlspecialchars($event['facility']); ?>
                                    </small>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Calendar Actions -->
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-header-custom">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#newBookingModal">
                                <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2z"/>
                                </svg>
                                New Booking
                            </button>
                            <button class="btn btn-outline-custom" onclick="exportCalendar()">
                                <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                    <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 1 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.16.846.195.381.56.854 1.035 1.385zm.63 1.142a7.424 7.424 0 0 1-.99-.501 6.513 6.513 0 0 1-.29-.285c-.2-.256-.197-.51-.055-.724.26-.412.687-.29 1.055.17a6.973 6.973 0 0 1 .636.89zm-.684-3.652c.156.05.32.086.496.11a4.05 4.05 0 0 1 .777.12c.16.049.318.115.47.187a.72.72 0 0 1 .34.265c.068.093.09.2.077.307a1.44 1.44 0 0 1-.217.39.8.8 0 0 1-.263.196c-.293.118-.654.127-.885-.028a2.65 2.65 0 0 1-.616-.614 1.385 1.385 0 0 1-.144-.3c-.008-.107.02-.21.087-.29.096-.115.263-.183.52-.198a1.74 1.74 0 0 1 .46.086z"/>
                                </svg>
                                Export Calendar
                            </button>
                            <button class="btn btn-outline-custom" data-bs-toggle="modal" data-bs-target="#calendarSettingsModal">
                                <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                                    <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
                                </svg>
                                Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Booking Modal -->
<div class="modal fade" id="newBookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Facility Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form class="form-custom" id="newBookingForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="bookingFacility" class="form-label">Facility <span class="text-danger">*</span></label>
                            <select class="form-select" id="bookingFacility" name="facility" required>
                                <option value="">Select facility...</option>
                                <option value="Computer Laboratory 1">Computer Laboratory 1</option>
                                <option value="Auditorium">Auditorium</option>
                                <option value="Library Study Room A">Library Study Room A</option>
                                <option value="Gymnasium">Gymnasium</option>
                                <option value="Conference Room A">Conference Room A</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="bookingType" class="form-label">Event Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="bookingType" name="eventType" required>
                                <option value="">Select type...</option>
                                <option value="Meeting">Meeting</option>
                                <option value="Class">Class</option>
                                <option value="Event">Event</option>
                                <option value="Workshop">Workshop</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="bookingDate" class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="bookingDate" name="date" min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label for="startTime" class="form-label">Start Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="startTime" name="startTime" required>
                        </div>
                        <div class="col-md-3">
                            <label for="endTime" class="form-label">End Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="endTime" name="endTime" required>
                        </div>
                        <div class="col-12">
                            <label for="bookingTitle" class="form-label">Event Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="bookingTitle" name="title" placeholder="e.g., Student Council Meeting" required>
                        </div>
                        <div class="col-12">
                            <label for="bookingDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="bookingDescription" name="description" rows="3" placeholder="Additional details about the booking..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="expectedAttendees" class="form-label">Expected Attendees</label>
                            <input type="number" class="form-control" id="expectedAttendees" name="attendees" min="1" placeholder="e.g., 50">
                        </div>
                        <div class="col-md-6">
                            <label for="contactPerson" class="form-label">Contact Person</label>
                            <input type="text" class="form-control" id="contactPerson" name="contactPerson" placeholder="Your name">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary-custom" id="submitBookingBtn">Submit Booking</button>
            </div>
        </div>
    </div>
</div>

<!-- Calendar Settings Modal -->
<div class="modal fade" id="calendarSettingsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Calendar Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form class="form-custom">
                    <div class="mb-3">
                        <label class="form-label">Default View</label>
                        <select class="form-select">
                            <option>Monthly View</option>
                            <option>Weekly View</option>
                            <option>Daily View</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Start of Week</label>
                        <select class="form-select">
                            <option>Sunday</option>
                            <option>Monday</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="showWeekends" checked>
                            <label class="form-check-label" for="showWeekends">
                                Show weekends
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="showHolidays" checked>
                            <label class="form-check-label" for="showHolidays">
                                Show holidays
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary-custom">Save Settings</button>
            </div>
        </div>
    </div>
</div>

<script>
// Calendar functionality
document.addEventListener('DOMContentLoaded', function() {
    let currentMonth = <?php echo $currentMonth; ?>;
    let currentYear = <?php echo $currentYear; ?>;

    const calendarTitle = document.getElementById('calendarTitle');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');
    const submitBookingBtn = document.getElementById('submitBookingBtn');
    const newBookingForm = document.getElementById('newBookingForm');

    // Calendar navigation
    function updateCalendar() {
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                          'July', 'August', 'September', 'October', 'November', 'December'];
        calendarTitle.textContent = monthNames[currentMonth - 1] + ' ' + currentYear;

        // Here you would typically reload the calendar content
        // For now, we'll just update the title
    }

    prevMonthBtn.addEventListener('click', function() {
        currentMonth--;
        if (currentMonth < 1) {
            currentMonth = 12;
            currentYear--;
        }
        updateCalendar();
    });

    nextMonthBtn.addEventListener('click', function() {
        currentMonth++;
        if (currentMonth > 12) {
            currentMonth = 1;
            currentYear++;
        }
        updateCalendar();
    });

    // Submit booking
    submitBookingBtn.addEventListener('click', function() {
        if (newBookingForm.checkValidity()) {
            // Here you would send the booking data to the server
            const formData = new FormData(newBookingForm);

            // Close modal and show success message
            bootstrap.Modal.getInstance(document.getElementById('newBookingModal')).hide();
            newBookingForm.reset();

            showAlert('Booking request submitted successfully! You will receive a confirmation email shortly.', 'success');
        } else {
            newBookingForm.reportValidity();
        }
    });

    // Calendar day click handler
    document.addEventListener('click', function(e) {
        if (e.target.closest('.calendar-day') && !e.target.closest('.calendar-day.empty')) {
            const dayElement = e.target.closest('.calendar-day');
            const day = dayElement.dataset.day;

            // Here you could open a day view or quick booking modal
            console.log('Clicked day:', day);
        }
    });
});

// Global functions
function exportCalendar() {
    // Mock export functionality
    showAlert('Calendar export feature coming soon!', 'info');
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