# Edge Cases

## Profile
- `localStorage` contains invalid JSON: the UI should fall back to an empty profile.
- Some form elements are missing from the DOM: helper functions should avoid throwing where possible.
- The user leaves fields blank: the card should show placeholder dashes for missing values.
- The uploaded photo is absent: the app should use the default generated avatar.
- The browser blocks `localStorage`: persistence will fail and should be treated as an environment limitation.
- Autosave debounce means data is not written on every keystroke instantly.

## FAQ
- A `.faq-item` missing either `.faq-question` or `.faq-answer` should be skipped safely.
- Very long answer content should still expand using measured `scrollHeight`.
- Repeated clicks should keep `aria-expanded`, CSS class state, and `max-height` in sync.
- Admin FAQ management actions should not prevent the student FAQ accordion from working.

## Facility Requests and Admin
- An empty request title, location, or description should block creation and show an alert.
- A request ID may collide only if the timestamp generator repeats; using `Date.now()` is acceptable for demo data.
- A cancelled or completed request should not be eligible for status progression.
- If `facilityRequests` contains invalid JSON, the app should return an empty request list instead of crashing.
- Notifications should be preserved even if request details later change.
- Filtering with no matches should show an empty state rather than hide the request container.
