const { createFaqDom } = require('../setup/dom-fixtures');
const { getFaqs, saveFaqs, addFaq, editFaq, deleteFaq, renderFaqs } = require('../../js/script');

describe('faq management', () => {
  beforeEach(() => {
    localStorage.clear();
    createFaqDom();
  });

  test('adds a new faq to storage', () => {
    saveFaqs([]);
    addFaq('New question?', 'New answer.');

    const faqs = getFaqs();
    expect(faqs).toHaveLength(1);
    expect(faqs[0]).toMatchObject({ question: 'New question?', answer: 'New answer.' });
  });

  test('edits an existing faq', () => {
    saveFaqs([{ id: 1, question: 'Old?', answer: 'Old answer.' }]);
    editFaq(1, 'Updated?', 'Updated answer.');

    const faqs = getFaqs();
    expect(faqs[0]).toMatchObject({ question: 'Updated?', answer: 'Updated answer.' });
  });

  test('deletes an faq', () => {
    saveFaqs([
      { id: 1, question: 'Q1', answer: 'A1' },
      { id: 2, question: 'Q2', answer: 'A2' },
    ]);

    deleteFaq(1);
    expect(getFaqs()).toEqual([{ id: 2, question: 'Q2', answer: 'A2' }]);
  });
});
