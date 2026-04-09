const { createFaqDom } = require('../setup/dom-fixtures');
const { initFaqAccordion } = require('../../js/script');

describe('faq accordion', () => {
  test('toggles an answer open and closed', () => {
    createFaqDom();
    initFaqAccordion();

    const item = document.querySelector('.faq-item');
    const question = item.querySelector('.faq-question');
    const answer = item.querySelector('.faq-answer');

    expect(question.getAttribute('aria-expanded')).toBe('false');
    expect(answer.style.maxHeight).toBe('0');

    question.click();
    expect(question.getAttribute('aria-expanded')).toBe('true');
    expect(item.classList.contains('open')).toBe(true);
    expect(answer.style.maxHeight).toBe('120px');

    question.click();
    expect(question.getAttribute('aria-expanded')).toBe('false');
    expect(item.classList.contains('open')).toBe(false);
    expect(answer.style.maxHeight).toBe('0');
  });
});
