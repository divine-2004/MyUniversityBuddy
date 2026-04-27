const { createRequestDom } = require('../setup/dom-fixtures');
const {
  saveRequests,
  getRequests,
  changeStatus,
  cancelRequest,
  approveRequest,
  rejectRequest,
  addRequestRemark,
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

  test('approves and rejects requests and records history', () => {
    saveRequests([{ id: 41, title: 'Window repair', status: 'Pending', history: [] }]);

    approveRequest(41);
    expect(getRequests()[0].status).toBe('Approved');
    expect(getRequests()[0].history.pop().action).toBe('Approved');

    rejectRequest(41);
    expect(getRequests()[0].status).toBe('Rejected');
    expect(getRequests()[0].history.pop().action).toBe('Rejected');
  });

  test('adds a remark to a request', () => {
    saveRequests([{ id: 51, title: 'Fan issue', status: 'Pending', history: [] }]);
    jest.spyOn(window, 'prompt').mockReturnValue('Needs urgent attention');

    addRequestRemark(51);

    expect(getRequests()[0].remark).toBe('Needs urgent attention');
    expect(getRequests()[0].history.pop().action).toBe('Remark added');
    window.prompt.mockRestore();
  });
});
