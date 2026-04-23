# Invariants

1. The profile card mirrors the latest saved or currently typed profile values.
2. Missing profile fields display a placeholder dash rather than crashing the UI.
3. Profile persistence is browser-local and depends on `localStorage`.
4. The profile feature must not require a network request to render saved data.
5. Each FAQ item contains exactly one question control and one answer container.
6. FAQ questions manage accessibility state through `aria-expanded`.
7. Expanding or collapsing one FAQ item must not remove the content of any answer.
8. The current FAQ implementation allows multiple FAQ items to stay open at the same time.
9. Requests are treated as browser-local records and are preserved through refreshes.
10. Request status is always one of: `Pending`, `In Progress`, `Completed`, or `Cancelled`.
11. Notifications are append-only logs of request state changes and due-date alerts.
12. The request list filter is case-insensitive and should not modify underlying data.
13. Admin dashboard summary counts reflect the current request collection.
14. Removing a request updates both the list and saved storage consistently.
