describe('SNSU-FRMS profile and FAQ flows', () => {
  beforeEach(() => {
    cy.visit('/profile.html', {
      onBeforeLoad(win) {
        win.localStorage.clear();
        win.localStorage.setItem('loggedIn', 'true');
      },
    });
  });

  it('updates the profile card as the user edits the profile', () => {
    cy.get('#name').type('Jamie Reyes');
    cy.get('#studentId').type('2024-00456');
    cy.get('#course').type('BS Information Technology');
    cy.get('#year').type('4');
    cy.get('#email').type('jamie@snsu.edu.ph');
    cy.get('#phone').type('09171234567');

    cy.get('#cardName').should('have.text', 'Jamie Reyes');
    cy.get('#cardId').should('have.text', '2024-00456');
    cy.get('#cardCourse').should('have.text', 'BS Information Technology');
    cy.get('#cardYear').should('have.text', '4');
    cy.get('#cardEmail').should('have.text', 'jamie@snsu.edu.ph');
    cy.get('#cardPhone').should('have.text', '09171234567');
  });

  it('expands and collapses faq answers on the help page', () => {
    cy.visit('/help.html', {
      onBeforeLoad(win) {
        win.localStorage.setItem('loggedIn', 'true');
      },
    });

    cy.contains('.faq-question', 'How do I submit a maintenance request?')
      .as('firstQuestion')
      .should('have.attr', 'aria-expanded', 'false')
      .click()
      .should('have.attr', 'aria-expanded', 'true');

    cy.contains('.faq-answer', 'Go to the "Requests" page').should(($answer) => {
      expect($answer[0].style.maxHeight).to.not.equal('0px');
    });

    cy.get('@firstQuestion')
      .click()
      .should('have.attr', 'aria-expanded', 'false');
  });
});
