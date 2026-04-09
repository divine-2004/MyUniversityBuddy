const { createProfileDom } = require('../setup/dom-fixtures');
const { STORAGE_KEYS, updateStudentCard, saveProfile } = require('../../js/script');

describe('student profile card', () => {
  test('renders stored profile details into the card', () => {
    createProfileDom();
    localStorage.setItem(
      STORAGE_KEYS.profile,
      JSON.stringify({
        name: 'Alex Dela Cruz',
        studentId: '2024-00123',
        course: 'BSIT',
        year: '3',
        email: 'alex@snsu.edu.ph',
        phone: '+63 912 345 6789',
      })
    );

    updateStudentCard();

    expect(document.getElementById('cardName').textContent).toBe('Alex Dela Cruz');
    expect(document.getElementById('cardId').textContent).toBe('2024-00123');
    expect(document.getElementById('cardCourse').textContent).toBe('BSIT');
    expect(document.getElementById('cardYear').textContent).toBe('3');
    expect(document.getElementById('cardEmail').textContent).toBe('alex@snsu.edu.ph');
    expect(document.getElementById('cardPhone').textContent).toBe('+63 912 345 6789');
  });

  test('saves profile data to localStorage', () => {
    createProfileDom();
    document.getElementById('name').value = 'Mia Santos';
    document.getElementById('studentId').value = '2024-00999';
    document.getElementById('course').value = 'BSED';
    document.getElementById('year').value = '2';
    document.getElementById('email').value = 'mia@snsu.edu.ph';
    document.getElementById('phone').value = '09123456789';

    saveProfile(false);

    expect(JSON.parse(localStorage.getItem(STORAGE_KEYS.profile))).toMatchObject({
      name: 'Mia Santos',
      studentId: '2024-00999',
      course: 'BSED',
      year: '2',
      email: 'mia@snsu.edu.ph',
      phone: '09123456789',
    });
  });
});
