# Invariants

1. The profile card mirrors the latest saved or currently typed profile values.
2. Missing profile fields display a placeholder dash rather than crashing the UI.
3. Profile persistence is browser-local and depends on `localStorage`.
4. The profile feature must not require a network request to render saved data.
5. Each FAQ item contains exactly one question control and one answer container.
6. FAQ questions manage accessibility state through `aria-expanded`.
7. Expanding or collapsing one FAQ item must not remove the content of any answer.
8. The current implementation allows multiple FAQ items to stay open at the same time.
