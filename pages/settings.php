<?php
/**
 * Settings Page Content
 * User account settings and preferences
 */

// Include database connection
require_once '../includes/database.php';

// Mock user settings data
$userSettings = [
    'notifications' => [
        'email_notifications' => true,
        'sms_notifications' => false,
        'push_notifications' => true,
        'request_updates' => true,
        'facility_updates' => false,
        'system_announcements' => true
    ],
    'privacy' => [
        'profile_visibility' => 'public',
        'show_email' => false,
        'show_phone' => false,
        'allow_messages' => true
    ],
    'preferences' => [
        'language' => 'en',
        'timezone' => 'Asia/Manila',
        'date_format' => 'MM/DD/YYYY',
        'theme' => 'light'
    ]
];

// Mock security settings
$securitySettings = [
    'last_login' => '2024-01-15 09:30:00',
    'login_devices' => [
        ['device' => 'Windows Desktop', 'location' => 'Surigao City', 'last_active' => '2024-01-15 14:45:00', 'current' => true],
        ['device' => 'Android Phone', 'location' => 'Surigao City', 'last_active' => '2024-01-14 18:20:00', 'current' => false]
    ],
    'two_factor_enabled' => false
];
?>

<div class="row g-4">
    <!-- Account Settings -->
    <div class="col-xl-8">
        <div class="card dashboard-card">
            <div class="card-header-custom">
                <h5 class="mb-0">Account Settings</h5>
            </div>
            <div class="card-body-custom">
                <form id="accountSettingsForm">
                    <!-- Profile Information -->
                    <div class="mb-4">
                        <h6 class="mb-3">Profile Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" value="John" required>
                            </div>
                            <div class="col-md-6">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" value="Doe" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" value="john.doe@snsu.edu.ph" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" value="+63 912 345 6789">
                            </div>
                            <div class="col-12">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control" id="bio" rows="3" placeholder="Tell us about yourself...">Computer Science student at SNSU, interested in web development and system administration.</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Preferences -->
                    <div class="mb-4">
                        <h6 class="mb-3">Notification Preferences</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="emailNotifications" <?php echo $userSettings['notifications']['email_notifications'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="emailNotifications">
                                        Email Notifications
                                    </label>
                                </div>
                                <small class="text-muted">Receive notifications via email</small>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="smsNotifications" <?php echo $userSettings['notifications']['sms_notifications'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="smsNotifications">
                                        SMS Notifications
                                    </label>
                                </div>
                                <small class="text-muted">Receive notifications via SMS</small>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="pushNotifications" <?php echo $userSettings['notifications']['push_notifications'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="pushNotifications">
                                        Push Notifications
                                    </label>
                                </div>
                                <small class="text-muted">Receive push notifications in browser</small>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="requestUpdates" <?php echo $userSettings['notifications']['request_updates'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="requestUpdates">
                                        Request Updates
                                    </label>
                                </div>
                                <small class="text-muted">Get notified about request status changes</small>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="facilityUpdates" <?php echo $userSettings['notifications']['facility_updates'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="facilityUpdates">
                                        Facility Updates
                                    </label>
                                </div>
                                <small class="text-muted">Receive updates about facility availability</small>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="systemAnnouncements" <?php echo $userSettings['notifications']['system_announcements'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="systemAnnouncements">
                                        System Announcements
                                    </label>
                                </div>
                                <small class="text-muted">Important system updates and announcements</small>
                            </div>
                        </div>
                    </div>

                    <!-- Privacy Settings -->
                    <div class="mb-4">
                        <h6 class="mb-3">Privacy Settings</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="profileVisibility" class="form-label">Profile Visibility</label>
                                <select class="form-select" id="profileVisibility">
                                    <option value="public" <?php echo $userSettings['privacy']['profile_visibility'] === 'public' ? 'selected' : ''; ?>>Public</option>
                                    <option value="friends" <?php echo $userSettings['privacy']['profile_visibility'] === 'friends' ? 'selected' : ''; ?>>Friends Only</option>
                                    <option value="private" <?php echo $userSettings['privacy']['profile_visibility'] === 'private' ? 'selected' : ''; ?>>Private</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="language" class="form-label">Language</label>
                                <select class="form-select" id="language">
                                    <option value="en" <?php echo $userSettings['preferences']['language'] === 'en' ? 'selected' : ''; ?>>English</option>
                                    <option value="fil" <?php echo $userSettings['preferences']['language'] === 'fil' ? 'selected' : ''; ?>>Filipino</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="showEmail" <?php echo $userSettings['privacy']['show_email'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="showEmail">
                                        Show email address publicly
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="showPhone" <?php echo $userSettings['privacy']['show_phone'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="showPhone">
                                        Show phone number publicly
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="allowMessages" <?php echo $userSettings['privacy']['allow_messages'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="allowMessages">
                                        Allow other users to send me messages
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preferences -->
                    <div class="mb-4">
                        <h6 class="mb-3">Display Preferences</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="timezone" class="form-label">Timezone</label>
                                <select class="form-select" id="timezone">
                                    <option value="Asia/Manila" <?php echo $userSettings['preferences']['timezone'] === 'Asia/Manila' ? 'selected' : ''; ?>>Asia/Manila (GMT+8)</option>
                                    <option value="UTC" <?php echo $userSettings['preferences']['timezone'] === 'UTC' ? 'selected' : ''; ?>>UTC</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="dateFormat" class="form-label">Date Format</label>
                                <select class="form-select" id="dateFormat">
                                    <option value="MM/DD/YYYY" <?php echo $userSettings['preferences']['date_format'] === 'MM/DD/YYYY' ? 'selected' : ''; ?>>MM/DD/YYYY</option>
                                    <option value="DD/MM/YYYY" <?php echo $userSettings['preferences']['date_format'] === 'DD/MM/YYYY' ? 'selected' : ''; ?>>DD/MM/YYYY</option>
                                    <option value="YYYY-MM-DD" <?php echo $userSettings['preferences']['date_format'] === 'YYYY-MM-DD' ? 'selected' : ''; ?>>YYYY-MM-DD</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="theme" class="form-label">Theme</label>
                                <select class="form-select" id="theme">
                                    <option value="light" <?php echo $userSettings['preferences']['theme'] === 'light' ? 'selected' : ''; ?>>Light</option>
                                    <option value="dark" <?php echo $userSettings['preferences']['theme'] === 'dark' ? 'selected' : ''; ?>>Dark</option>
                                    <option value="auto" <?php echo $userSettings['preferences']['theme'] === 'auto' ? 'selected' : ''; ?>>Auto</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-outline-secondary" onclick="resetSettings()">Reset to Defaults</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Security & Account Actions -->
    <div class="col-xl-4">
        <!-- Security Settings -->
        <div class="card dashboard-card mb-4">
            <div class="card-header-custom">
                <h6 class="mb-0">Security</h6>
            </div>
            <div class="card-body-custom">
                <div class="mb-3">
                    <strong>Last Login:</strong><br>
                    <small class="text-muted"><?php echo date('M j, Y \a\t g:i A', strtotime($securitySettings['last_login'])); ?></small>
                </div>

                <div class="mb-3">
                    <strong>Two-Factor Authentication:</strong>
                    <span class="badge bg-<?php echo $securitySettings['two_factor_enabled'] ? 'success' : 'secondary'; ?> ms-2">
                        <?php echo $securitySettings['two_factor_enabled'] ? 'Enabled' : 'Disabled'; ?>
                    </span>
                    <br>
                    <button class="btn btn-sm btn-outline-primary mt-2" onclick="toggle2FA()">
                        <?php echo $securitySettings['two_factor_enabled'] ? 'Disable' : 'Enable'; ?> 2FA
                    </button>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-outline-secondary btn-sm" onclick="changePassword()">Change Password</button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="downloadData()">Download My Data</button>
                </div>
            </div>
        </div>

        <!-- Login Sessions -->
        <div class="card dashboard-card mb-4">
            <div class="card-header-custom">
                <h6 class="mb-0">Active Sessions</h6>
            </div>
            <div class="card-body-custom">
                <?php foreach ($securitySettings['login_devices'] as $device): ?>
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <strong><?php echo htmlspecialchars($device['device']); ?></strong>
                        <br>
                        <small class="text-muted">
                            <?php echo htmlspecialchars($device['location']); ?>
                            <?php if ($device['current']): ?>
                                <span class="badge bg-success ms-1">Current</span>
                            <?php endif; ?>
                        </small>
                        <br>
                        <small class="text-muted">Last active: <?php echo date('M j, Y g:i A', strtotime($device['last_active'])); ?></small>
                    </div>
                    <?php if (!$device['current']): ?>
                    <button class="btn btn-sm btn-outline-danger" onclick="revokeSession('<?php echo md5($device['device']); ?>')">Revoke</button>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="card dashboard-card border-danger">
            <div class="card-header-custom bg-danger bg-opacity-10">
                <h6 class="mb-0 text-danger">Danger Zone</h6>
            </div>
            <div class="card-body-custom">
                <p class="text-muted small mb-3">These actions cannot be undone. Please proceed with caution.</p>
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-danger btn-sm" onclick="deactivateAccount()">Deactivate Account</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteAccount()">Delete Account</button>
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
                <form id="changePasswordForm">
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="currentPassword" required>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="newPassword" required>
                        <div class="form-text">Password must be at least 8 characters long</div>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirmPassword" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="changePasswordForm" class="btn btn-primary">Change Password</button>
            </div>
        </div>
    </div>
</div>

<script>
// Settings form handling
document.getElementById('accountSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Collect form data
    const formData = new FormData(this);

    // Show success message
    showAlert('Settings saved successfully!', 'success');

    // In a real application, you would send this data to the server
    console.log('Settings saved:', Object.fromEntries(formData));
});

function resetSettings() {
    if (confirm('Are you sure you want to reset all settings to defaults? This action cannot be undone.')) {
        // Reset form to default values
        document.getElementById('profileVisibility').value = 'public';
        document.getElementById('language').value = 'en';
        document.getElementById('timezone').value = 'Asia/Manila';
        document.getElementById('dateFormat').value = 'MM/DD/YYYY';
        document.getElementById('theme').value = 'light';

        // Reset checkboxes
        const checkboxes = ['emailNotifications', 'pushNotifications', 'requestUpdates', 'systemAnnouncements', 'allowMessages'];
        checkboxes.forEach(id => {
            document.getElementById(id).checked = true;
        });

        const uncheckedBoxes = ['smsNotifications', 'facilityUpdates', 'showEmail', 'showPhone'];
        uncheckedBoxes.forEach(id => {
            document.getElementById(id).checked = false;
        });

        showAlert('Settings reset to defaults!', 'info');
    }
}

function toggle2FA() {
    const enabled = <?php echo $securitySettings['two_factor_enabled'] ? 'true' : 'false'; ?>;
    if (enabled) {
        if (confirm('Are you sure you want to disable two-factor authentication?')) {
            showAlert('Two-factor authentication disabled.', 'warning');
            // In a real app, make API call to disable 2FA
        }
    } else {
        showAlert('Two-factor authentication setup would start here.', 'info');
        // In a real app, show 2FA setup wizard
    }
}

function changePassword() {
    const modal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
    modal.show();
}

document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    if (newPassword !== confirmPassword) {
        showAlert('Passwords do not match!', 'danger');
        return;
    }

    if (newPassword.length < 8) {
        showAlert('Password must be at least 8 characters long!', 'danger');
        return;
    }

    // In a real application, send password change request to server
    showAlert('Password changed successfully!', 'success');
    bootstrap.Modal.getInstance(document.getElementById('changePasswordModal')).hide();
    this.reset();
});

function downloadData() {
    showAlert('Data export request submitted. You will receive an email when ready.', 'info');
    // In a real app, trigger data export process
}

function revokeSession(sessionId) {
    if (confirm('Are you sure you want to revoke this session?')) {
        showAlert('Session revoked successfully.', 'success');
        // In a real app, make API call to revoke session
        // Then refresh the sessions list
    }
}

function deactivateAccount() {
    if (confirm('Are you sure you want to deactivate your account? You can reactivate it later by contacting support.')) {
        showAlert('Account deactivation request submitted.', 'warning');
        // In a real app, make API call to deactivate account
    }
}

function deleteAccount() {
    if (confirm('Are you sure you want to permanently delete your account? This action cannot be undone and all your data will be lost.')) {
        if (confirm('This is your final warning. Account deletion is permanent. Are you absolutely sure?')) {
            showAlert('Account deletion request submitted. You will receive a confirmation email.', 'danger');
            // In a real app, make API call to delete account
        }
    }
}

function showAlert(message, type) {
    // Create alert element
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(alert);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}
</script>