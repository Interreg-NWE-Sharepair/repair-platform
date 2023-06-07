context('Website', () => {
    it('works', () => {
        cy.visit(Cypress.env('APP_URL'));
    });
});
