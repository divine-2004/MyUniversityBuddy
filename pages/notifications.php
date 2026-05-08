<?php
/**
 * Notifications Page Content
 * Displays user notifications and alerts
 */

// Include database connection
require_once '../includes/database.php';

// Mock notifications data (replace with real database queries)
$notifications = [
    [
        'id' => 1,
        'type' => 'request_update',
        'title' => 'Request Approved',
        'message' => 'Your request for Computer Lab 1 has been approved.',
        'timestamp' => '2026-05-08 14:30:00',
        'status' => 'unread',
        'priority' => 'high'
    ],
    [
        'id' => 2,
        'type' => 'maintenance',
        'title' => 'Facility Maintenance',
        'message' => 'Library maintenance scheduled for May 22, 2026.',
        'timestamp' => '2026-05-07 09:15:00',
        'status' => 'read',
        'priority' => 'medium'
    ],
    [
        'id' => 3,
        'type' => 'system',
        'title' => 'System Update',
        'message' => 'FRMS has been updated to version 1.0.1.',
        'timestamp' => '2026-05-06 16:45:00',
        'status' => 'read',
        'priority' => 'low'
    ],
    [
        'id' => 4,
        'type' => 'reminder',
        'title' => 'Upcoming Event',
        'message' => 'Don\'t forget your booking for Auditorium on May 20, 2026.',
        'timestamp' => '2026-05-05 11:20:00',
        'status' => 'unread',
        'priority' => 'medium'
    ],
    [
        'id' => 5,
        'type' => 'request_update',
        'title' => 'Request Completed',
        'message' => 'Your equipment repair request has been completed.',
        'timestamp' => '2026-05-04 13:10:00',
        'status' => 'read',
        'priority' => 'high'
    ]
];

// Notification type icons
$notificationIcons = [
    'request_update' => 'clipboard-check',
    'maintenance' => 'tools',
    'system' => 'info-circle',
    'reminder' => 'bell',
    'alert' => 'exclamation-triangle'
];

// Priority classes
$priorityClasses = [
    'high' => 'border-danger',
    'medium' => 'border-warning',
    'low' => 'border-info'
];

// Time formatting function
function timeAgo($timestamp) {
    $now = new DateTime();
    $notificationTime = new DateTime($timestamp);
    $interval = $now->diff($notificationTime);

    if ($interval->d > 0) {
        return $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
    } elseif ($interval->h > 0) {
        return $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
    } elseif ($interval->i > 0) {
        return $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
    } else {
        return 'Just now';
    }
}
?>

<div class="row g-4">
    <!-- Notifications List -->
    <div class="col-xl-8">
        <div class="card dashboard-card">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Notifications</h5>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="markAllRead">Mark All Read</button>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="clearAll">Clear All</button>
                </div>
            </div>
            <div class="card-body-custom">
                <!-- Filters -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <select class="form-select form-select-sm" id="statusFilter">
                            <option value="">All Notifications</option>
                            <option value="unread">Unread Only</option>
                            <option value="read">Read Only</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select form-select-sm" id="typeFilter">
                            <option value="">All Types</option>
                            <option value="request_update">Request Updates</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="system">System</option>
                            <option value="reminder">Reminders</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select form-select-sm" id="priorityFilter">
                            <option value="">All Priorities</option>
                            <option value="high">High Priority</option>
                            <option value="medium">Medium Priority</option>
                            <option value="low">Low Priority</option>
                        </select>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="notification-list" id="notificationsContainer">
                    <?php foreach ($notifications as $notification): ?>
                    <div class="notification-item <?php echo $notification['status'] === 'unread' ? 'unread' : ''; ?> <?php echo $priorityClasses[$notification['priority']] ?? ''; ?>"
                         data-id="<?php echo $notification['id']; ?>"
                         data-status="<?php echo $notification['status']; ?>"
                         data-type="<?php echo $notification['type']; ?>"
                         data-priority="<?php echo $notification['priority']; ?>">
                        <div class="notification-icon">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <?php
                                $icon = $notificationIcons[$notification['type']] ?? 'info-circle';
                                switch ($icon) {
                                    case 'clipboard-check':
                                        echo '<path fill-rule="evenodd" d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/><path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/><path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>';
                                        break;
                                    case 'tools':
                                        echo '<path d="M1 0 0 1l2.2 3.081a1 1 0 0 0 .815.419h.07a1 1 0 0 1 .708.293l2.675 2.675-2.617 2.654A3.003 3.003 0 0 0 0 13.917V14a2 2 0 1 0 4 0v-.083a3.003 3.003 0 0 0-2.207-2.709l2.594-2.63.793.793a1 1 0 0 0 1.414 0l2.675-2.675a1 1 0 0 0 .293-.708v-.07a1 1 0 0 0-.419-.814L4 2.02 1 0zm9.646 10.646a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708zM8 3a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 0 1h-.5v.5a.5.5 0 0 1-1 0v-.5h-.5a.5.5 0 0 1 0-1h.5v-.5A.5.5 0 0 1 8 3z"/>';
                                        break;
                                    case 'bell':
                                        echo '<path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2z"/><path d="M8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.57-.215 1.928.188.396.657.799 1.334.799h6.334c.677 0 1.146-.403 1.334-.799.161-.358-.055-1.161-.215-1.928C10.134 8.197 10 6.628 10 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917z"/>';
                                        break;
                                    case 'exclamation-triangle':
                                        echo '<path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/><path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>';
                                        break;
                                    default:
                                        echo '<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M5 6.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0zm1.138-1.496a.75.75 0 1 0-.976 1.138l.75.75a.75.75 0 0 0 1.138-.976l-.75-.75zm2.226 0a.75.75 0 1 0-.976-1.138l-.75.75a.75.75 0 0 0 1.138.976l.75-.75z"/>';
                                }
                                ?>
                            </svg>
                        </div>
                        <div class="notification-content">
                            <h6 class="notification-title"><?php echo htmlspecialchars($notification['title']); ?></h6>
                            <p class="notification-message"><?php echo htmlspecialchars($notification['message']); ?></p>
                            <small class="notification-time"><?php echo timeAgo($notification['timestamp']); ?></small>
                        </div>
                        <div class="notification-actions">
                            <?php if ($notification['status'] === 'unread'): ?>
                            <button class="btn btn-sm btn-outline-primary me-1" onclick="markAsRead(<?php echo $notification['id']; ?>)">Mark Read</button>
                            <?php endif; ?>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteNotification(<?php echo $notification['id']; ?>)">Delete</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Load More -->
                <div class="text-center mt-4">
                    <button class="btn btn-outline-primary" id="loadMoreBtn">Load More Notifications</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Settings & Summary -->
    <div class="col-xl-4">
        <div class="row g-4">
            <!-- Notification Summary -->
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-header-custom">
                        <h5 class="mb-0">Summary</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="notification-stats">
                            <div class="stat-item">
                                <div class="stat-number text-primary"><?php echo count(array_filter($notifications, fn($n) => $n['status'] === 'unread')); ?></div>
                                <div class="stat-label">Unread</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number text-success"><?php echo count(array_filter($notifications, fn($n) => $n['type'] === 'request_update')); ?></div>
                                <div class="stat-label">Requests</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number text-warning"><?php echo count(array_filter($notifications, fn($n) => $n['type'] === 'maintenance')); ?></div>
                                <div class="stat-label">Maintenance</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number text-info"><?php echo count($notifications); ?></div>
                                <div class="stat-label">Total</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-header-custom">
                        <h5 class="mb-0">Settings</h5>
                    </div>
                    <div class="card-body-custom">
                        <form class="form-custom">
                            <h6 class="mb-3">Email Notifications</h6>
                            <div class="mb-3">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="emailRequests" checked>
                                    <label class="form-check-label" for="emailRequests">
                                        Request updates
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="emailMaintenance" checked>
                                    <label class="form-check-label" for="emailMaintenance">
                                        Maintenance schedules
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="emailSystem" checked>
                                    <label class="form-check-label" for="emailSystem">
                                        System announcements
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="emailReminders">
                                    <label class="form-check-label" for="emailReminders">
                                        Event reminders
                                    </label>
                                </div>
                            </div>

                            <h6 class="mb-3">Push Notifications</h6>
                            <div class="mb-3">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="pushRequests" checked>
                                    <label class="form-check-label" for="pushRequests">
                                        Request updates
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="pushUrgent" checked>
                                    <label class="form-check-label" for="pushUrgent">
                                        Urgent notifications only
                                    </label>
                                </div>
                            </div>

                            <h6 class="mb-3">Frequency</h6>
                            <div class="mb-3">
                                <select class="form-select">
                                    <option>Real-time</option>
                                    <option>Daily digest</option>
                                    <option>Weekly summary</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary-custom w-100">Save Preferences</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Notification management functionality
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    const priorityFilter = document.getElementById('priorityFilter');
    const markAllRead = document.getElementById('markAllRead');
    const clearAll = document.getElementById('clearAll');
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const notificationsContainer = document.getElementById('notificationsContainer');

    function filterNotifications() {
        const statusValue = statusFilter.value;
        const typeValue = typeFilter.value;
        const priorityValue = priorityFilter.value;

        const notifications = notificationsContainer.querySelectorAll('.notification-item');

        notifications.forEach(notification => {
            const status = notification.dataset.status;
            const type = notification.dataset.type;
            const priority = notification.dataset.priority;

            const statusMatch = !statusValue || status === statusValue;
            const typeMatch = !typeValue || type === typeValue;
            const priorityMatch = !priorityValue || priority === priorityValue;

            notification.style.display = (statusMatch && typeMatch && priorityMatch) ? '' : 'none';
        });
    }

    statusFilter.addEventListener('change', filterNotifications);
    typeFilter.addEventListener('change', filterNotifications);
    priorityFilter.addEventListener('change', filterNotifications);

    // Mark all as read
    markAllRead.addEventListener('click', function() {
        const unreadNotifications = notificationsContainer.querySelectorAll('.notification-item.unread');
        unreadNotifications.forEach(notification => {
            notification.classList.remove('unread');
            notification.dataset.status = 'read';
        });

        showAlert('All notifications marked as read.', 'success');
    });

    // Clear all notifications
    clearAll.addEventListener('click', function() {
        if (confirm('Are you sure you want to clear all notifications? This action cannot be undone.')) {
            notificationsContainer.innerHTML = '<div class="text-center text-muted py-5">No notifications to display.</div>';
            showAlert('All notifications cleared.', 'info');
        }
    });

    // Load more (mock functionality)
    loadMoreBtn.addEventListener('click', function() {
        loadMoreBtn.disabled = true;
        loadMoreBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';

        setTimeout(() => {
            loadMoreBtn.disabled = false;
            loadMoreBtn.innerHTML = 'Load More Notifications';
            showAlert('No more notifications to load.', 'info');
        }, 2000);
    });
});

// Global functions for notification actions
function markAsRead(notificationId) {
    const notification = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
    if (notification) {
        notification.classList.remove('unread');
        notification.dataset.status = 'read';
        showAlert('Notification marked as read.', 'success');
    }
}

function deleteNotification(notificationId) {
    const notification = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
    if (notification) {
        if (confirm('Are you sure you want to delete this notification?')) {
            notification.remove();
            showAlert('Notification deleted.', 'info');
        }
    }
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