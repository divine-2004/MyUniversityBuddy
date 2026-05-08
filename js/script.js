const STORAGE_KEYS = {
  auth: 'loggedIn',
  role: 'userRole',
  profile: 'profileData',
  requests: 'facilityRequests',
  notifications: 'fmrmsNotifications',
  help: 'helpMessages',
  faqs: 'fmrmsFaqs',
  adminUsers: 'fmrmsAdminUsers',
  facilities: 'fmrmsFacilities',
  adminSettings: 'fmrmsAdminSettings',
};

function debounce(fn, delay) {
  let timeout;
  return (...args) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => fn(...args), delay);
  };
}

function getProfile() {
  const raw = localStorage.getItem(STORAGE_KEYS.profile);
  try {
    return raw ? JSON.parse(raw) : {};
  } catch {
    return {};
  }
}

function getDefaultProfilePhoto() {
  return 'data:image/svg+xml;charset=UTF-8,' +
    encodeURIComponent(
      `<svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 120 120"><defs><linearGradient id="g" x1="0" x2="1" y1="0" y2="1"><stop offset="0%" stop-color="#60a5fa"/><stop offset="100%" stop-color="#3b82f6"/></linearGradient></defs><rect width="120" height="120" rx="24" fill="#e7f2ff"/><circle cx="60" cy="48" r="28" fill="#fff" stroke="#dbeafe" stroke-width="4"/><path d="M22 104c0-18 14-32 33-32s33 14 33 32" fill="none" stroke="#dbeafe" stroke-width="4" stroke-linecap="round"/></svg>`
    );
}

function getRootPrefix() {
  return window.location.pathname.includes('/admin/') ? '../' : '';
}

function getDashboardPathForRole(role) {
  return role === 'ADMIN' ? `${getRootPrefix()}admin.html` : `${getRootPrefix()}index.html`;
}

function requireAuth() {
  if (window.location.pathname.endsWith('login.html')) {
    return;
  }

  if (localStorage.getItem(STORAGE_KEYS.auth) !== 'true') {
    window.location = `${getRootPrefix()}login.html`;
  }
}

function getUserRole() {
  const role = localStorage.getItem(STORAGE_KEYS.role);
  return role === 'ADMIN' ? 'ADMIN' : 'STUDENT';
}

function isAdminPage() {
  return window.location.pathname.endsWith('/admin.html') ||
    window.location.pathname.endsWith('/user-management.html') ||
    window.location.pathname.endsWith('/facility-management.html') ||
    window.location.pathname.endsWith('/requests.html') ||
    window.location.pathname.endsWith('/requests-oversight.html') ||
    window.location.pathname.endsWith('/reports.html') ||
    window.location.pathname.endsWith('/reports-analytics.html') ||
    window.location.pathname.endsWith('/admin-notifications.html') ||
    window.location.pathname.endsWith('/admin-settings.html') ||
    window.location.pathname.includes('/admin/') ||
    window.location.pathname.endsWith('admin/index.html');
}

function getAdminNavItems(prefix = '') {
  return [
    { key: 'dashboard', href: `${prefix}admin.html`, icon: 'tachometer-alt', label: 'Dashboard' },
    { key: 'users', href: `${prefix}user-management.html`, icon: 'users', label: 'User Management' },
    { key: 'facilities', href: `${prefix}facility-management.html`, icon: 'building', label: 'Facility Management' },
    { key: 'requests', href: `${prefix}requests.html`, icon: 'clipboard-list', label: 'Requests Oversight' },
    { key: 'reports', href: `${prefix}reports.html`, icon: 'chart-bar', label: 'Reports & Analytics' },
    { key: 'notifications', href: `${prefix}admin-notifications.html`, icon: 'bell', label: 'Notifications' },
    { key: 'settings', href: `${prefix}admin-settings.html`, icon: 'cog', label: 'Settings' },
  ];
}

function getActiveAdminSection() {
  const page = window.location.pathname.split('/').pop();
  switch (page) {
    case 'user-management.html':
      return 'users';
    case 'facility-management.html':
      return 'facilities';
    case 'requests.html':
    case 'requests-oversight.html':
      return 'requests';
    case 'reports.html':
    case 'reports-analytics.html':
      return 'reports';
    case 'admin-notifications.html':
      return 'notifications';
    case 'admin-settings.html':
      return 'settings';
    default:
      return 'dashboard';
  }
}

function initializeAdminShell(activeSection = getActiveAdminSection()) {
  localStorage.setItem(STORAGE_KEYS.role, 'ADMIN');
  applyAdminSettings();

  const prefix = getRootPrefix();
  const profile = getProfile();
  const adminName = profile.adminName || 'Juan Miguel Reyes';
  const adminAvatar = profile.adminAvatar || profile.photo || getDefaultProfilePhoto();
  const navItems = getAdminNavItems(prefix);

  const headerMarkup = `
    <header class="admin-topbar">
      <div class="admin-topbar-left">
        <img class="admin-logo" src="${prefix}images/snsu logo.png" alt="SNSU Logo">
        <div class="admin-info">
          <div class="admin-name" id="adminName">${escapeHtml(adminName)}</div>
          <div class="admin-role">Administrator</div>
        </div>
      </div>

      <div class="admin-topbar-center">
        <div class="search-container">
          <i class="fas fa-search search-icon"></i>
          <input type="text" class="search-input" placeholder="Search requests, users, facilities..." id="adminSearch">
        </div>
      </div>

      <div class="admin-topbar-right">
        <div class="admin-avatar-container">
          <img id="adminAvatar" class="admin-avatar" src="${adminAvatar}" alt="Admin Avatar">
          <div class="admin-status online"></div>
        </div>
      </div>
    </header>
  `;

  const sidebarMarkup = `
    <nav class="sidebar admin-sidebar" id="sidebar">
      <div class="sidebar-top">
        <div class="profile-block">
          <img id="sidebarProfilePhoto" class="sidebar-avatar" src="${adminAvatar}" alt="Profile">
          <div class="profile-info">
            <div class="profile-name" id="sidebarProfileName">${escapeHtml(adminName)}</div>
            <div class="profile-meta" id="sidebarProfileMeta">System Admin</div>
          </div>
        </div>

        <ul class="nav">
          ${navItems.map((item) => `
            <li>
              <a href="${item.href}" class="${item.key === activeSection ? 'active' : ''}">
                <i class="fas fa-${item.icon} nav-icon"></i>
                <span class="nav-label">${item.label}</span>
              </a>
            </li>
          `).join('')}
        </ul>
      </div>

      <div class="sidebar-footer">
        <button class="logout-btn" type="button" onclick="logout()">
          <i class="fas fa-sign-out-alt nav-icon"></i>
          <span class="nav-label">Logout</span>
        </button>
      </div>
    </nav>
  `;

  const existingHeader = document.querySelector('.admin-topbar');
  if (existingHeader) {
    existingHeader.outerHTML = headerMarkup;
  } else {
    document.body.insertAdjacentHTML('afterbegin', headerMarkup);
  }

  const layout = document.querySelector('.admin-layout');
  const existingSidebar = document.querySelector('.admin-sidebar');
  if (existingSidebar) {
    existingSidebar.outerHTML = sidebarMarkup;
  } else if (layout) {
    layout.insertAdjacentHTML('afterbegin', sidebarMarkup);
  }

  initializeAdminSearch(prefix);
}

function initializeAdminSearch(prefix = '') {
  const searchInput = document.getElementById('adminSearch');
  if (!searchInput) return;

  searchInput.addEventListener('input', debounce((event) => {
    const query = event.target.value.trim();
    if (query.length > 2) {
      displayAdminSearchResults(getAdminSearchResults(query, prefix));
    } else {
      clearSearchResults();
    }
  }, 300));

  searchInput.addEventListener('keydown', (event) => {
    if (event.key !== 'Enter') return;
    const firstResult = document.querySelector('.search-result-item');
    if (firstResult) firstResult.click();
  });
}

function getAdminSearchResults(query, prefix = '') {
  const normalizedQuery = query.toLowerCase();
  const results = [];
  const requests = getRequests();

  requests.forEach((request) => {
    const haystack = [request.title, request.location, request.description, request.status].filter(Boolean).join(' ').toLowerCase();
    if (haystack.includes(normalizedQuery)) {
      results.push({
        type: 'request',
        title: request.title || request.location || 'Facility request',
        subtitle: `Request #${request.id} - ${request.status || 'Pending'}`,
        url: `${prefix}requests.html`,
      });
    }
  });

  if ('user management accounts staff student administrator'.includes(normalizedQuery) || normalizedQuery.includes('user')) {
    results.push({ type: 'user', title: 'User Management', subtitle: 'Manage admin, staff, and student accounts', url: `${prefix}user-management.html` });
  }

  if ('facility building room location availability'.includes(normalizedQuery) || normalizedQuery.includes('facility') || normalizedQuery.includes('room')) {
    results.push({ type: 'facility', title: 'Facility Management', subtitle: 'Manage locations and availability', url: `${prefix}facility-management.html` });
  }

  if ('reports analytics charts trends'.includes(normalizedQuery) || normalizedQuery.includes('report') || normalizedQuery.includes('chart')) {
    results.push({ type: 'report', title: 'Reports & Analytics', subtitle: 'View usage and request trends', url: `${prefix}reports.html` });
  }

  return results.slice(0, 8);
}

function displayAdminSearchResults(results) {
  clearSearchResults();
  if (!results.length) return;

  const resultsContainer = document.createElement('div');
  resultsContainer.className = 'search-results';

  results.forEach((result) => {
    const resultItem = document.createElement('div');
    resultItem.className = 'search-result-item';
    resultItem.addEventListener('click', () => {
      window.location.href = result.url;
    });

    resultItem.innerHTML = `
      <div class="search-result-icon">
        <i class="fas fa-${getSearchResultIcon(result.type)}"></i>
      </div>
      <div class="search-result-content">
        <div class="search-result-title">${escapeHtml(result.title)}</div>
        <div class="search-result-subtitle">${escapeHtml(result.subtitle)}</div>
      </div>
    `;

    resultsContainer.appendChild(resultItem);
  });

  const searchContainer = document.querySelector('.search-container');
  if (searchContainer) searchContainer.appendChild(resultsContainer);
}

function getSearchResultIcon(type) {
  switch (type) {
    case 'request': return 'clipboard-list';
    case 'user': return 'user';
    case 'facility': return 'building';
    case 'report': return 'chart-bar';
    default: return 'search';
  }
}

function clearSearchResults() {
  const results = document.querySelector('.search-results');
  if (results) results.remove();
}

function updateAdminNotificationBadge() {
  const badge = document.getElementById('adminNotifBadge');
  if (!badge) return;

  const notifications = getNotifications();
  const unreadCount = notifications.filter((note) => !note.read).length || notifications.length;
  if (unreadCount > 0) {
    badge.textContent = unreadCount > 99 ? '99+' : String(unreadCount);
    badge.style.display = 'flex';
  } else {
    badge.style.display = 'none';
  }
}

function initializePage() {
  const role = getUserRole();
  const isAdmin = isAdminPage();

  // Update profile display based on role
  updateProfileDisplay(role);

  // Initialize appropriate dashboard
  if (isAdmin) {
    initializeAdminDashboard();
  } else {
    initializeStudentDashboard();
  }
}

function updateProfileDisplay(role) {
  const profileName = document.getElementById('sidebarProfileName');
  const profileMeta = document.getElementById('sidebarProfileMeta');
  const userName = document.getElementById('userName');
  const userId = document.getElementById('userId');

  if (role === 'ADMIN') {
    if (profileName) profileName.textContent = 'Administrator';
    if (profileMeta) profileMeta.textContent = 'System Admin';
    if (userName) userName.textContent = 'Administrator';
    if (userId) userId.textContent = 'Role: System Administrator';
  } else {
    const profile = getProfile();
    const name = profile.name || 'Student';
    const studentId = profile.studentId || '—';

    if (profileName) profileName.textContent = name.split(' ')[0] || 'Student';
    if (profileMeta) profileMeta.textContent = studentId;
    if (userName) userName.textContent = name;
    if (userId) userId.textContent = `ID: ${studentId}`;
  }
}

function initializeAdminDashboard() {
  // Load admin-specific data
  loadAdminData();

  // Initialize admin charts
  renderAdminCharts();

  // Load admin activity
  renderAdminActivity();

  // Render reusable admin tables when they are present on the dashboard.
  renderUserManagement();
  renderFacilityManagement();
  renderAdminRequestsTable();
}

function initializeAdminUsersPage() {
  initializeAdminShell('users');
  requireAuth();
  renderUserManagement();
}

function initializeAdminFacilitiesPage() {
  initializeAdminShell('facilities');
  requireAuth();
  renderFacilityManagement();
}

function initializeAdminRequestsPage() {
  initializeAdminShell('requests');
  requireAuth();
  renderAdminRequestsTable();
}

function initializeAdminReportsPage() {
  initializeAdminShell('reports');
  requireAuth();
  renderAdminCharts();
}

function initializeAdminNotificationsPage() {
  initializeAdminShell('notifications');
  requireAuth();
  renderAdminNotifications();
}

function initializeAdminSettingsPage() {
  initializeAdminShell('settings');
  requireAuth();
  initializeSettingsTabs();
  renderAdminSettings();
}

function initializeStudentDashboard() {
  applyAdminSettings();

  // Load student dashboard data
  updateDashboardStats();
  updateDashboardUserInfo();

  // Initialize student charts
  renderCharts();

  // Load student activity
  renderRecentRequests();
  renderActivityFeed();
}

function loadAdminData() {
  applyAdminSettings();

  // Update admin summary cards with data from localStorage
  const requests = getRequests();

  // Total Users (mock data - in real app, would come from API)
  const totalUsersEl = document.getElementById('totalUsers');
  if (totalUsersEl) totalUsersEl.textContent = '156';

  // Total Facilities (mock data - in real app, would come from API)
  const totalFacilitiesEl = document.getElementById('totalFacilities');
  if (totalFacilitiesEl) totalFacilitiesEl.textContent = '24';

  // Pending Requests
  const pendingEl = document.getElementById('pendingRequests');
  if (pendingEl) {
    const pending = requests.filter(r => r.status === 'Pending').length;
    pendingEl.textContent = pending;
  }

  // In Progress Requests
  const inProgressEl = document.getElementById('inProgressRequests');
  if (inProgressEl) {
    const inProgress = requests.filter(r => r.status === 'In Progress').length;
    inProgressEl.textContent = inProgress;
  }

  // Completed Requests
  const completedEl = document.getElementById('completedRequests');
  if (completedEl) {
    const completed = requests.filter(r => r.status === 'Completed').length;
    completedEl.textContent = completed;
  }

  // Cancelled Requests
  const cancelledEl = document.getElementById('cancelledRequests');
  if (cancelledEl) {
    const cancelled = requests.filter(r => r.status === 'Cancelled').length;
    cancelledEl.textContent = cancelled;
  }
}

function renderAdminCharts() {
  if (typeof Chart === 'undefined') return;

  const requests = getRequests();
  const requestTrendLabels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
  const trendSeed = requests.length || 42;
  const submittedData = requestTrendLabels.map((_, index) => Math.max(4, Math.round(trendSeed / 4) + index * 2));
  const approvedData = submittedData.map((value, index) => Math.max(2, value - (index % 2 === 0 ? 3 : 5)));
  const completedData = submittedData.map((value, index) => Math.max(1, value - (index % 2 === 0 ? 5 : 7)));

  const trendsCanvas = document.getElementById('requestTrendsChart');
  if (trendsCanvas) {
    if (window.adminRequestTrendsChart) {
      window.adminRequestTrendsChart.destroy();
    }

    const ctx = trendsCanvas.getContext('2d');
    window.adminRequestTrendsChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: requestTrendLabels,
        datasets: [{
          label: 'Submitted',
          data: submittedData,
          borderColor: '#3498DB',
          backgroundColor: 'rgba(52, 152, 219, 0.12)',
          fill: true,
          tension: 0.35
        }, {
          label: 'Approved',
          data: approvedData,
          borderColor: '#27AE60',
          backgroundColor: 'rgba(39, 174, 96, 0.08)',
          tension: 0.35
        }, {
          label: 'Completed',
          data: completedData,
          borderColor: '#2C3E50',
          backgroundColor: 'rgba(44, 62, 80, 0.08)',
          tension: 0.35
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
          },
          tooltip: {
            mode: 'index',
            intersect: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              precision: 0
            }
          }
        }
      }
    });
  }

  const facilityUsageCanvas = document.getElementById('facilityUsageChart');
  if (facilityUsageCanvas) {
    if (window.adminFacilityUsageChart) {
      window.adminFacilityUsageChart.destroy();
    }

    const usageCounts = requests.reduce((acc, request) => {
      const facility = request.location || request.title || 'General Facility';
      acc[facility] = (acc[facility] || 0) + 1;
      return acc;
    }, {});
    const usageEntries = Object.entries(usageCounts).sort((a, b) => b[1] - a[1]).slice(0, 5);
    const usageLabels = usageEntries.length
      ? usageEntries.map(([facility]) => facility)
      : ['Computer Lab A', 'Main Auditorium', 'Library Room', 'Gymnasium', 'Science Lab'];
    const usageData = usageEntries.length
      ? usageEntries.map(([, count]) => count)
      : [32, 24, 18, 14, 12];

    const ctx = facilityUsageCanvas.getContext('2d');
    window.adminFacilityUsageChart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: usageLabels,
        datasets: [{
          data: usageData,
          backgroundColor: [
            '#3498DB',
            '#27AE60',
            '#F1C40F',
            '#E74C3C',
            '#2C3E50'
          ]
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
          }
        }
      }
    });
  }
}

function renderAdminActivity() {
  const feed = document.getElementById('adminActivityFeed');
  if (!feed) return;

  const requests = getRequests();
  const fallbackActivity = [
    {
      title: 'Request approved',
      detail: 'Computer Lab A maintenance request',
      timestamp: Date.now() - 1000 * 60 * 12,
      type: 'approval',
    },
    {
      title: 'Request rejected',
      detail: 'Auditorium reservation conflict',
      timestamp: Date.now() - 1000 * 60 * 46,
      type: 'cancellation',
    },
    {
      title: 'New facility added',
      detail: 'Library Conference Room',
      timestamp: Date.now() - 1000 * 60 * 90,
      type: 'update',
    },
  ];

  const requestActivity = requests
    .slice()
    .sort((a, b) => new Date(b.updatedAt || b.reportedAt || 0) - new Date(a.updatedAt || a.reportedAt || 0))
    .slice(0, 8)
    .map((request) => {
      const status = request.status || 'Pending';
      return {
        title: getActionFromStatus(status),
        detail: request.location || request.title || 'Facility request',
        timestamp: new Date(request.updatedAt || request.reportedAt || Date.now()).getTime(),
        type: getActivityTypeFromStatus(status),
      };
    });

  const activityItems = requestActivity.length ? requestActivity : fallbackActivity;

  feed.innerHTML = activityItems.map((item) => `
    <div class="activity-item">
      <div class="activity-item-header">
        <span class="activity-icon ${item.type}">
          <i class="fas fa-${getActivityIcon(item.type)}"></i>
        </span>
        <div class="activity-content">
          <h4>${escapeHtml(item.title)}</h4>
          <p>${escapeHtml(item.detail)}</p>
        </div>
      </div>
      <div class="activity-timestamp">${getTimeAgo(item.timestamp)} - ${new Date(item.timestamp).toLocaleString()}</div>
    </div>
  `).join('');
}

function getActionFromStatus(status) {
  switch (status) {
    case 'Pending': return 'New request submitted';
    case 'Approved': return 'Request approved';
    case 'Rejected': return 'Request rejected';
    case 'In Progress': return 'Facility request updated';
    case 'Completed': return 'Request completed';
    case 'Cancelled': return 'Request cancelled';
    default: return 'Updated Status';
  }
}

function getActivityTypeFromStatus(status) {
  switch (status) {
    case 'Approved':
    case 'Completed':
      return 'approval';
    case 'Rejected':
    case 'Cancelled':
      return 'cancellation';
    case 'Pending':
      return 'submission';
    default:
      return 'update';
  }
}

function getStatusBadgeClass(status) {
  const normalized = String(status || '').toLowerCase();
  if (['active', 'available', 'approved', 'completed'].includes(normalized)) return 'status-completed';
  if (['pending'].includes(normalized)) return 'status-pending';
  if (['in use', 'in progress'].includes(normalized)) return 'status-inprogress';
  if (['inactive', 'unavailable', 'rejected', 'cancelled'].includes(normalized)) return 'status-cancelled';
  return 'status-inprogress';
}

function applyAdminSettings() {
  const settings = getAdminSettings();
  const root = document.documentElement;
  const body = document.body;

  if (settings.accentColor) {
    root.style.setProperty('--accent', settings.accentColor);
    root.style.setProperty('--primary-2', settings.accentColor);
  }

  if (body) {
    body.classList.toggle('density-compact', settings.dashboardDensity === 'Compact');
  }

  const activityPanel = document.querySelector('.admin-activity-panel');
  if (activityPanel) {
    activityPanel.style.display = settings.showActivityPanel === false ? 'none' : '';
  }

  const metricSections = document.querySelectorAll('.admin-summary-grid, .metrics-list');
  metricSections.forEach((section) => {
    section.style.display = settings.showMetricCards === false ? 'none' : '';
  });
}

function canManageUsers() {
  return getAdminSettings().permManageUsers !== false;
}

function canManageFacilities() {
  return getAdminSettings().permManageFacilities !== false;
}

function canApproveRequests() {
  return getAdminSettings().permApproveRequests !== false;
}

function canExportReports() {
  return getAdminSettings().permExportReports !== false;
}

function renderUserManagement() {
  const tbody = document.getElementById('userManagementBody');
  if (!tbody) return;

  const users = getAdminUsers();
  tbody.innerHTML = users.map((user) => `
    <tr>
      <td>${escapeHtml(user.id)}</td>
      <td>${escapeHtml(user.name)}</td>
      <td>${escapeHtml(user.role)}</td>
      <td><span class="status-badge ${getStatusBadgeClass(user.status)}">${escapeHtml(user.status)}</span></td>
      <td>
        <div class="action-buttons">
          <button class="edit" type="button" onclick="editAdminUser('${escapeHtml(user.id)}')">Edit</button>
          <button class="delete" type="button" onclick="deleteAdminUser('${escapeHtml(user.id)}')">Delete</button>
        </div>
      </td>
    </tr>
  `).join('');
}

function addAdminUser() {
  if (!canManageUsers()) {
    alert('User management changes are disabled in Settings.');
    return;
  }

  const name = prompt('User name:');
  if (!name) return;
  const role = prompt('Role:', 'Student') || 'Student';
  const status = prompt('Status:', 'Active') || 'Active';
  const users = getAdminUsers();
  users.push({
    id: `USR-${Date.now().toString().slice(-3)}`,
    name: name.trim(),
    role: role.trim(),
    status: status.trim(),
  });
  saveAdminUsers(users);
  addNotification(`User added: ${name.trim()}`);
  renderUserManagement();
}

function editAdminUser(id) {
  if (!canManageUsers()) {
    alert('User management changes are disabled in Settings.');
    return;
  }

  const users = getAdminUsers();
  const user = users.find((item) => item.id === id);
  if (!user) return;

  const name = prompt('User name:', user.name);
  if (name === null) return;
  const role = prompt('Role:', user.role);
  if (role === null) return;
  const status = prompt('Status:', user.status);
  if (status === null) return;

  user.name = name.trim() || user.name;
  user.role = role.trim() || user.role;
  user.status = status.trim() || user.status;
  saveAdminUsers(users);
  addNotification(`User updated: ${user.name}`);
  renderUserManagement();
}

function deleteAdminUser(id) {
  if (!canManageUsers()) {
    alert('User deletion is disabled in Settings.');
    return;
  }

  const users = getAdminUsers();
  const user = users.find((item) => item.id === id);
  if (!user) return;
  if (!confirm(`Delete user ${user.name}?`)) return;

  saveAdminUsers(users.filter((item) => item.id !== id));
  addNotification(`User deleted: ${user.name}`);
  renderUserManagement();
}

function renderFacilityManagement() {
  const tbody = document.getElementById('facilityManagementBody');
  if (!tbody) return;

  const facilities = getFacilities();
  tbody.innerHTML = facilities.map((facility) => `
    <tr>
      <td>${escapeHtml(facility.id)}</td>
      <td>${escapeHtml(facility.name)}</td>
      <td>${escapeHtml(facility.location)}</td>
      <td><span class="status-badge ${getStatusBadgeClass(facility.availability)}">${escapeHtml(facility.availability)}</span></td>
      <td>
        <div class="action-buttons">
          <button class="edit" type="button" onclick="editFacility('${escapeHtml(facility.id)}')">Edit</button>
          <button class="delete" type="button" onclick="deleteFacility('${escapeHtml(facility.id)}')">Delete</button>
        </div>
      </td>
    </tr>
  `).join('');
}

function addFacility() {
  if (!canManageFacilities()) {
    alert('Facility management changes are disabled in Settings.');
    return;
  }

  const name = prompt('Facility name:');
  if (!name) return;
  const location = prompt('Location:', 'Campus') || 'Campus';
  const availability = prompt('Availability:', 'Available') || 'Available';
  const facilities = getFacilities();
  facilities.push({
    id: `FAC-${Date.now().toString().slice(-3)}`,
    name: name.trim(),
    location: location.trim(),
    availability: availability.trim(),
  });
  saveFacilities(facilities);
  addNotification(`New facility added: ${name.trim()}`, 'facilityChange');
  renderFacilityManagement();
}

function editFacility(id) {
  if (!canManageFacilities()) {
    alert('Facility management changes are disabled in Settings.');
    return;
  }

  const facilities = getFacilities();
  const facility = facilities.find((item) => item.id === id);
  if (!facility) return;

  const name = prompt('Facility name:', facility.name);
  if (name === null) return;
  const location = prompt('Location:', facility.location);
  if (location === null) return;
  const availability = prompt('Availability:', facility.availability);
  if (availability === null) return;

  facility.name = name.trim() || facility.name;
  facility.location = location.trim() || facility.location;
  facility.availability = availability.trim() || facility.availability;
  saveFacilities(facilities);
  addNotification(`Facility updated: ${facility.name}`, 'facilityChange');
  renderFacilityManagement();
}

function deleteFacility(id) {
  if (!canManageFacilities()) {
    alert('Facility deletion is disabled in Settings.');
    return;
  }

  const facilities = getFacilities();
  const facility = facilities.find((item) => item.id === id);
  if (!facility) return;
  if (!confirm(`Delete facility ${facility.name}?`)) return;

  saveFacilities(facilities.filter((item) => item.id !== id));
  addNotification(`Facility removed: ${facility.name}`, 'facilityChange');
  renderFacilityManagement();
}

function renderAdminRequestsTable() {
  const tbody = document.getElementById('adminRequestsBody');
  if (!tbody) return;

  const requests = getRequests();
  if (!requests.length) {
    tbody.innerHTML = `
      <tr class="empty-state">
        <td colspan="5" style="text-align: center; padding: 32px;">No student requests yet.</td>
      </tr>
    `;
    return;
  }

  tbody.innerHTML = requests.map((request) => {
    const status = request.status || 'Pending';
    const date = request.reportedAt ? new Date(request.reportedAt).toLocaleDateString() : 'Not set';
    const decisionButtons = status === 'Pending'
      ? `<button class="approve" type="button" onclick="approveRequest(${request.id})">Approve</button><button class="reject" type="button" onclick="rejectRequest(${request.id})">Reject</button>`
      : '';

    return `
      <tr>
        <td>${escapeHtml(String(request.id))}</td>
        <td>${escapeHtml(request.location || request.title || 'Facility request')}</td>
        <td>${escapeHtml(date)}</td>
        <td><span class="status-badge ${getStatusBadgeClass(status)}">${escapeHtml(status)}</span></td>
        <td>
          <div class="action-buttons">
            ${decisionButtons}
            <button class="view" type="button" onclick="viewRequest(${request.id})">View</button>
            <button class="edit" type="button" onclick="addRequestRemark(${request.id})">Remark</button>
          </div>
        </td>
      </tr>
    `;
  }).join('');
}

function renderAdminNotifications() {
  const feed = document.getElementById('adminNotificationsFeed');
  if (!feed) return;

  const notifications = getNotifications();
  if (!notifications.length) {
    feed.innerHTML = '<div class="activity-empty"><i class="fas fa-clock"></i><p>No notifications yet.</p></div>';
    return;
  }

  feed.innerHTML = notifications.map((note) => `
    <div class="activity-item">
      <div class="activity-item-header">
        <span class="activity-icon update"><i class="fas fa-bell"></i></span>
        <div class="activity-content">
          <h4>System notification</h4>
          <p>${escapeHtml(note.message)}</p>
        </div>
      </div>
      <div class="activity-timestamp">${note.createdAt ? new Date(note.createdAt).toLocaleString() : 'Recently'}</div>
    </div>
  `).join('');
}

function renderAdminSettings() {
  const settings = getAdminSettings();
  const fields = [
    'adminDisplayName',
    'adminEmail',
    'sessionTimeout',
    'defaultStatus',
    'defaultPriority',
    'autoArchiveDays',
    'notificationDigest',
    'reportRange',
    'reportFormat',
    'dashboardDensity',
    'accentColor',
  ];
  const checks = [
    'requireStrongPassword',
    'allowStudentCancel',
    'permApproveRequests',
    'permManageFacilities',
    'permManageUsers',
    'permExportReports',
    'notifyNewRequest',
    'notifyDueSoon',
    'notifyFacilityChange',
    'includeCharts',
    'includeActivityLog',
    'showActivityPanel',
    'showMetricCards',
  ];

  fields.forEach((id) => {
    const input = document.getElementById(id);
    if (input && settings[id] !== undefined) input.value = settings[id];
  });

  checks.forEach((id) => {
    const input = document.getElementById(id);
    if (input) input.checked = Boolean(settings[id]);
  });
}

function saveAdminSettingsFromForm() {
  const current = getAdminSettings();
  const fieldIds = [
    'adminDisplayName',
    'adminEmail',
    'sessionTimeout',
    'defaultStatus',
    'defaultPriority',
    'autoArchiveDays',
    'notificationDigest',
    'reportRange',
    'reportFormat',
    'dashboardDensity',
    'accentColor',
  ];
  const checkboxIds = [
    'requireStrongPassword',
    'allowStudentCancel',
    'permApproveRequests',
    'permManageFacilities',
    'permManageUsers',
    'permExportReports',
    'notifyNewRequest',
    'notifyDueSoon',
    'notifyFacilityChange',
    'includeCharts',
    'includeActivityLog',
    'showActivityPanel',
    'showMetricCards',
  ];
  const next = { ...current };

  fieldIds.forEach((id) => {
    const input = document.getElementById(id);
    if (input) next[id] = input.value;
  });

  checkboxIds.forEach((id) => {
    const input = document.getElementById(id);
    if (input) next[id] = input.checked;
  });

  saveAdminSettings(next);
  const profile = getProfile();
  if (next.adminDisplayName) {
    localStorage.setItem(STORAGE_KEYS.profile, JSON.stringify({ ...profile, adminName: next.adminDisplayName }));
  }
  addNotification('Admin settings updated.');
  alert('Settings saved.');
  initializeAdminShell('settings');
  initializeSettingsTabs();
  renderAdminSettings();
}

function initializeSettingsTabs() {
  const tabs = document.querySelectorAll('[data-settings-tab]');
  const panels = document.querySelectorAll('[data-settings-panel]');
  if (!tabs.length || !panels.length) return;

  tabs.forEach((tab) => {
    tab.addEventListener('click', () => {
      const key = tab.getAttribute('data-settings-tab');
      tabs.forEach((item) => item.classList.toggle('active', item === tab));
      panels.forEach((panel) => {
        panel.classList.toggle('active', panel.getAttribute('data-settings-panel') === key);
      });
    });
  });
}

function login() {
  const user = document.getElementById('username').value.trim();
  const pass = document.getElementById('password').value;
  const normalizedUser = user.toLowerCase();

  if ((normalizedUser === 'student' || normalizedUser === 'admin') && pass === '1234') {
    const role = normalizedUser === 'admin' ? 'ADMIN' : 'STUDENT';
    localStorage.setItem(STORAGE_KEYS.auth, 'true');
    localStorage.setItem(STORAGE_KEYS.role, role);
    localStorage.setItem('username', role === 'ADMIN' ? 'ADMIN' : 'student');
    window.location = getDashboardPathForRole(role);
    return;
  }

  alert('Invalid username or password.');
}

function logout() {
  localStorage.removeItem(STORAGE_KEYS.auth);
  localStorage.removeItem(STORAGE_KEYS.role);
  localStorage.removeItem('username');
  window.location = `${getRootPrefix()}login.html`;
}

function getSidebarOpen() {
  return localStorage.getItem('sidebarOpen') === 'true';
}

function setSidebarOpen(open) {
  localStorage.setItem('sidebarOpen', open ? 'true' : 'false');
}

function ensureSidebarBackdrop() {
  let backdrop = document.getElementById('sidebarBackdrop');
  if (!backdrop) {
    backdrop = document.createElement('div');
    backdrop.id = 'sidebarBackdrop';
    backdrop.className = 'sidebar-backdrop';
    backdrop.addEventListener('click', () => toggleSidebar());
    document.body.appendChild(backdrop);
  }
  return backdrop;
}

function setSidebarBackdropVisible(visible) {
  const isMobile = window.matchMedia('(max-width: 900px)').matches;
  const backdrop = ensureSidebarBackdrop();
  backdrop.classList.toggle('visible', isMobile && visible);
}

function applySidebarState() {
  const sidebar = document.getElementById('sidebar');
  if (!sidebar) return;

  const open = getSidebarOpen();
  sidebar.classList.toggle('open', open);
  setSidebarBackdropVisible(open);
}

function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  if (!sidebar) return;

  const isOpen = sidebar.classList.contains('open');
  setSidebarOpen(!isOpen);
  applySidebarState();
}

function setActiveNav() {
  const links = document.querySelectorAll('.sidebar .nav a');
  const current = window.location.pathname.split('/').pop();
  links.forEach((link) => {
    const href = link.getAttribute('href');
    if (href === current) {
      link.classList.add('active');
    } else {
      link.classList.remove('active');
    }
  });
}

function saveProfile(showAlert = true) {
  const name = document.getElementById('name').value.trim();
  const studentId = document.getElementById('studentId').value.trim();
  const course = document.getElementById('course').value.trim();
  const year = document.getElementById('year').value.trim();
  const email = document.getElementById('email').value.trim();
  const phone = document.getElementById('phone').value.trim();
  const photo = document.getElementById('profilePhoto')?.getAttribute('data-src') || getDefaultProfilePhoto();

  const profile = { name, studentId, course, year, email, phone, photo };
  localStorage.setItem(STORAGE_KEYS.profile, JSON.stringify(profile));

  if (showAlert) {
    alert('Profile saved.');
  }

  updateUserGreeting();
  updateStudentCard();
}

function populateProfile() {
  const profile = getProfile();
  document.getElementById('name').value = profile.name || '';
  document.getElementById('studentId').value = profile.studentId || '';
  document.getElementById('course').value = profile.course || '';
  document.getElementById('year').value = profile.year || '';
  document.getElementById('email').value = profile.email || '';
  document.getElementById('phone').value = profile.phone || '';

  const preview = document.getElementById('profilePhoto');
  if (preview) {
    const src = profile.photo || getDefaultProfilePhoto();
    preview.src = src;
    preview.setAttribute('data-src', src);
  }
}

function setupProfileAutoSave() {
  const fields = ['name', 'studentId', 'course', 'year', 'email', 'phone'];
  const debouncedSave = debounce(() => saveProfile(false), 800);

  fields.forEach((fieldId) => {
    const input = document.getElementById(fieldId);
    if (!input) return;

    input.addEventListener('input', () => {
      updateStudentCard();
      updateUserGreeting();
      updateSidebarProfile();
      debouncedSave();
    });
  });
}

function handleProfilePhotoUpload(event) {
  const file = event.target.files && event.target.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = () => {
    const imageData = reader.result;
    const preview = document.getElementById('profilePhoto');
    if (preview) {
      preview.src = imageData;
      preview.setAttribute('data-src', imageData);
    }
  };
  reader.readAsDataURL(file);
}

function updateUserGreeting() {
  const profile = getProfile();
  const name = profile.name || localStorage.getItem('username') || 'Student';
  const el = document.getElementById('userGreeting');

  if (el) {
    el.textContent = '';
  }
}

function updateTopbarTitle() {
  const title = document.querySelector('.page-header h1')?.textContent || '';
  const el = document.getElementById('topbarTitle');
  if (el) el.textContent = title;
}

function updateSidebarProfile() {
  const profile = getProfile();
  const role = getUserRole();
  const name = profile.name || (role === 'ADMIN' ? 'ADMIN' : 'Student');
  const meta = role === 'ADMIN' ? 'ADMIN' : [profile.course, profile.year].filter(Boolean).join(' • ') || '—';
  const photo = profile.photo || getDefaultProfilePhoto();

  const elName = document.getElementById('sidebarProfileName');
  const elMeta = document.getElementById('sidebarProfileMeta');
  const elPhoto = document.getElementById('sidebarProfilePhoto');

  if (elName) elName.textContent = name;
  if (elMeta) elMeta.textContent = meta;
  if (elPhoto) {
    elPhoto.src = photo;
    elPhoto.setAttribute('data-src', photo);
  }
}

function updateStudentCard() {
  const profile = getProfile();
  const role = getUserRole();
  const name = role === 'ADMIN' ? 'ADMIN' : profile.name || '—';
  const studentId = role === 'ADMIN' ? '—' : profile.studentId || '—';
  const course = role === 'ADMIN' ? '—' : profile.course || '—';
  const year = role === 'ADMIN' ? '—' : profile.year || '—';
  const email = role === 'ADMIN' ? '—' : profile.email || '—';
  const phone = role === 'ADMIN' ? '—' : profile.phone || '—';
  const photo = profile.photo || getDefaultProfilePhoto();

  const elName = document.getElementById('cardName');
  const elId = document.getElementById('cardId');
  const elCourse = document.getElementById('cardCourse');
  const elYear = document.getElementById('cardYear');
  const elEmail = document.getElementById('cardEmail');
  const elPhone = document.getElementById('cardPhone');
  const elPhoto = document.getElementById('profilePhoto');

  if (elName) elName.textContent = name;
  if (elId) elId.textContent = studentId;
  if (elCourse) elCourse.textContent = course;
  if (elYear) elYear.textContent = year;
  if (elEmail) elEmail.textContent = email;
  if (elPhone) elPhone.textContent = phone;
  if (elPhoto) {
    elPhoto.src = photo;
    elPhoto.setAttribute('data-src', photo);
  }

  updateSidebarProfile();
}

function renderStudentViewer() {
  const profile = getProfile();
  const values = {
    studentProfileName: profile.name || '—',
    studentProfileId: profile.studentId || '—',
    studentProfileCourse: profile.course || '—',
    studentProfileYear: profile.year || '—',
    studentProfileEmail: profile.email || '—',
    studentProfilePhone: profile.phone || '—',
  };

  Object.entries(values).forEach(([id, value]) => {
    const el = document.getElementById(id);
    if (el) el.textContent = value;
  });
}

function updateRequestHistory(request, action, note) {
  request.history = request.history || [];
  request.history.push({
    status: request.status,
    action,
    note,
    timestamp: Date.now(),
  });
}

function approveRequest(id) {
  if (!canApproveRequests()) {
    alert('Approving requests is disabled in Settings.');
    return;
  }

  const requests = getRequests();
  const request = requests.find((item) => item.id === id);
  if (!request) return;

  request.status = 'Approved';
  updateRequestHistory(request, 'Approved', 'Request approved by admin.');
  saveRequests(requests);
  addNotification(`Request "${request.title}" approved.`);
  renderRequests();
  renderAdminRequestsTable();
  renderAdminNotifications();
}

function rejectRequest(id) {
  if (!canApproveRequests()) {
    alert('Rejecting requests is disabled in Settings.');
    return;
  }

  const requests = getRequests();
  const request = requests.find((item) => item.id === id);
  if (!request) return;

  request.status = 'Rejected';
  updateRequestHistory(request, 'Rejected', 'Request rejected by admin.');
  saveRequests(requests);
  addNotification(`Request "${request.title}" rejected.`);
  renderRequests();
  renderAdminRequestsTable();
  renderAdminNotifications();
}

function addRequestRemark(id) {
  const requests = getRequests();
  const request = requests.find((item) => item.id === id);
  if (!request) return;

  const remark = prompt('Enter remark for this request:', request.remark || '');
  if (remark === null) return;

  request.remark = remark.trim();
  updateRequestHistory(request, 'Remark added', request.remark || 'Remark cleared.');
  saveRequests(requests);
  addNotification(`Remark updated for request "${request.title}".`);
  renderRequests();
  renderAdminRequestsTable();
}

function initDashboard() {
  requireAuth();
  applySidebarState();
  setActiveNav();
  updateUserGreeting();
  updateTopbarTitle();
  updateStudentCard();
  updateNotificationBadge();
  checkDueDates();

  // Initialize page based on role and current page
  initializePage();

  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      if (isAdminPage()) {
        renderAdminCharts();
      } else {
        renderCharts();
      }
    }, 120);
  });

  setInterval(checkDueDates, 60_000);
}

function initProfile() {
  requireAuth();
  applySidebarState();
  setActiveNav();
  updateUserGreeting();
  updateTopbarTitle();
  populateProfile();
  updateStudentCard();
  setupProfileAutoSave();
  updateNotificationBadge();
}

function initRequests() {
  requireAuth();
  applyAdminSettings();

  if (getUserRole() === 'ADMIN') {
    window.location = 'requests.html';
    return;
  }

  applySidebarState();
  setActiveNav();
  updateUserGreeting();
  updateTopbarTitle();
  updateStudentCard();
  renderStudentViewer();
  updateNotificationBadge();

  const today = new Date().toISOString().slice(0, 10);
  const requestDate = document.getElementById('requestDate');
  if (requestDate && !requestDate.value) {
    requestDate.value = today;
  }

  const settings = getAdminSettings();
  const prioritySelect = document.getElementById('requestPriority');
  if (prioritySelect && settings.defaultPriority) {
    prioritySelect.value = settings.defaultPriority;
  }

  renderRequests();
  checkDueDates();
  setInterval(checkDueDates, 60_000);
}

function initHelp() {
  requireAuth();
  applySidebarState();
  setActiveNav();
  updateUserGreeting();
  updateTopbarTitle();
  updateStudentCard();
  renderFaqs();
  updateNotificationBadge();

  // Show admin FAQ controls if admin
  const adminControls = document.getElementById('adminFaqControls');
  if (adminControls && getUserRole() === 'ADMIN') {
    adminControls.style.display = 'block';
  }
}

function initFaqAccordion() {
  const items = document.querySelectorAll('.faq-item');
  items.forEach((item) => {
    const question = item.querySelector('.faq-question');
    const answer = item.querySelector('.faq-answer');
    if (!question || !answer) return;

    answer.style.maxHeight = '0';
    answer.style.overflow = 'hidden';
    answer.style.transition = 'max-height 0.25s ease';

    question.setAttribute('aria-expanded', 'false');
    question.addEventListener('click', () => {
      const expanded = question.getAttribute('aria-expanded') === 'true';
      question.setAttribute('aria-expanded', expanded ? 'false' : 'true');
      item.classList.toggle('open', !expanded);
      if (!expanded) {
        answer.style.maxHeight = `${answer.scrollHeight}px`;
      } else {
        answer.style.maxHeight = '0';
      }
    });
  });
}

function initNotifications() {
  requireAuth();
  applySidebarState();
  setActiveNav();
  updateUserGreeting();
  updateTopbarTitle();
  updateStudentCard();
  renderNotifications();
  updateNotificationBadge();
}

function getRequests() {
  const raw = localStorage.getItem(STORAGE_KEYS.requests);
  try {
    return raw ? JSON.parse(raw) : [];
  } catch {
    return [];
  }
}

function saveRequests(requests) {
  localStorage.setItem(STORAGE_KEYS.requests, JSON.stringify(requests));
}

function getNotifications() {
  const raw = localStorage.getItem(STORAGE_KEYS.notifications);
  try {
    return raw ? JSON.parse(raw) : [];
  } catch {
    return [];
  }
}

function getAdminUsers() {
  const raw = localStorage.getItem(STORAGE_KEYS.adminUsers);
  try {
    return raw ? JSON.parse(raw) : [
      { id: 'USR-001', name: 'Juan Miguel Reyes', role: 'Administrator', status: 'Active' },
      { id: 'USR-104', name: 'Maria Santos', role: 'Staff', status: 'Active' },
      { id: 'USR-217', name: 'Carlo Medina', role: 'Student', status: 'Pending' },
    ];
  } catch {
    return [];
  }
}

function saveAdminUsers(users) {
  localStorage.setItem(STORAGE_KEYS.adminUsers, JSON.stringify(users));
}

function getFacilities() {
  const raw = localStorage.getItem(STORAGE_KEYS.facilities);
  try {
    return raw ? JSON.parse(raw) : [
      { id: 'FAC-010', name: 'Computer Lab A', location: 'Building B, Room 201', availability: 'Available' },
      { id: 'FAC-018', name: 'Main Auditorium', location: 'Admin Building', availability: 'In Use' },
      { id: 'FAC-024', name: 'Library Conference Room', location: 'Library, 2nd Floor', availability: 'Unavailable' },
    ];
  } catch {
    return [];
  }
}

function saveFacilities(facilities) {
  localStorage.setItem(STORAGE_KEYS.facilities, JSON.stringify(facilities));
}

function getAdminSettings() {
  const raw = localStorage.getItem(STORAGE_KEYS.adminSettings);
  try {
    return raw ? JSON.parse(raw) : {
      adminDisplayName: 'Juan Miguel Reyes',
      defaultStatus: 'Pending',
      defaultPriority: 'Medium',
      adminEmail: 'admin@snsu.edu.ph',
      sessionTimeout: '30',
      requireStrongPassword: true,
      autoArchiveDays: '30',
      allowStudentCancel: true,
      permApproveRequests: true,
      permManageFacilities: true,
      permManageUsers: true,
      permExportReports: false,
      notifyNewRequest: true,
      notifyDueSoon: true,
      notifyFacilityChange: true,
      notificationDigest: 'Instant',
      reportRange: 'Last 30 days',
      reportFormat: 'PDF',
      includeCharts: true,
      includeActivityLog: true,
      dashboardDensity: 'Comfortable',
      accentColor: '#3498DB',
      showActivityPanel: true,
      showMetricCards: true,
    };
  } catch {
    return {};
  }
}

function saveAdminSettings(settings) {
  localStorage.setItem(STORAGE_KEYS.adminSettings, JSON.stringify(settings));
}

function saveNotifications(items) {
  localStorage.setItem(STORAGE_KEYS.notifications, JSON.stringify(items));
}

function addNotification(message, type = 'general') {
  const settings = getAdminSettings();
  if (type === 'newRequest' && settings.notifyNewRequest === false) return;
  if (type === 'dueSoon' && settings.notifyDueSoon === false) return;
  if (type === 'facilityChange' && settings.notifyFacilityChange === false) return;

  const notifications = getNotifications();
  notifications.unshift({
    id: Date.now(),
    message,
    createdAt: Date.now(),
  });
  saveNotifications(notifications);
  updateNotificationBadge();
}

function updateNotificationBadge() {
  const badge = document.getElementById('notifBadge');
  if (!badge) return;
  const count = getNotifications().length;
  badge.textContent = count ? String(count) : '';
  badge.style.display = count ? 'inline-flex' : 'none';
}

function renderNotifications() {
  const notifications = getNotifications();
  const list = document.getElementById('notificationList');
  if (!list) return;

  list.innerHTML = '';

  if (notifications.length === 0) {
    list.innerHTML = '<li class="notification-empty">No notifications yet.</li>';
    return;
  }

  notifications.forEach((note) => {
    const li = document.createElement('li');
    li.className = 'notification-row';

    const time = new Date(note.createdAt).toLocaleString();
    li.innerHTML = `
      <div class="notification-body">
        <p>${escapeHtml(note.message)}</p>
        <span class="notification-time">${time}</span>
      </div>
      <button class="btn secondary" onclick="dismissNotification(${note.id})">Dismiss</button>
    `;

    list.appendChild(li);
  });
}

function dismissNotification(id) {
  const remaining = getNotifications().filter((item) => item.id !== id);
  saveNotifications(remaining);
  renderNotifications();
  updateNotificationBadge();
}

function clearNotifications() {
  saveNotifications([]);
  renderNotifications();
  updateNotificationBadge();
}

function updateDashboardStats() {
  const requests = getRequests();
  const total = requests.length;
  const pending = requests.filter((r) => r.status === 'Pending').length;
  const inProgress = requests.filter((r) => r.status === 'In Progress').length;
  const completed = requests.filter((r) => r.status === 'Approved').length;
  const cancelled = requests.filter((r) => r.status === 'Rejected').length;

  // Update summary cards
  const totalEl = document.getElementById('totalRequests');
  const pendingEl = document.getElementById('pendingRequests');
  const inprogressEl = document.getElementById('inprogressRequests');
  const completedEl = document.getElementById('completedRequests');
  const cancelledEl = document.getElementById('cancelledRequests');

  if (totalEl) totalEl.textContent = String(total);
  if (pendingEl) pendingEl.textContent = String(pending);
  if (inprogressEl) inprogressEl.textContent = String(inProgress);
  if (completedEl) completedEl.textContent = String(completed);
  if (cancelledEl) cancelledEl.textContent = String(cancelled);

  // Keep charts in sync with current stats
  renderCharts();
  renderRecentRequests();
  renderActivityFeed();
}

function updateDashboardUserInfo() {
  const profile = getProfile();
  const role = getUserRole();
  const name = profile.name || (role === 'ADMIN' ? 'ADMIN' : 'Student');
  const studentId = profile.studentId || '—';

  const nameEl = document.getElementById('userName');
  const idEl = document.getElementById('userId');

  if (nameEl) nameEl.textContent = name;
  if (idEl) idEl.textContent = `ID: ${studentId}`;
}

function renderCharts() {
  const statusCanvas = document.getElementById('statusChart');
  const activityCanvas = document.getElementById('activityChart');
  if (!statusCanvas && !activityCanvas) return;

  const requests = getRequests();

  if (statusCanvas) {
    renderStatusChartJs(statusCanvas, requests);
  }

  if (activityCanvas) {
    renderActivityChartJs(activityCanvas, requests);
  }

  renderAnalyticsMetrics(requests);
}

function renderStatusChart(canvas, requests) {
  const counts = {
    Pending: 0,
    Approved: 0,
    Rejected: 0,
    'In Progress': 0,
    Completed: 0,
    Cancelled: 0,
  };

  requests.forEach((r) => {
    if (counts[r.status] !== undefined) {
      counts[r.status] += 1;
    }
  });

  const labels = ['Pending', 'Approved', 'Rejected', 'In Progress', 'Completed', 'Cancelled'];
  const values = labels.map((label) => counts[label] || 0);
  const colors = [
    'rgba(59, 130, 246, 0.9)',
    'rgba(16, 185, 129, 0.9)',
    'rgba(239, 68, 68, 0.9)',
    'rgba(234, 179, 8, 0.9)',
    'rgba(34, 197, 94, 0.9)',
    'rgba(107, 114, 128, 0.9)',
  ];

  drawBarChart(canvas, labels, values, colors);
}

function renderTrendChart(canvas, requests) {
  const today = new Date();
  const days = Array.from({ length: 7 }).map((_, idx) => {
    const date = new Date(today);
    date.setDate(today.getDate() - (6 - idx));
    return date;
  });

  const values = days.map((day) => {
    const dayStart = new Date(day);
    dayStart.setHours(0, 0, 0, 0);
    const dayEnd = new Date(dayStart);
    dayEnd.setDate(dayEnd.getDate() + 1);

    return requests.filter((r) => {
      if (!r.reportedAt) return false;
      const time = new Date(r.reportedAt).getTime();
      return time >= dayStart.getTime() && time < dayEnd.getTime();
    }).length;
  });

  const labels = days.map((d) => formatDateLabel(d));
  drawLineChart(canvas, labels, values);
}

// Chart.js implementations for enhanced analytics
function renderActivityChartJs(canvas, requests) {
  if (window.activityChartInstance) {
    window.activityChartInstance.destroy();
  }

  const today = new Date();
  const days = Array.from({ length: 7 }).map((_, idx) => {
    const date = new Date(today);
    date.setDate(today.getDate() - (6 - idx));
    return date;
  });

  const values = days.map((day) => {
    const dayStart = new Date(day);
    dayStart.setHours(0, 0, 0, 0);
    const dayEnd = new Date(dayStart);
    dayEnd.setDate(dayEnd.getDate() + 1);

    return requests.filter((r) => {
      if (!r.reportedAt) return false;
      const time = new Date(r.reportedAt).getTime();
      return time >= dayStart.getTime() && time < dayEnd.getTime();
    }).length;
  });

  const labels = days.map((d) => formatDateLabel(d));

  const ctx = canvas.getContext('2d');
  window.activityChartInstance = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: 'Requests Created',
        data: values,
        borderColor: 'rgba(59, 130, 246, 1)',
        backgroundColor: 'rgba(59, 130, 246, 0.1)',
        borderWidth: 2.5,
        fill: true,
        tension: 0.4,
        pointBackgroundColor: 'rgba(59, 130, 246, 1)',
        pointBorderColor: '#fff',
        pointBorderWidth: 2,
        pointRadius: 5,
        pointHoverRadius: 7,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          labels: {
            font: { size: 12 },
            color: 'rgba(100, 116, 139, 0.9)',
            usePointStyle: true,
            padding: 20,
          }
        },
        tooltip: {
          backgroundColor: 'rgba(15, 23, 42, 0.9)',
          padding: 12,
          borderRadius: 8,
          titleFont: { size: 13, weight: 'bold' },
          bodyFont: { size: 12 },
          callbacks: {
            afterLabel: () => 'requests',
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1,
            color: 'rgba(100, 116, 139, 0.7)',
            font: { size: 12 }
          },
          grid: {
            color: 'rgba(148, 163, 184, 0.1)',
            drawBorder: false,
          }
        },
        x: {
          ticks: {
            color: 'rgba(100, 116, 139, 0.7)',
            font: { size: 12 }
          },
          grid: {
            display: false,
            drawBorder: false,
          }
        }
      }
    }
  });
}

function renderStatusChartJs(canvas, requests) {
  if (window.statusChartInstance) {
    window.statusChartInstance.destroy();
  }

  const counts = {
    Pending: 0,
    'In Progress': 0,
    Approved: 0,
    Completed: 0,
    Rejected: 0,
    Cancelled: 0,
  };

  requests.forEach((r) => {
    const status = r.status || 'Pending';
    if (counts[status] !== undefined) {
      counts[status] += 1;
    }
  });

  const labels = ['Pending', 'In Progress', 'Approved', 'Completed', 'Rejected', 'Cancelled'];
  const data = labels.map((label) => counts[label] || 0);
  const colors = [
    'rgba(245, 158, 11, 0.9)',
    'rgba(59, 130, 246, 0.9)',
    'rgba(34, 197, 94, 0.9)',
    'rgba(16, 185, 129, 0.9)',
    'rgba(239, 68, 68, 0.9)',
    'rgba(107, 114, 128, 0.9)',
  ];

  const ctx = canvas.getContext('2d');
  window.statusChartInstance = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Number of Requests',
        data: data,
        backgroundColor: colors,
        borderRadius: 8,
        borderSkipped: false,
      }]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        },
        tooltip: {
          backgroundColor: 'rgba(15, 23, 42, 0.9)',
          padding: 12,
          borderRadius: 8,
          titleFont: { size: 13, weight: 'bold' },
          bodyFont: { size: 12 },
          callbacks: {
            afterLabel: () => 'requests',
          }
        }
      },
      scales: {
        x: {
          beginAtZero: true,
          ticks: {
            stepSize: 1,
            color: 'rgba(100, 116, 139, 0.7)',
            font: { size: 12 }
          },
          grid: {
            color: 'rgba(148, 163, 184, 0.1)',
            drawBorder: false,
          }
        },
        y: {
          ticks: {
            color: 'rgba(100, 116, 139, 0.7)',
            font: { size: 12 }
          },
          grid: {
            display: false,
            drawBorder: false,
          }
        }
      }
    }
  });
}

function renderAnalyticsMetrics(requests) {
  // Calculate average resolution time
  const completedRequests = requests.filter((r) => r.status === 'Approved' || r.status === 'Completed');
  let avgResolutionTime = '—';
  if (completedRequests.length > 0) {
    const totalTime = completedRequests.reduce((sum, r) => {
      const created = new Date(r.reportedAt || Date.now());
      const completed = new Date(r.completedAt || Date.now());
      return sum + (completed - created);
    }, 0);
    const avgMs = totalTime / completedRequests.length;
    const avgDays = Math.round(avgMs / (1000 * 60 * 60 * 24) * 10) / 10;
    avgResolutionTime = `${avgDays} day${avgDays !== 1 ? 's' : ''}`;
  }

  // Calculate success rate
  const successCount = completedRequests.length;
  const successRate = requests.length > 0 ? Math.round((successCount / requests.length) * 100) : 0;

  // Calculate this week's requests
  const today = new Date();
  const weekStart = new Date(today);
  weekStart.setDate(today.getDate() - today.getDay());
  weekStart.setHours(0, 0, 0, 0);

  const weeklyRequests = requests.filter((r) => {
    if (!r.reportedAt) return false;
    const reqDate = new Date(r.reportedAt);
    return reqDate >= weekStart;
  }).length;

  // Calculate average requests per day
  const avgRequestsPerDay = requests.length > 0 ? Math.round((requests.length / 7) * 10) / 10 : 0;

  // Update the DOM
  const avgResolutionEl = document.getElementById('avgResolutionTime');
  const successRateEl = document.getElementById('successRate');
  const weeklyRequestsEl = document.getElementById('weeklyRequests');
  const avgRequestsPerDayEl = document.getElementById('avgRequestsPerDay');

  if (avgResolutionEl) avgResolutionEl.textContent = avgResolutionTime;
  if (successRateEl) successRateEl.textContent = `${successRate}%`;
  if (weeklyRequestsEl) weeklyRequestsEl.textContent = String(weeklyRequests);
  if (avgRequestsPerDayEl) avgRequestsPerDayEl.textContent = String(avgRequestsPerDay);
}

function renderRecentRequests() {
  const requests = getRequests();
  const tbody = document.getElementById('recentRequestsBody');
  if (!tbody) return;

  tbody.innerHTML = '';

  if (!requests.length) {
    tbody.innerHTML = `
      <tr class="empty-state">
        <td colspan="6" style="text-align: center; padding: 32px;">
          <i class="fas fa-inbox" style="font-size: 2.5rem; color: var(--muted); margin-bottom: 12px;"></i>
          <p style="color: var(--muted); margin: 8px 0 0 0;">No requests yet. Create your first maintenance request!</p>
        </td>
      </tr>
    `;
    return;
  }

  const recentRequests = requests.slice(0, 8);

  recentRequests.forEach((request) => {
    const row = document.createElement('tr');
    const reportedDate = request.reportedAt ? new Date(request.reportedAt).toLocaleDateString() : '—';
    const statusClass = `status-${String(request.status || 'Pending').toLowerCase().replace(/\s+/g, '')}`;
    const statusLabel = request.status || 'Pending';

    row.innerHTML = `
      <td>${escapeHtml(String(request.id || '—'))}</td>
      <td>${escapeHtml(request.title || request.location || '—')}</td>
      <td>${escapeHtml(request.location || '—')}</td>
      <td>${escapeHtml(reportedDate)}</td>
      <td><span class="status-badge ${statusClass}">${escapeHtml(statusLabel)}</span></td>
      <td>
        <div class="action-buttons">
          <button type="button" class="view" onclick="viewRequest(${request.id})">View</button>
          <button type="button" class="edit" onclick="editRequest(${request.id})">Edit</button>
        </div>
      </td>
    `;

    tbody.appendChild(row);
  });
}

function renderActivityFeed() {
  const feed = document.getElementById('activityFeed');
  if (!feed) return;

  const requests = getRequests();

  if (!requests.length) {
    feed.innerHTML = `
      <div class="activity-empty">
        <i class="fas fa-clock" style="font-size: 2rem; color: var(--muted); margin-bottom: 12px;"></i>
        <p style="color: var(--muted); margin: 0;">No recent activity</p>
      </div>
    `;
    return;
  }

  // Sort requests by most recent first and take the latest 10
  const recentActivity = requests
    .sort((a, b) => (b.updatedAt || b.reportedAt || 0) - (a.updatedAt || a.reportedAt || 0))
    .slice(0, 10);

  const activityItems = recentActivity.map(request => {
    const timestamp = request.updatedAt || request.reportedAt;
    const timeAgo = timestamp ? getTimeAgo(timestamp) : 'Recently';
    const status = request.status || 'Pending';
    const title = request.title || request.location || 'Maintenance Request';

    let activityType = 'submission';
    let activityText = 'New request submitted';
    let iconClass = 'submission';

    if (status === 'Completed') {
      activityType = 'approval';
      activityText = 'Request completed';
      iconClass = 'approval';
    } else if (status === 'In Progress') {
      activityType = 'update';
      activityText = 'Request status updated';
      iconClass = 'update';
    } else if (status === 'Cancelled') {
      activityType = 'cancellation';
      activityText = 'Request cancelled';
      iconClass = 'cancellation';
    }

    return `
      <div class="activity-item">
        <div class="activity-item-header">
          <div class="activity-icon ${iconClass}">
            <i class="fas fa-${getActivityIcon(iconClass)}"></i>
          </div>
          <div class="activity-content">
            <h4>${escapeHtml(title)}</h4>
            <p>${activityText}</p>
          </div>
        </div>
        <div class="activity-timestamp">${timeAgo}</div>
      </div>
    `;
  }).join('');

  feed.innerHTML = activityItems;
}

function getActivityIcon(type) {
  switch (type) {
    case 'approval': return 'check-circle';
    case 'submission': return 'plus-circle';
    case 'cancellation': return 'times-circle';
    case 'update': return 'sync-alt';
    default: return 'circle';
  }
}

function getTimeAgo(timestamp) {
  const now = Date.now();
  const diff = now - timestamp;
  const minutes = Math.floor(diff / 60000);
  const hours = Math.floor(diff / 3600000);
  const days = Math.floor(diff / 86400000);

  if (minutes < 1) return 'Just now';
  if (minutes < 60) return `${minutes}m ago`;
  if (hours < 24) return `${hours}h ago`;
  if (days < 7) return `${days}d ago`;
  return new Date(timestamp).toLocaleDateString();
}

function viewRequest(id) {
  const requests = getRequests();
  const request = requests.find((item) => item.id === id);
  if (!request) return;
  alert(`View request #${id}: ${request.title || request.location}`);
}

function editRequest(id) {
  const requests = getRequests();
  const request = requests.find((item) => item.id === id);
  if (!request) return;
  alert(`Edit request #${id}: ${request.title || request.location}`);
}

function drawBarChart(canvas, labels, values, colors) {
  const ctx = setCanvasDpi(canvas);
  const rect = canvas.getBoundingClientRect();
  const width = rect.width;
  const height = rect.height;
  const padding = 38;
  const max = Math.max(...values, 1);
  const barWidth = (width - padding * 2) / values.length * 0.6;

  ctx.clearRect(0, 0, width, height);
  ctx.font = '12px ui-sans-serif';
  ctx.textAlign = 'center';
  ctx.textBaseline = 'middle';

  const points = [];

  values.forEach((value, idx) => {
    const xAxis = padding + idx * ((width - padding * 2) / values.length) + (width - padding * 2) / values.length / 2;
    const barHeight = (value / max) * (height - padding * 2);
    const y = height - padding - barHeight;

    ctx.fillStyle = colors[idx];
    ctx.fillRect(xAxis - barWidth / 2, y, barWidth, barHeight);

    ctx.fillStyle = 'rgba(100, 116, 139, 0.9)';
    ctx.fillText(values[idx], xAxis, y - 14);
    ctx.fillText(labels[idx], xAxis, height - padding + 14);

    points.push({ x: xAxis, y, label: labels[idx], value });
  });

  canvas._chartPoints = points;
  ensureChartTooltip(canvas);
}

function drawLineChart(canvas, labels, values) {
  const ctx = setCanvasDpi(canvas);
  const rect = canvas.getBoundingClientRect();
  const width = rect.width;
  const height = rect.height;
  const padding = 38;
  const max = Math.max(...values, 1);

  ctx.clearRect(0, 0, width, height);
  ctx.font = '12px ui-sans-serif';
  ctx.textAlign = 'center';

  // Draw grid lines
  ctx.strokeStyle = 'rgba(148, 163, 184, 0.35)';
  ctx.lineWidth = 1;
  const gridLines = 4;
  for (let i = 0; i <= gridLines; i += 1) {
    const y = padding + ((height - padding * 2) / gridLines) * i;
    ctx.beginPath();
    ctx.moveTo(padding, y);
    ctx.lineTo(width - padding, y);
    ctx.stroke();
  }

  // Draw line
  ctx.beginPath();
  values.forEach((value, idx) => {
    const x = padding + (idx * (width - padding * 2)) / (values.length - 1 || 1);
    const y = height - padding - (value / max) * (height - padding * 2);

    if (idx === 0) ctx.moveTo(x, y);
    else ctx.lineTo(x, y);
  });
  ctx.strokeStyle = 'rgba(59, 130, 246, 0.95)';
  ctx.lineWidth = 2.5;
  ctx.stroke();

  // Points
  const points = [];
  values.forEach((value, idx) => {
    const x = padding + (idx * (width - padding * 2)) / (values.length - 1 || 1);
    const y = height - padding - (value / max) * (height - padding * 2);

    ctx.beginPath();
    ctx.arc(x, y, 5, 0, Math.PI * 2);
    ctx.fillStyle = 'rgba(59, 130, 246, 0.95)';
    ctx.fill();

    points.push({ x, y, label: labels[idx], value });
  });

  canvas._chartPoints = points;
  ensureChartTooltip(canvas);

  // Labels
  labels.forEach((label, idx) => {
    const x = padding + (idx * (width - padding * 2)) / (values.length - 1 || 1);
    ctx.fillStyle = 'rgba(100, 116, 139, 0.85)';
    ctx.fillText(label, x, height - padding + 16);
  });
}

function setCanvasDpi(canvas) {
  const ctx = canvas.getContext('2d');
  const rect = canvas.getBoundingClientRect();
  const scale = window.devicePixelRatio || 1;
  canvas.width = rect.width * scale;
  canvas.height = rect.height * scale;
  ctx.setTransform(scale, 0, 0, scale, 0, 0);
  return ctx;
}

function formatDateLabel(date) {
  return date.toLocaleDateString(undefined, { month: 'short', day: 'numeric' });
}

function ensureChartTooltip(canvas) {
  const tooltip = document.getElementById('chartTooltip');
  if (!tooltip || !canvas) return;

  if (canvas._chartTooltipAttached) return;
  canvas._chartTooltipAttached = true;

  canvas.addEventListener('mousemove', (event) => {
    const rect = canvas.getBoundingClientRect();
    const x = event.clientX - rect.left;
    const y = event.clientY - rect.top;
    const points = canvas._chartPoints || [];

    const hit = points.find((p) => {
      return x >= p.x - 10 && x <= p.x + 10 && y >= p.y - 10 && y <= p.y + 10;
    });

    if (!hit) {
      tooltip.style.opacity = '0';
      return;
    }

    tooltip.textContent = hit.label ? `${hit.label}: ${hit.value}` : String(hit.value);
    tooltip.style.opacity = '1';
    tooltip.style.left = `${event.clientX}px`;
    tooltip.style.top = `${event.clientY - 18}px`;
  });

  canvas.addEventListener('mouseleave', () => {
    const tooltip = document.getElementById('chartTooltip');
    if (tooltip) tooltip.style.opacity = '0';
  });
}

function renderRequests() {
  const requests = getRequests();
  const list = document.getElementById('taskList');
  const count = document.getElementById('taskCount');
  const filterInput = document.getElementById('requestFilter');
  const filter = filterInput?.value.trim().toLowerCase() || '';

  if (!list) return;

  list.innerHTML = '';

  const filteredRequests = filter
    ? requests.filter((request) => {
        const haystack = [
          request.title,
          request.location,
          request.issueType,
          request.description,
          request.status,
          request.remark,
        ]
          .filter(Boolean)
          .join(' ')
          .toLowerCase();
        return haystack.includes(filter);
      })
    : requests;

  const isAdmin = getUserRole() === 'ADMIN';

  if (filteredRequests.length === 0) {
    list.innerHTML = '<li class="request-empty">No requests found. Try adjusting the filter.</li>';
  }

  filteredRequests.forEach((request) => {
    const li = document.createElement('li');
    li.className = 'request-row';

    const dueDateStr = request.dueDate ? new Date(request.dueDate).toLocaleDateString() : 'Not set';
    const reported = request.reportedAt ? new Date(request.reportedAt).toLocaleString() : 'Unknown';
    const priority = String(request.priority || 'Medium');
    const priorityClass = priority.toLowerCase();
    const remark = request.remark ? `<div class="request-status">Remark: ${escapeHtml(request.remark)}</div>` : '';
    const historyRows = (request.history || [])
      .map((entry) => `
        <li>
          <strong>${escapeHtml(entry.action)}</strong>
          <span>(${new Date(entry.timestamp).toLocaleString()})</span>
          <div>${escapeHtml(entry.note || entry.status)}</div>
        </li>
      `)
      .join('');
    const historySection = historyRows
      ? `<details class="request-history"><summary>Request history</summary><ul>${historyRows}</ul></details>`
      : '';

    const adminStatusControls = request.status === 'Pending'
      ? `
          <button class="btn secondary" onclick="approveRequest(${request.id})">Approve</button>
          <button class="btn secondary" onclick="rejectRequest(${request.id})">Reject</button>
        `
      : '';

    const adminRemarkControl = isAdmin
      ? `<button class="btn secondary" onclick="addRequestRemark(${request.id})">${request.remark ? 'Edit remark' : 'Add remark'}</button>`
      : '';

    const studentActions = !isAdmin
      ? `
          <button class="btn secondary" onclick="changeStatus(${request.id})">Progress</button>
          ${getAdminSettings().allowStudentCancel !== false && request.status !== 'Cancelled' && request.status !== 'Completed' ? `<button class="btn secondary" onclick="cancelRequest(${request.id})">Cancel</button>` : ''}
        `
      : '';

    li.innerHTML = `
      <div class="request-label">
        <div class="request-meta">
          <strong>${escapeHtml(request.title)}</strong>
          <span class="badge ${priorityClass}">${priority}</span>
        </div>
        <div class="request-subtitle">
          <span><strong>Facility:</strong> ${escapeHtml(request.location)}</span>
          <span><strong>Type:</strong> ${escapeHtml(request.issueType)}</span>
        </div>
        <div class="request-subtitle">${escapeHtml(request.description)}</div>
        <div class="request-status">Status: <strong>${request.status}</strong></div>
        <div class="request-status">Reported: ${reported}</div>
        <div class="request-status">Due: ${dueDateStr}</div>
        ${remark}
      </div>
      <div class="request-actions">
        ${adminStatusControls}
        ${adminRemarkControl}
        ${studentActions}
        <button class="btn secondary" onclick="removeRequest(${request.id})">Remove</button>
      </div>
      ${historySection}
    `;

    list.appendChild(li);
  });

  if (count) {
    count.textContent = `${filteredRequests.length} request${filteredRequests.length === 1 ? '' : 's'}`;
  }

  updateDashboardUserInfo();
  updateDashboardStats();
  checkDueDates();
}

function addRequest() {
  if (getUserRole() !== 'STUDENT') {
    alert('Only student accounts can create facility requests.');
    return;
  }

  const titleInput = document.getElementById('requestTitle');
  const locationInput = document.getElementById('requestLocation');
  const typeSelect = document.getElementById('requestType');
  const descriptionInput = document.getElementById('requestDescription');
  const dateInput = document.getElementById('requestDate');
  const dueInput = document.getElementById('requestDue');
  const prioritySelect = document.getElementById('requestPriority');

  if (!titleInput || !locationInput || !typeSelect || !descriptionInput || !dateInput || !dueInput || !prioritySelect) {
    alert('Some request fields are missing.');
    return;
  }

  const title = titleInput.value.trim();
  const location = locationInput.value.trim();
  const issueType = typeSelect.value;
  const description = descriptionInput.value.trim();
  const reportedAt = new Date(dateInput.value || new Date().toISOString()).toISOString();
  const dueDate = dueInput.value ? new Date(dueInput.value).toISOString() : '';
  const settings = getAdminSettings();
  const priority = prioritySelect.value || settings.defaultPriority || 'Medium';

  if (!title || !location || !description) {
    alert('Please fill in the request title, location, and description.');
    return;
  }

  const requests = getRequests();
  requests.unshift({
    id: Date.now(),
    title,
    location,
    issueType,
    description,
    priority,
    status: settings.defaultStatus || 'Pending',
    reportedAt,
    dueDate,
    notifiedDue: false,
    remark: '',
    history: [
      {
        status: 'Pending',
        action: 'Created',
        note: 'Request submitted',
        timestamp: Date.now(),
      },
    ],
  });

  saveRequests(requests);
  addNotification(`New request created: ${title} (Status: ${settings.defaultStatus || 'Pending'})`, 'newRequest');
  renderRequests();

  titleInput.value = '';
  locationInput.value = '';
  descriptionInput.value = '';
  dateInput.value = new Date().toISOString().slice(0, 10);
  dueInput.value = '';
  prioritySelect.value = settings.defaultPriority || 'Medium';
}

function getNextStatus(status) {
  switch (status) {
    case 'Pending':
      return 'In Progress';
    case 'In Progress':
      return 'Completed';
    case 'Completed':
      return 'Pending';
    case 'Cancelled':
      return 'Pending';
    default:
      return 'Pending';
  }
}

function changeStatus(id) {
  const requests = getRequests();
  const request = requests.find((item) => item.id === id);
  if (!request) return;

  const nextStatus = getNextStatus(request.status);
  request.status = nextStatus;
  updateRequestHistory(request, 'Status changed', `Moved to ${nextStatus}`);
  saveRequests(requests);
  addNotification(`Request "${request.title}" moved to ${nextStatus}.`);
  renderRequests();
}

function cancelRequest(id) {
  if (getUserRole() === 'STUDENT' && getAdminSettings().allowStudentCancel === false) {
    alert('Student cancellation is disabled by the administrator.');
    return;
  }

  const requests = getRequests();
  const request = requests.find((item) => item.id === id);
  if (!request) return;

  request.status = 'Cancelled';
  updateRequestHistory(request, 'Cancelled', 'Request was cancelled.');
  saveRequests(requests);
  renderRequests();
  addNotification(`Request "${request.title}" was cancelled.`);
}

function removeRequest(id) {
  const requests = getRequests().filter((item) => item.id !== id);
  saveRequests(requests);
  renderRequests();
  addNotification('A request was removed.');
}

function clearResolved() {
  const requests = getRequests().filter((item) => item.status !== 'Completed' && item.status !== 'Cancelled');
  saveRequests(requests);
  renderRequests();
  addNotification('Cleared completed/cancelled requests.');
}

function checkDueDates() {
  const requests = getRequests();
  const now = Date.now();
  const twoDays = 1000 * 60 * 60 * 24 * 2;

  requests.forEach((request) => {
    if (!request.dueDate || request.status === 'Completed' || request.status === 'Cancelled') {
      return;
    }

    const due = new Date(request.dueDate).getTime();
    if (due - now <= twoDays && due - now >= 0 && !request.notifiedDue) {
      addNotification(`Request "${request.title}" is due soon (${new Date(request.dueDate).toLocaleDateString()}).`, 'dueSoon');
      request.notifiedDue = true;
    }
  });

  saveRequests(requests);
}

function sendHelpMessage() {
  const message = document.getElementById('helpMessage');
  if (!message) return;

  const text = message.value.trim();
  if (!text) {
    alert('Please enter a message.');
    return;
  }

  const messages = getHelpMessages();
  messages.unshift({
    id: Date.now(),
    text,
    createdAt: Date.now(),
  });

  localStorage.setItem(STORAGE_KEYS.help, JSON.stringify(messages));

  alert('Your message has been recorded. Thank you!');
  message.value = '';
}

function getFaqs() {
  const raw = localStorage.getItem(STORAGE_KEYS.faqs);
  try {
    return raw ? JSON.parse(raw) : [
      {
        id: 1,
        question: 'How do I submit a maintenance request?',
        answer: 'Go to the "Requests" page, fill out the form, and click "Create request".',
      },
      {
        id: 2,
        question: 'Can I cancel a request?',
        answer: 'Yes — use the "Cancel" button on the request to stop it from moving forward.',
      },
      {
        id: 3,
        question: 'How do I get notifications?',
        answer: 'Notifications appear in the bell icon when a request status changes or a due date is near.',
      },
    ];
  } catch {
    return [];
  }
}

function saveFaqs(faqs) {
  localStorage.setItem(STORAGE_KEYS.faqs, JSON.stringify(faqs));
}

function addFaq(question, answer) {
  const faqs = getFaqs();
  faqs.push({
    id: Date.now(),
    question: question.trim(),
    answer: answer.trim(),
  });
  saveFaqs(faqs);
}

function editFaq(id, question, answer) {
  const faqs = getFaqs();
  const faq = faqs.find(f => f.id === id);
  if (faq) {
    faq.question = question.trim();
    faq.answer = answer.trim();
    saveFaqs(faqs);
  }
}

function deleteFaq(id) {
  const faqs = getFaqs().filter(f => f.id !== id);
  saveFaqs(faqs);
}

function renderFaqs() {
  const faqs = getFaqs();
  const container = document.querySelector('.faq-section');
  if (!container) return;

  const isAdmin = getUserRole() === 'ADMIN';

  container.innerHTML = faqs.map(faq => `
    <div class="faq-item" data-id="${faq.id}">
      <button class="faq-question" type="button" aria-expanded="false">${escapeHtml(faq.question)}</button>
      <div class="faq-answer">${escapeHtml(faq.answer)}</div>
      ${isAdmin ? `
        <div class="faq-admin-controls">
          <button class="btn secondary" onclick="editFaqMode(${faq.id})">Edit</button>
          <button class="btn secondary" onclick="deleteFaq(${faq.id})">Delete</button>
        </div>
      ` : ''}
    </div>
  `).join('');

  initFaqAccordion();
}

function editFaqMode(id) {
  const faqItem = document.querySelector(`.faq-item[data-id="${id}"]`);
  if (!faqItem) return;

  const question = faqItem.querySelector('.faq-question').textContent;
  const answer = faqItem.querySelector('.faq-answer').textContent;

  faqItem.innerHTML = `
    <input type="text" class="faq-edit-question" value="${escapeHtml(question)}" />
    <textarea class="faq-edit-answer">${escapeHtml(answer)}</textarea>
    <div class="faq-admin-controls">
      <button class="btn primary" onclick="saveFaqEdit(${id})">Save</button>
      <button class="btn secondary" onclick="renderFaqs()">Cancel</button>
    </div>
  `;
}

function saveFaqEdit(id) {
  const faqItem = document.querySelector(`.faq-item[data-id="${id}"]`);
  const question = faqItem.querySelector('.faq-edit-question').value;
  const answer = faqItem.querySelector('.faq-edit-answer').value;
  editFaq(id, question, answer);
  renderFaqs();
}

function addNewFaq() {
  const question = document.getElementById('newFaqQuestion').value;
  const answer = document.getElementById('newFaqAnswer').value;
  if (question && answer) {
    addFaq(question, answer);
    document.getElementById('newFaqQuestion').value = '';
    document.getElementById('newFaqAnswer').value = '';
    renderFaqs();
  }
}

function getHelpMessages() {
  const raw = localStorage.getItem(STORAGE_KEYS.help);
  try {
    return raw ? JSON.parse(raw) : [];
  } catch {
    return [];
  }
}

function escapeHtml(str) {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

if (typeof module !== 'undefined' && module.exports) {
  module.exports = {
    STORAGE_KEYS,
    debounce,
    getProfile,
    getDefaultProfilePhoto,
    requireAuth,
    login,
    logout,
    getSidebarOpen,
    setSidebarOpen,
    ensureSidebarBackdrop,
    setSidebarBackdropVisible,
    applySidebarState,
    toggleSidebar,
    setActiveNav,
    saveProfile,
    populateProfile,
    setupProfileAutoSave,
    handleProfilePhotoUpload,
    updateUserGreeting,
    updateTopbarTitle,
    updateSidebarProfile,
    updateStudentCard,
    renderStudentViewer,
    initDashboard,
    initProfile,
    initRequests,
    initHelp,
    initFaqAccordion,
    getFaqs,
    saveFaqs,
    addFaq,
    editFaq,
    deleteFaq,
    renderFaqs,
    editFaqMode,
    saveFaqEdit,
    addNewFaq,
    initNotifications,
    getRequests,
    saveRequests,
    getNotifications,
    saveNotifications,
    addNotification,
    updateNotificationBadge,
    renderNotifications,
    dismissNotification,
    clearNotifications,
    updateDashboardStats,
    renderCharts,
    renderStatusChart,
    renderTrendChart,
    drawBarChart,
    drawLineChart,
    setCanvasDpi,
    formatDateLabel,
    ensureChartTooltip,
    renderRequests,
    addRequest,
    getNextStatus,
    changeStatus,
    cancelRequest,
    approveRequest,
    rejectRequest,
    addRequestRemark,
    removeRequest,
    clearResolved,
    checkDueDates,
    sendHelpMessage,
    getHelpMessages,
    escapeHtml,
  };
}

