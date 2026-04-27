const STORAGE_KEYS = {
  auth: 'loggedIn',
  role: 'userRole',
  profile: 'profileData',
  requests: 'facilityRequests',
  notifications: 'fmrmsNotifications',
  help: 'helpMessages',
  faqs: 'fmrmsFaqs',
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

function requireAuth() {
  if (window.location.pathname.endsWith('login.html')) {
    return;
  }

  if (localStorage.getItem(STORAGE_KEYS.auth) !== 'true') {
    window.location = 'login.html';
  }
}

function getUserRole() {
  const role = localStorage.getItem(STORAGE_KEYS.role);
  return role === 'ADMIN' ? 'ADMIN' : 'STUDENT';
}

function login() {
  const user = document.getElementById('username').value.trim();
  const pass = document.getElementById('password').value;
  const normalizedUser = user.toLowerCase();

  if ((normalizedUser === 'student' || normalizedUser === 'admin') && pass === '1234') {
    localStorage.setItem(STORAGE_KEYS.auth, 'true');
    localStorage.setItem(STORAGE_KEYS.role, normalizedUser === 'admin' ? 'ADMIN' : 'STUDENT');
    localStorage.setItem('username', normalizedUser === 'admin' ? 'ADMIN' : 'student');
    window.location = 'index.html';
    return;
  }

  alert('Invalid username or password.');
}

function logout() {
  localStorage.removeItem(STORAGE_KEYS.auth);
  localStorage.removeItem(STORAGE_KEYS.role);
  localStorage.removeItem('username');
  window.location = 'login.html';
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
  const requests = getRequests();
  const request = requests.find((item) => item.id === id);
  if (!request) return;

  request.status = 'Approved';
  updateRequestHistory(request, 'Approved', 'Request approved by admin.');
  saveRequests(requests);
  addNotification(`Request "${request.title}" approved.`);
  renderRequests();
}

function rejectRequest(id) {
  const requests = getRequests();
  const request = requests.find((item) => item.id === id);
  if (!request) return;

  request.status = 'Rejected';
  updateRequestHistory(request, 'Rejected', 'Request rejected by admin.');
  saveRequests(requests);
  addNotification(`Request "${request.title}" rejected.`);
  renderRequests();
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
}

function initDashboard() {
  requireAuth();
  applySidebarState();
  setActiveNav();
  updateUserGreeting();
  updateTopbarTitle();
  updateStudentCard();
  updateDashboardStats();
  renderCharts();
  updateNotificationBadge();
  checkDueDates();

  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(renderCharts, 120);
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

function saveNotifications(items) {
  localStorage.setItem(STORAGE_KEYS.notifications, JSON.stringify(items));
}

function addNotification(message) {
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
  const approved = requests.filter((r) => r.status === 'Approved').length;
  const rejected = requests.filter((r) => r.status === 'Rejected').length;

  const totalEl = document.getElementById('totalCount');
  const pendingEl = document.getElementById('pendingCount');
  const approvedEl = document.getElementById('approvedCount');
  const rejectedEl = document.getElementById('rejectedCount');

  if (totalEl) totalEl.textContent = String(total);
  if (pendingEl) pendingEl.textContent = String(pending);
  if (approvedEl) approvedEl.textContent = String(approved);
  if (rejectedEl) rejectedEl.textContent = String(rejected);

  // Keep charts in sync with current stats
  renderCharts();
}

function renderCharts() {
  const statusCanvas = document.getElementById('statusChart');
  const trendCanvas = document.getElementById('trendChart');
  if (!statusCanvas && !trendCanvas) return;

  const requests = getRequests();

  if (statusCanvas) {
    renderStatusChart(statusCanvas, requests);
  }

  if (trendCanvas) {
    renderTrendChart(trendCanvas, requests);
  }
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
          ${request.status !== 'Cancelled' && request.status !== 'Completed' ? `<button class="btn secondary" onclick="cancelRequest(${request.id})">Cancel</button>` : ''}
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

  updateDashboardStats();
  checkDueDates();
}

function addRequest() {
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
  const priority = prioritySelect.value;

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
    status: 'Pending',
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
  addNotification(`New request created: ${title} (Status: Pending)`);
  renderRequests();

  titleInput.value = '';
  locationInput.value = '';
  descriptionInput.value = '';
  dateInput.value = new Date().toISOString().slice(0, 10);
  dueInput.value = '';
  prioritySelect.value = 'Medium';
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
      addNotification(`Request "${request.title}" is due soon (${new Date(request.dueDate).toLocaleDateString()}).`);
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
