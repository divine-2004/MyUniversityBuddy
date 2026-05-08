<?php
/**
 * Top navigation bar component for SNSU-FRMS
 */

// Get current page for breadcrumb
$currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
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

$currentTitle = isset($pageTitles[$currentPage]) ? $pageTitles[$currentPage] : 'Dashboard';

// Mock user data (replace with session data in production)
$user = [
    'name' => 'Jane Dela Cruz',
    'role' => 'Student',
    'avatar' => 'assets/icons/profile.svg'
];
?>

<!-- Top Navigation Bar -->
<header class="topbar">
    <div class="container-fluid">
        <div class="topbar-left">
            <!-- Mobile menu toggle -->
            <button class="btn btn-outline-secondary d-lg-none sidebar-toggle" id="sidebarToggle" type="button" aria-label="Toggle navigation">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                </svg>
            </button>

            <!-- Page title and breadcrumb -->
            <div>
                <h1 class="topbar-title"><?php echo htmlspecialchars($currentTitle); ?></h1>
                <p class="topbar-subtitle">SNSU Facility Request and Monitoring System</p>
            </div>
        </div>

        <div class="topbar-actions">
            <!-- Search -->
            <div class="input-group me-3" style="width: 250px;">
                <input type="text" class="form-control" placeholder="Search..." aria-label="Search">
                <button class="btn btn-outline-secondary" type="button">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                    </svg>
                </button>
            </div>

            <!-- Notifications -->
            <button class="btn btn-outline-secondary position-relative me-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2z"/>
                    <path d="M8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.57-.215 1.928.188.396.657.799 1.334.799h6.334c.677 0 1.146-.403 1.334-.799.161-.358-.055-1.161-.215-1.928C10.134 8.197 10 6.628 10 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.865 3.682c.074.264.135.543.135.818 0 2.364-.547 4.07-1.481 5.756-.393.706-.786 1.16-1.011 1.16-.225 0-.618-.454-1.011-1.16C8.547 8.89 8 7.184 8 5c0-.275.061-.554.135-.818.09-.39.255-.826.52-1.303C9.04 1.313 9.568 1 10 1c.432 0 .96.313 1.345.379.265.077.43.511.52 1.303z"/>
                </svg>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                    3
                </span>
            </button>

            <!-- User menu -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="User Avatar" class="rounded-circle me-2" width="32" height="32">
                    <div class="text-start d-none d-sm-block">
                        <div class="fw-bold" style="font-size: 0.875rem;"><?php echo htmlspecialchars($user['name']); ?></div>
                        <small class="text-muted"><?php echo htmlspecialchars($user['role']); ?></small>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="dashboard.php?page=profile">
                        <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                            <path fill-rule="evenodd" d="M8 0a5.5 5.5 0 0 0-5.5 5.5c0 2.052.895 3.858 2.276 5.069A5.5 5.5 0 0 0 8 15.5a5.5 5.5 0 0 0 3.224-1.431A5.5 5.5 0 0 0 13.5 5.5 5.5 5.5 0 0 0 8 0zM4.895 4.895a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708.708l-.646.646.646.646a.5.5 0 0 1-.708.708L6.25 6.25l-.646.646a.5.5 0 0 1-.708-.708l.646-.646-.646-.646a.5.5 0 0 1 0-.708z"/>
                        </svg>
                        Profile
                    </a></li>
                    <li><a class="dropdown-item" href="dashboard.php?page=settings">
                        <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                            <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
                        </svg>
                        Settings
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php">
                        <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                            <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708 0l-1 1a.5.5 0 0 0 .708.708L13.293 7H5.5a.5.5 0 0 0 0 1h7.793l-1.147 1.146a.5.5 0 0 0 .708.708l1-1z"/>
                        </svg>
                        Logout
                    </a></li>
                </ul>
            </div>
        </div>
    </div>
</header>