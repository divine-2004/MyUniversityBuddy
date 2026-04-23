# Submission Reflection

## What was built
The current SNSU-FRMS assignment delivers offline-capable front-end features across both student and admin flows:
- Student profile card with live preview and browser persistence
- Help FAQ accordion with collapsible answers
- Facility request submission and local request management
- Dashboard summaries, notifications/logs, and request status workflows

## What went well
- The app already had a clear separation between pages and shared JavaScript behavior.
- `localStorage` made it straightforward to support offline persistence without a server.
- The request and notification model fit a browser-only demo environment well.
- Existing tests provide a strong foundation for profile, FAQ, and workflow behavior.

## Risks and follow-up work
- The app has no backend validation, so all data quality depends on client-side behavior.
- Role-based access control is not enforced in the current implementation; admin/student boundaries are conceptual.
- Adding explicit approve/reject and remark fields would improve the admin workflow in a future iteration.
- As the project grows, splitting `js/script.js` into smaller modules will improve maintainability.
