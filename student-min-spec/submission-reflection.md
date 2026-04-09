# Submission Reflection

## What was built
The current SNSU-FRMS assignment delivers two offline-capable front-end features: a student profile card with live preview and browser persistence, and a help-page FAQ accordion with collapsible answers.

## What went well
- The app already had a clear separation between pages and shared JavaScript behavior.
- `localStorage` made it straightforward to support offline persistence.
- The FAQ behavior is simple and easy to verify through tests.

## Risks and follow-up work
- The app has no backend validation, so all data quality depends on client-side behavior.
- Encoding artifacts in the HTML text should be cleaned up later for presentation quality.
- If the project grows, moving from global functions to modular scripts would improve maintainability and testability.
