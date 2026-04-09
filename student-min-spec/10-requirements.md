# Requirements

## Functional requirements

### Student Profile Card
1. The system shall collect and display the following profile fields: name, student ID, program/course, year, email, and phone.
2. The profile form shall update the visible student card preview while the user edits profile fields.
3. The profile data shall be saved in browser `localStorage`.
4. The profile page shall repopulate saved values when reopened.
5. The profile card shall remain usable offline after the page has loaded.

### FAQ Section
1. The system shall display a list of frequently asked questions on the help page.
2. Each FAQ question shall be clickable.
3. Clicking a question shall toggle its answer between collapsed and expanded states.
4. FAQ interaction shall work offline after the page has loaded.

## Non-functional requirements
1. The features shall run entirely in a modern browser without a backend.
2. The UI shall remain responsive during typing and clicking.
3. Saved profile data shall survive page refreshes in the same browser.
4. FAQ state does not need to persist across page reloads.
