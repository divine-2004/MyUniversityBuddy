const STORAGE_KEYS = {
  auth: 'loggedIn',
  profile: 'profileData',
  requests: 'facilityRequests',
  notifications: 'fmrmsNotifications',
  help: 'helpMessages',
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

function login() {
  const user = document.getElementById('username').value.trim();
  const pass = document.getElementById('password').value;

  if (user === 'student' && pass === '1234') {
    localStorage.setItem(STORAGE_KEYS.auth, 'true');
    localStorage.setItem('username', user);
    window.location = 'index.html';
    return;
  }

  alert('Invalid username or password.');
}

function logout() {
  localStorage.removeItem(STORAGE_KEYS.auth);
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
    el.textContent = `Hi, ${name}`;
  }
}

function updateTopbarTitle() {
  const title = document.querySelector('.page-header h1')?.textContent || '';
  const el = document.getElementById('topbarTitle');
  if (el) el.textContent = title;
}

function updateSidebarProfile() {
  const profile = getProfile();
  const name = profile.name || 'Student';
  const meta = [profile.course, profile.year].filter(Boolean).join(' • ') || '—';
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
  const name = profile.name || '—';
  const studentId = profile.studentId || '—';
  const course = profile.course || '—';
  const year = profile.year || '—';
  const email = profile.email || '—';
  const phone = profile.phone || '—';
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
  initFaqAccordion();
  updateNotificationBadge();
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
  const pending = requests.filter((r) => r.status === 'Pending').length;
  const inProgress = requests.filter((r) => r.status === 'In Progress').length;
  const completed = requests.filter((r) => r.status === 'Completed').length;
  const cancelled = requests.filter((r) => r.status === 'Cancelled').length;

  const pendingEl = document.getElementById('openCount');
  const inProgressEl = document.getElementById('inProgressCount');
  const resolvedEl = document.getElementById('resolvedCount');

  if (pendingEl) pendingEl.textContent = String(pending);
  if (inProgressEl) inProgressEl.textContent = String(inProgress);
  if (resolvedEl) resolvedEl.textContent = String(completed);

  if (resolvedEl && cancelled > 0) {
    resolvedEl.textContent += ` (+${cancelled} cancelled)`;
  }

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
    'In Progress': 0,
    Completed: 0,
    Cancelled: 0,
  };

  requests.forEach((r) => {
    if (counts[r.status] !== undefined) {
      counts[r.status] += 1;
    }
  });

  const labels = ['Pending', 'In Progress', 'Completed', 'Cancelled'];
  const values = labels.map((label) => counts[label] || 0);
  const colors = ['rgba(59, 130, 246, 0.9)', 'rgba(234, 179, 8, 0.9)', 'rgba(34, 197, 94, 0.9)', 'rgba(239, 68, 68, 0.9)'];

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
        ]
          .filter(Boolean)
          .join(' ')
          .toLowerCase();
        return haystack.includes(filter);
      })
    : requests;

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
      </div>
      <div class="request-actions">
        <button class="btn secondary" onclick="changeStatus(${request.id})">Progress</button>
        ${request.status !== 'Cancelled' && request.status !== 'Completed' ? `<button class="btn secondary" onclick="cancelRequest(${request.id})">Cancel</button>` : ''}
        <button class="btn secondary" onclick="removeRequest(${request.id})">Remove</button>
      </div>
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
  saveRequests(requests);
  addNotification(`Request "${request.title}" moved to ${nextStatus}.`);
  renderRequests();
}

function cancelRequest(id) {
  const requests = getRequests();
  const request = requests.find((item) => item.id === id);
  if (!request) return;

  request.status = 'Cancelled';
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
    initDashboard,
    initProfile,
    initRequests,
    initHelp,
    initFaqAccordion,
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
    removeRequest,
    clearResolved,
    checkDueDates,
    sendHelpMessage,
    getHelpMessages,
    escapeHtml,
  };
}
