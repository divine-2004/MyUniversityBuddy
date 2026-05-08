<?php
/**
 * Sidebar navigation component for SNSU-FRMS
 */

// Define navigation menu items
$menuItems = [
    'dashboard' => [
        'label' => 'Dashboard',
        'icon' => 'assets/icons/dashboard.svg',
        'url' => 'dashboard.php?page=dashboard'
    ],
    'profile' => [
        'label' => 'My Profile',
        'icon' => 'assets/icons/profile.svg',
        'url' => 'dashboard.php?page=profile'
    ],
    'requests' => [
        'label' => 'My Requests',
        'icon' => 'assets/icons/requests.svg',
        'url' => 'dashboard.php?page=requests'
    ],
    'facility' => [
        'label' => 'Facility List',
        'icon' => 'assets/icons/facility.svg',
        'url' => 'dashboard.php?page=facility'
    ],
    'calendar' => [
        'label' => 'Calendar',
        'icon' => 'assets/icons/calendar.svg',
        'url' => 'dashboard.php?page=calendar'
    ],
    'notifications' => [
        'label' => 'Notifications',
        'icon' => 'assets/icons/notifications.svg',
        'url' => 'dashboard.php?page=notifications'
    ],
    'reports' => [
        'label' => 'Reports',
        'icon' => 'assets/icons/reports.svg',
        'url' => 'dashboard.php?page=reports'
    ],
    'faq' => [
        'label' => 'FAQ',
        'icon' => 'assets/icons/faq.svg',
        'url' => 'dashboard.php?page=faq'
    ],
    'settings' => [
        'label' => 'Settings',
        'icon' => 'assets/icons/settings.svg',
        'url' => 'dashboard.php?page=settings'
    ]
];

// Get current page
$currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <span>SNSU</span>
        </div>
        <div>
            <h2 class="sidebar-title">FRMS</h2>
            <p class="sidebar-subtitle">Facility Request System</p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <?php foreach ($menuItems as $key => $item): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($currentPage === $key) ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($item['url']); ?>">
                        <img class="nav-icon" src="<?php echo htmlspecialchars($item['icon']); ?>" alt="<?php echo htmlspecialchars($item['label']); ?>" width="20" height="20">
                        <span><?php echo htmlspecialchars($item['label']); ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="text-center">
            <small class="text-muted">Version 1.0.0</small>
        </div>
    </div>
</aside>

<!-- Mobile Sidebar Overlay -->
<div class="sidebar-overlay d-lg-none" id="sidebarOverlay"></div>