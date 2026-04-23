const { createRequestDom } = require('../setup/dom-fixtures');
const {
  saveRequests,
  getRequests,
  changeStatus,
  cancelRequest,
  removeRequest,
  clearResolved,
} = require('../../js/script');

describe('request management', () => {
  beforeEach(() => {
    localStorage.clear();
  });

  test('saves and loads request records correctly', () => {
    const requests = [
      { id: 1, title: 'Light issue', status: 'Pending' },
      { id: 2, title: 'AC repair', status: 'In Progress' },
    ];

    saveRequests(requests);
    expect(getRequests()).toEqual(requests);
  });

  test('profiles status progression through changeStatus()', () => {
    saveRequests([{ id: 10, title: 'Test request', status: 'Pending' }]);

    changeStatus(10);
    expect(getRequests()[0].status).toBe('In Progress');

    changeStatus(10);
    expect(getRequests()[0].status).toBe('Completed');
  });

  test('cancels a request without deleting it', () => {
    saveRequests([{ id: 12, title: 'Cancel me', status: 'Pending' }]);

    cancelRequest(12);
    expect(getRequests()[0].status).toBe('Cancelled');
  });

  test('removes a request from storage', () => {
    saveRequests([
      { id: 21, title: 'Keep me', status: 'Pending' },
      { id: 22, title: 'Remove me', status: 'Pending' },
    ]);

    removeRequest(22);
    expect(getRequests().map((item) => item.id)).toEqual([21]);
  });

  test('clearResolved removes completed and cancelled requests', () => {
    saveRequests([
      { id: 31, title: 'Done', status: 'Completed' },
      { id: 32, title: 'Cancelled', status: 'Cancelled' },
      { id: 33, title: 'Pending', status: 'Pending' },
    ]);

    clearResolved();
    expect(getRequests().map((item) => item.status)).toEqual(['Pending']);
  });
});
