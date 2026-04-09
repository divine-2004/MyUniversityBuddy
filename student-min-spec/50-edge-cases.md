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
