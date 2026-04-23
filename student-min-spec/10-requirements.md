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

### Facility Request Submission
1. Students shall be able to submit a facility request with title, facility/location, type, description, reported date, due date, and priority.
2. Requests shall be stored locally in `localStorage` under `facilityRequests`.
3. Students shall be able to filter request entries by keyword, location, or status.
4. Request creation shall trigger a local notification update.

### Admin Dashboard and Request Management
1. The dashboard shall display summary counts for request statuses.
2. Admin users shall be able to view all requests in a list.
3. Admin users shall be able to progress requests through status changes and cancel or remove requests.
4. The request list shall show request metadata and timestamps for reported and due dates.
5. Notifications or logs shall record request state changes and due-date alerts.

### FAQ Management and Student Viewer
1. The system shall allow admin users to view FAQ entries and related help content.
2. Admin users shall be able to view student profile information associated with requests.
3. FAQ management actions shall not break the student FAQ accordion behavior.

## Non-functional requirements
1. The features shall run entirely in a modern browser without a backend.
2. The UI shall remain responsive during typing, form submission, and interactive toggles.
3. Saved request, profile, FAQ, and notification data shall survive page reloads in the same browser.
4. The user interface shall remain usable when offline after the initial page load.
5. Existing HTML pages and JavaScript behavior shall be reused where possible.
