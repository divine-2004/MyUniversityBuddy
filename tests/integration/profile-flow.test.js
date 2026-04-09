const { createProfileDom } = require('../setup/dom-fixtures');
const { STORAGE_KEYS, setupProfileAutoSave } = require('../../js/script');

describe('profile form integration', () => {
  beforeEach(() => {
    jest.useFakeTimers();
    createProfileDom();
    setupProfileAutoSave();
  });

  afterEach(() => {
    jest.useRealTimers();
  });

  test('form input updates the student card and persists after debounce', () => {
    const nameInput = document.getElementById('name');
    const courseInput = document.getElementById('course');

    nameInput.value = 'Cara Villanueva';
    nameInput.dispatchEvent(new Event('input', { bubbles: true }));
    courseInput.value = 'BSCE';
    courseInput.dispatchEvent(new Event('input', { bubbles: true }));

    jest.advanceTimersByTime(850);

    expect(document.getElementById('cardName').textContent).toBe('Cara Villanueva');
    expect(document.getElementById('sidebarProfileName').textContent).toBe('Cara Villanueva');
    expect(document.getElementById('cardCourse').textContent).toBe('BSCE');
    expect(JSON.parse(localStorage.getItem(STORAGE_KEYS.profile))).toMatchObject({
      name: 'Cara Villanueva',
      course: 'BSCE',
    });
  });
});
