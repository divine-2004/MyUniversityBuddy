# Decisions

1. The feature set is documented against the existing static implementation instead of inventing backend APIs that do not exist.
2. `localStorage` is the persistence mechanism because the current app is designed to work offline and has no server.
3. Jest with `jsdom` is used for unit and integration testing because the behavior is DOM-heavy but still lightweight.
4. Cypress is used for E2E testing because it validates the real browser interaction flow on the existing HTML pages.
5. The test setup adds exports to `js/script.js` for Node-based tests without altering browser-side initialization.
6. The specs treat multiple simultaneously open FAQ items as valid because that matches the current accordion behavior.
7. The student/admin structure is represented through folder-level documentation and portal pages to preserve the existing root implementation.
8. Existing dashboard and request pages are reused for admin monitoring and request management rather than introducing a backend.
