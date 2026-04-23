# Acceptance Criteria

## Student Profile Card
1. Given a student is on `profile.html`, when they type into a profile field, then the student card preview updates immediately.
2. Given a student edits profile details, when autosave completes or the save button is clicked, then the data is stored in `localStorage`.
3. Given profile data already exists in `localStorage`, when the student reloads `profile.html`, then the form and card show the saved values.
4. Given the device is offline, when the student revisits the already loaded profile page, then the profile card still works from local browser storage.

## FAQ Section
1. Given a student is on `help.html`, when they click an FAQ question, then the corresponding answer expands.
2. Given an FAQ answer is expanded, when the same question is clicked again, then the answer collapses.
3. Given the device is offline, when the help page is already available locally, then FAQ expand/collapse still works.

## Facility Request Submission
1. Given a student fills the request form on `notes.html`, when they submit the request, then a new request appears in the task list.
2. Given a request exists, when the student filters requests, then only matching entries remain visible.
3. Given a request is created, when the page reloads, then the request still exists in `localStorage`.

## Admin Dashboard and Request Management
1. Given an admin views `index.html`, when the dashboard loads, then summary statistics reflect stored request statuses.
2. Given an admin views the request list, when they change a request status, then the request updates and the notifications log receives an entry.
3. Given an admin cancels or removes a request, then the request disappears from the visible list and storage is updated.
4. Given request history exists, when the admin opens `notifications.html`, then transaction alerts and due-date reminders are visible.
