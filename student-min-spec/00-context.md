# Context

## Project
SNSU-FRMS (Surigao del Norte State University - Facility Request and Monitoring System)

## Scope of this mini-spec
This mini-spec covers the core student and admin features for facility request tracking, monitoring, and support.

### Student-facing features
- Student Profile Card on `profile.html`
- FAQ section on `help.html`
- Facility request submission on `notes.html`

### Admin-facing features
- Dashboard reporting on `index.html`
- Request management in the request list
- Notifications/logs on `notifications.html`
- Student profile viewer and FAQ management via shared UI patterns

## Product context
The application is a browser-based university facilities support tool. Students log in locally, manage a profile card, submit facility requests, and read help content. Admins can monitor request status, review request history, and view notifications. The app is implemented with plain HTML, CSS, and JavaScript and relies on `localStorage` for persistence, so the targeted features must continue to work without a network connection.

## Existing implementation anchors
- UI pages: `index.html`, `profile.html`, `notes.html`, `help.html`, `notifications.html`
- Behavior: `js/script.js`
- Styling: `css/style.css`

## Primary users
- Students submitting facility requests and maintaining profile details
- Admins monitoring request status, viewing student data, and managing FAQs
- University support staff reviewing facility request logs and notifications
