<<<<<<< HEAD
# SNSU-FRMS (Surigao del Norte State University - Facility Request and Monitoring System)
=======
# SNSU FMRMS – Assignment 1
>>>>>>> fc1f6052f84c7dff618db1d166bf3b408bbd07df

## Features Implemented
1. Student Profile Card
2. FAQ Section

---

## Mini Specs

### Feature 1: Student Profile Card
- **Purpose**: Display key student information in a structured card for quick reference.
- **Expected User**: Students and university staff.
- **Main Functionality**: Shows name, ID, program, year level, and contact details.
- **Acceptance Criteria**:
  1. Card displays all required fields (Name, ID, Program, Year, Email, Phone).
  2. Updates automatically when the student edits their profile (auto-save + real-time preview).
  3. Works offline without needing an internet connection (data stored in localStorage).

### Feature 2: FAQ Section
- **Purpose**: Provide quick answers to common student questions.
- **Expected User**: Students using the app for guidance.
- **Main Functionality**: A collapsible list of questions with answers.
- **Acceptance Criteria**:
  1. FAQ entries are displayed in a clean list format.
  2. Clicking a question expands to show the answer.
  3. Works locally without requiring internet access.

---

## What Was Implemented
- Profile card connected to student data model (stored in `localStorage`).
- Auto-updating profile card (real-time update while editing).
- FAQ section with expandable answers (accordion UI with smooth animation).

---

## Problems / Challenges
- Ensuring the profile card updates live while editing (solved with debounce + preview).
- Keeping styling consistent with the existing UI while adding accordion behavior.

---

## Running Locally
1. Open `index.html` in a browser.
2. Log in using:
   - Username: `student`
   - Password: `1234`
3. Visit **Profile** to edit and view the profile card.
4. Visit **Help** to see the FAQ accordion.

---
<<<<<<< HEAD

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

---

## Testing Setup

The project now includes:
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

### Install dependencies

```bash
npm install
```

### Run all Jest tests

```bash
npm test
```

### Run only unit tests

```bash
npm run test:unit
```

### Run only integration tests

```bash
npm run test:integration
```

### Run Cypress E2E tests

If Cypress has not downloaded its browser binary yet, run:

```bash
npx cypress install
```

Then run:

```bash
npm run test:e2e
```

Or open the interactive Cypress runner:

```bash
npm run test:e2e:open
```

### Covered test scenarios

- Profile rendering from saved data
- Profile autosave to `localStorage`
- FAQ expand/collapse toggle behavior
- Profile form updates the student card preview
- FAQ interaction flow in the browser

---

=======
>>>>>>> fc1f6052f84c7dff618db1d166bf3b408bbd07df
## Screenshots

### Login Page
![Login](screenshots/login.png)

### Dashboard
![Dashboard](screenshots/dashboard.png)

### Profile Card (initial view)
<<<<<<< HEAD
![Profile Card (initial view)](screenshots/profile-card1.png)

### Profile Card (after editing)
![Profile Card (after editing)](screenshots/profile-card2.png)

### Requests Page
![Requests](screenshots/requests.png)

### FAQ Section (collapsed)
![FAQ Section (collapsed)](screenshots/faq-expanded1.png)

### FAQ Section (expanded)
![FAQ Section (expanded)](screenshots/faq-expanded2.png)

### Notifications Page
![Notifications](screenshots/notifications.png)
