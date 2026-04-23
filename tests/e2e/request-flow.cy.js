describe('SNSU-FRMS request workflow', () => {
  beforeEach(() => {
    cy.visit('/notes.html', {
      onBeforeLoad(win) {
        win.localStorage.clear();
        win.localStorage.setItem('loggedIn', 'true');
      },
    });
  });

  it('student submits a facility request and it appears in the request list', () => {
    cy.get('#requestTitle').type('Broken door in Lab C');
    cy.get('#requestLocation').type('Lab C');
    cy.get('#requestType').select('Structural');
    cy.get('#requestDescription').type('The main door is stuck when closing.');
    cy.get('#requestDate').type('2026-04-23');
    cy.get('#requestDue').type('2026-04-25');
    cy.get('#requestPriority').select('High');
    cy.contains('button', 'Create request').click();

    cy.get('#taskList .request-row').should('have.length', 1);
    cy.contains('.request-label strong', 'Broken door in Lab C').should('exist');
  });

  it('changes request status and updates the request entry', () => {
    cy.get('#requestTitle').type('Flickering lights');
    cy.get('#requestLocation').type('Room 102');
    cy.get('#requestType').select('Electrical');
    cy.get('#requestDescription').type('Lights flicker when turned on.');
    cy.get('#requestDate').type('2026-04-23');
    cy.get('#requestDue').type('2026-04-26');
    cy.get('#requestPriority').select('Medium');
    cy.contains('button', 'Create request').click();

    cy.get('#taskList .request-row button').contains('Progress').click();
    cy.contains('.request-status', 'In Progress').should('exist');
  });

  it('allows the user to interact with the FAQ on the help page', () => {
    cy.visit('/help.html', {
      onBeforeLoad(win) {
        win.localStorage.setItem('loggedIn', 'true');
      },
    });

    cy.contains('.faq-question', 'How do I submit a maintenance request?')
      .click()
      .should('have.attr', 'aria-expanded', 'true');
  });
});
