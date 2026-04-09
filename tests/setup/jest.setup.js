beforeEach(() => {
  document.body.innerHTML = '';
  localStorage.clear();
  jest.restoreAllMocks();

  window.alert = jest.fn();
  window.matchMedia = window.matchMedia || function matchMedia() {
    return {
      matches: false,
      media: '',
      onchange: null,
      addListener: jest.fn(),
      removeListener: jest.fn(),
      addEventListener: jest.fn(),
      removeEventListener: jest.fn(),
      dispatchEvent: jest.fn(),
    };
  };
});
