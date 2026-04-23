# SNSU-FRMS (Surigao del Norte State University - Facility Request and Monitoring System)

## Features Implemented
1. Student Profile Card
2. FAQ Section
3. Facility Request Submission
4. Dashboard request monitoring
5. Request status workflows and notifications

---

## System Overview
SNSU-FRMS is a browser-based facility request and monitoring system for students and admins. The app uses local storage for persistence and supports offline-capable workflows for profile management, FAQs, requests, notifications, and dashboard monitoring.

## Project Structure
- `student-min-spec/`: Detailed system specifications and validation criteria
- `student/`: Student-facing portal documentation and page landing points
- `admin/`: Admin-facing portal documentation and page landing points
- `shared/`: Shared assets and data contract documentation
- `tests/`: Jest and Cypress test suites
- `css/`: Shared styles
- `js/`: Shared application logic

---

## What Was Implemented
- Student profile live preview and persistence with `localStorage`
- FAQ accordion with accessible expand/collapse behavior
- Facility request submission, filtering, and local storage persistence
- Request status workflows with progress, cancellation, and removal
- Notification logs for request events and due-date alerts

---

## Running Locally
1. Install dependencies:
   ```bash
   npm install
   ```
2. Start the local server:
   ```bash
   npm run start
   ```
3. Open `http://127.0.0.1:8080` in your browser.
4. Log in using:
   - Username: `student`
   - Password: `1234`

---

## Test Setup
The project includes:
- `Jest` for unit and integration tests
- `Cypress` for end-to-end browser tests

Test folders:
- `tests/unit/`
- `tests/integration/`
- `tests/e2e/`

Configuration files:
- `package.json`
- `jest.config.js`
- `cypress.config.js`

### Run tests
```bash
npm test
npm run test:unit
npm run test:integration
npm run test:e2e
npm run test:e2e:open
```

---

## New structure notes
The `student/` and `admin/` folders provide portal-level structure for the two main user roles. The `shared/` folder documents shared assets and storage contracts used by the application.

---

## Documentation Pack
The generated mini-spec package is located in `student-min-spec/`.

Included files:
- `00-context.md`
- `10-requirements.md`
- `20-api.md`
- `30-invariants.md`
- `40-acceptance.md`
- `50-edge-cases.md`
- `decisions.md`
- `README.md`
- `submission-reflection.md`
