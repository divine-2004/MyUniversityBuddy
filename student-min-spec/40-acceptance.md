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
