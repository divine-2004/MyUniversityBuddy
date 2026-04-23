const { createRequestDom } = require('../setup/dom-fixtures');
const { addRequest, renderRequests, getRequests } = require('../../js/script');

describe('request management integration', () => {
  beforeEach(() => {
    localStorage.clear();
    createRequestDom();
  });

  test('submitting a request persists it and renders it in the list', () => {
    document.getElementById('requestTitle').value = 'Broken window in Lab A';
    document.getElementById('requestLocation').value = 'Lab A';
    document.getElementById('requestType').value = 'Structural';
    document.getElementById('requestDescription').value = 'The window is cracked and needs replacement.';
    document.getElementById('requestDate').value = '2026-04-23';
    document.getElementById('requestDue').value = '2026-04-25';
    document.getElementById('requestPriority').value = 'High';

    addRequest();
    renderRequests();

    const requests = getRequests();
    expect(requests).toHaveLength(1);
    expect(requests[0]).toMatchObject({
      title: 'Broken window in Lab A',
      location: 'Lab A',
      issueType: 'Structural',
      priority: 'High',
      status: 'Pending',
    });
    expect(document.querySelectorAll('.request-row').length).toBe(1);
  });

  test('filter input narrows the displayed request list', () => {
    document.getElementById('requestTitle').value = 'Broken fan';
    document.getElementById('requestLocation').value = 'Room 101';
    document.getElementById('requestType').value = 'Electrical';
    document.getElementById('requestDescription').value = 'The ceiling fan is not spinning.';
    document.getElementById('requestDate').value = '2026-04-23';
    document.getElementById('requestDue').value = '2026-04-25';
    document.getElementById('requestPriority').value = 'Medium';

    addRequest();
    document.getElementById('requestFilter').value = 'room 101';
    renderRequests();

    expect(document.querySelectorAll('.request-row').length).toBe(1);
  });
});
