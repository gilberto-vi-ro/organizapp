// Script de Test de Seguridad
//https://glebbahmutov.com/cypress-examples/7.1.0/commands/actions.html#select
describe('Test de funcionalidad - Administrador', () => {
    
	it('conectando con localhost y el proyecto', () => {
        cy.visit('http://localhost/program/organizapp/login'); // carga el proyecto en localhost
		cy.wait(3000);
    })
	
	// Se intenta hacer login con datos inexistentes en la BD
	it('Request - login usuario X', () => {
		
		//se envía el valor de usuariox como nombre de usuario
		cy.get('[name="email"]').type('usuariox@gmail.com') 
		//se envía el valor de password123 como Password de usuario
		//la propiedad {enter} equivale a un enter para envío de datos
		cy.get('[name="pwd"]').type('password123')

		//CAPTCHA
		cy.get('.login-captcha').click()
		cy.get('#randomfield').clear( { force: true })
		cy.get('#randomfield').type('AAAA', { force: true })
		cy.get('#captchaEnter').type('AAAA')
		cy.get('.msg-btn-ok').click()

		//submit
		cy.get('[name="pwd"]').type('{enter}')
		//msg
		cy.get('.msg-btn-aceptar').click()
		
		cy.wait(3000);
		
		})

		// Se intenta hacer login una inyección de SQL básico
	it('Request - login inyección SQL Básico', () => {
		
		cy.get('[name="email"]').type('admin@gmail.com')
		
		cy.get('[name="pwd"]').type('" or ""={enter}')

		//CAPTCHA
		cy.get('.login-captcha').click()
		cy.get('#randomfield').clear( { force: true })
		cy.get('#randomfield').type('AAAA', { force: true })
		cy.get('#captchaEnter').type('AAAA')
		cy.get('.msg-btn-ok').click()

		//submit
		cy.get('[name="pwd"]').type('{enter}')
		//msg
		cy.get('.msg-btn-aceptar').click()
		
		cy.wait(3000);
		
		})
	
	// Se intenta hacer login con datos existentes en la BD
	it('Request - login Administrador', () => {
		
		cy.get('[name="email"]').type('admin@gmail.com')

		cy.get('[name="pwd"]').type('admin{enter}')

		//CAPTCHA
		cy.get('.login-captcha').click()
		cy.get('#randomfield').clear( { force: true })
		cy.get('#randomfield').type('AAAA', { force: true })
		cy.get('#captchaEnter').type('AAAA')
		cy.get('.msg-btn-ok').click()

		//submit
		cy.get('[name="pwd"]').type('{enter}')
		//msg
		//cy.get('.msg-btn-aceptar').click()
		
		cy.wait(3000);
		
		})

})