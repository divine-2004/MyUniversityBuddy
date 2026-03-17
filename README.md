# MyUniversityBuddy

## Features Implemented

### ✅ Student Profile Card (Feature branch: `feature/profile-card`)
- **Purpose:** Display key student information in a structured card for quick reference.
- **Expected User:** Students and university staff.
- **Main Functionality:** Shows name, ID, program, year level, and contact details.

### ✅ FAQ Section (Feature branch: `feature/faq-section`)
- **Purpose:** Provide quick answers to common student questions.
- **Expected User:** Students using the app for guidance.
- **Main Functionality:** A collapsible list of questions with answers.

---

## Mini Specs

### Feature 1: Student Profile Card
- **Purpose:** Display key student information in a structured card for quick reference.
- **Expected User:** Students and university staff.
- **Main Functionality:** Shows name, ID, program, year level, and contact details.
- **Acceptance Criteria:**
  - Card displays all required fields (Name, ID, Program, Year, Email, Phone).
  - Updates automatically when the student edits their profile (auto-save + real-time preview).
  - Works offline without needing an internet connection (data stored in `localStorage`).

### Feature 2: FAQ Section
- **Purpose:** Provide quick answers to common student questions.
- **Expected User:** Students using the app for guidance.
- **Main Functionality:** A collapsible list of questions with answers.
- **Acceptance Criteria:**
  - FAQ entries are displayed in a clean list format.
  - Clicking a question expands to show the answer.
  - Works locally without requiring internet access.

---

## What Was Implemented

- **Profile card connected to student data model:** Profile values are stored in `localStorage` and displayed in both the sidebar and the profile card.
- **Auto-updating profile card (real-time update):** The card updates live while editing profile fields, and data is auto-saved (debounced) into `localStorage`.
- **FAQ section with expandable answers:** The FAQ block uses a collapsible accordion UI; questions toggle answers with a smooth open/close animation.

---

## Problems / Challenges

- Ensuring the profile card updates as soon as the user edits fields (solved with a small debounce + live preview).
- Keeping styling consistent with existing UI while adding accordion behavior.

---

## Running Locally

1. Open `index.html` in a browser.
2. Log in using:
   - Username: `student`
   - Password: `1234`
3. Visit **Profile** to edit and view the profile card.
4. Visit **Help** to see the FAQ accordion.

---

## Screenshots (to add)

- `screenshots/profile-card.png` — Profile card displayed with student info.
- `screenshots/faq-expanded.png` — FAQ section expanded/collapsed.
