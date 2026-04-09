const { createFaqDom } = require('../setup/dom-fixtures');
const { initFaqAccordion } = require('../../js/script');

describe('faq interaction flow', () => {
  test('users can open multiple faq items independently', () => {
    createFaqDom();
    initFaqAccordion();

    const questions = document.querySelectorAll('.faq-question');
    const items = document.querySelectorAll('.faq-item');

    questions[0].click();
    questions[1].click();

    expect(items[0].classList.contains('open')).toBe(true);
    expect(items[1].classList.contains('open')).toBe(true);
    expect(questions[0].getAttribute('aria-expanded')).toBe('true');
    expect(questions[1].getAttribute('aria-expanded')).toBe('true');
  });
});
