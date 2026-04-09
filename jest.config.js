module.exports = {
  testEnvironment: 'jsdom',
  roots: ['<rootDir>/tests'],
  setupFilesAfterEnv: ['<rootDir>/tests/setup/jest.setup.js'],
  testMatch: ['**/*.test.js'],
  collectCoverageFrom: ['js/**/*.js'],
};
