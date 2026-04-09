const { defineConfig } = require('cypress');

module.exports = defineConfig({
  e2e: {
    baseUrl: 'http://127.0.0.1:8080',
    specPattern: 'tests/e2e/**/*.cy.js',
    supportFile: 'tests/e2e/support/e2e.js',
  },
  video: false,
});
