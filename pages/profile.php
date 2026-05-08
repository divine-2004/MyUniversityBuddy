<?php
/**
 * Profile Page Content
 * Displays and manages user profile information
 */

// Include database connection
require_once '../includes/database.php';

// Mock user data (replace with real database queries)
$userData = [
    'firstName' => 'Jane',
    'lastName' => 'Dela Cruz',
    'email' => 'jane.delacruz@snsu.edu.ph',
    'phone' => '+63 912 345 6789',
    'address' => 'Main Campus, Surigao del Norte',
    'studentId' => '20241234',
    'course' => 'BS Computer Science',
    'status' => 'Active Student'
];
?>

<div class="row g-4">
    <div class="col-xl-4">
        <div class="card dashboard-card">
            <div class="card-body-custom text-center">
                <img src="../assets/icons/profile.svg" alt="Profile" class="rounded-circle mb-3" width="80" height="80" style="background-color: #f8f9fa; padding: 1rem;">
                <h4><?php echo htmlspecialchars($userData['firstName'] . ' ' . $userData['lastName']); ?></h4>
                <p class="text-muted mb-1"><?php echo htmlspecialchars($userData['course']); ?></p>
                <p class="text-muted small">Student ID: <?php echo htmlspecialchars($userData['studentId']); ?></p>
                <span class="badge bg-primary"><?php echo htmlspecialchars($userData['status']); ?></span>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card dashboard-card">
            <div class="card-header-custom">
                <h5 class="mb-0">Profile Information</h5>
            </div>
            <div class="card-body-custom">
                <form class="form-custom" id="profileForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo htmlspecialchars($userData['firstName']); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo htmlspecialchars($userData['lastName']); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($userData['phone']); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="studentId" class="form-label">Student ID</label>
                            <input type="text" class="form-control" id="studentId" name="studentId" value="<?php echo htmlspecialchars($userData['studentId']); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="course" class="form-label">Course/Program</label>
                            <input type="text" class="form-control" id="course" name="course" value="<?php echo htmlspecialchars($userData['course']); ?>" readonly>
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3" readonly><?php echo htmlspecialchars($userData['address']); ?></textarea>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="button" class="btn btn-primary-custom" id="editProfileBtn">Edit Profile</button>
                        <button type="button" class="btn btn-outline-secondary d-none" id="cancelEditBtn">Cancel</button>
                        <button type="submit" class="btn btn-success d-none" id="saveProfileBtn">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Profile Edit Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form class="form-custom" id="editProfileForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="editFirstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="editFirstName" name="firstName" value="<?php echo htmlspecialchars($userData['firstName']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editLastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="editLastName" name="lastName" value="<?php echo htmlspecialchars($userData['lastName']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editPhone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="editPhone" name="phone" value="<?php echo htmlspecialchars($userData['phone']); ?>" required>
                        </div>
                        <div class="col-12">
                            <label for="editAddress" class="form-label">Address</label>
                            <textarea class="form-control" id="editAddress" name="address" rows="3" required><?php echo htmlspecialchars($userData['address']); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary-custom" id="saveProfileChangesBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
// Profile editing functionality
document.addEventListener('DOMContentLoaded', function() {
    const editProfileBtn = document.getElementById('editProfileBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const saveProfileBtn = document.getElementById('saveProfileBtn');
    const profileForm = document.getElementById('profileForm');
    const editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
    const saveProfileChangesBtn = document.getElementById('saveProfileChangesBtn');

    // Enable edit mode
    editProfileBtn.addEventListener('click', function() {
        editProfileModal.show();
    });

    // Save profile changes
    saveProfileChangesBtn.addEventListener('click', function() {
        const formData = new FormData(document.getElementById('editProfileForm'));

        // Here you would typically send the data to the server
        // For now, we'll just update the display
        document.getElementById('firstName').value = formData.get('firstName');
        document.getElementById('lastName').value = formData.get('lastName');
        document.getElementById('email').value = formData.get('email');
        document.getElementById('phone').value = formData.get('phone');
        document.getElementById('address').value = formData.get('address');

        editProfileModal.hide();

        // Show success message
        showAlert('Profile updated successfully!', 'success');
    });
});

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