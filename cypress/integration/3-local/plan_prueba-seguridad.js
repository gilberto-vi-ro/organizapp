// Script de Test de Seguridad
//https://glebbahmutov.com/cypress-examples/7.1.0/commands/actions.html#select
describe('Test de funcionalidad - Administrador', () => {
    
	it('conectando con localhost y el proyecto', () => {
        cy.visit('http://localhost/program/organizapp/login'); // Carga el proyecto en localhost
		cy.wait(3000);
    })
	
	// Se intenta hacer login con datos inexistentes en la BD
	it('Request - login usuario X', () => {
		
		//Se envía el valor de usuariox como nombre de usuario
		cy.get('[name="email"]').type('usuariox@gmail.com') 
		//Se envía el valor de password123 como Password de usuario
		//La propiedad {enter} equivale a un enter para envío de datos
		cy.get('[name="pwd"]').type('password123')

		//CAPTCHA
		cy.get('.login-captcha').click()//Esta funcion da click en el capptcha
		cy.get('#randomfield').clear( { force: true })//Esto limpia el captcha
		cy.get('#randomfield').type('AAAA', { force: true }) // Aqui se forza el captcha con un valor AAAA
		cy.get('#captchaEnter').type('AAAA') //Se escribe el captcha
		cy.get('.msg-btn-ok').click() // Da click en verificar captcha

		//SUBMIT
		cy.get('[name="pwd"]').type('{enter}')// Da enter para loguearse
		//MSG
		cy.get('.msg-btn-aceptar').click() // En caso de un mensaje, cierra la ventana
		
		cy.wait(3000); // Espera 3 segundos mientras se realiza esta prueba
		
		})

	// Se intenta hacer login con una inyección de SQL básico
	it('Request - login inyección SQL Básico', () => {
		
		//Se envía el valor de email del usuario
		cy.get('[name="email"]').type('admin@gmail.com')
		//Se envía un valor con inyeccion SQL como Password de usuario
		cy.get('[name="pwd"]').type('" or ""={enter}')

		//CAPTCHA
		cy.get('.login-captcha').click()//Esta funcion da click en el capptcha
		cy.get('#randomfield').clear( { force: true })//Esto limpia el captcha
		cy.get('#randomfield').type('AAAA', { force: true }) // Aqui se forza el captcha con un valor AAAA
		cy.get('#captchaEnter').type('AAAA') //Se escribe el captcha
		cy.get('.msg-btn-ok').click() // Da click en verificar captcha

		//SUBMIT
		cy.get('[name="pwd"]').type('{enter}')// Da enter para loguearse
		//MSG
		cy.get('.msg-btn-aceptar').click() // En caso de un mensaje, cierra la ventana
		
		cy.wait(3000); // Espera 3 segundos mientras se realiza esta prueba
		
		})
	
	// Se intenta hacer login con datos existentes en la BD
	it('Request - login Administrador', () => {
		
		//Se envía el valor de email del usuario
		cy.get('[name="email"]').type('admin@gmail.com')
		//Se envía la contraseña del usuario
		cy.get('[name="pwd"]').type('admin{enter}')

		//CAPTCHA
		cy.get('.login-captcha').click()//Esta funcion da click en el capptcha
		cy.get('#randomfield').clear( { force: true })//Esto limpia el captcha
		cy.get('#randomfield').type('AAAA', { force: true }) // Aqui se forza el captcha con un valor AAAA
		cy.get('#captchaEnter').type('AAAA') //Se escribe el captcha
		cy.get('.msg-btn-ok').click() // Da click en verificar captcha

		//SUBMIT
		cy.get('[name="pwd"]').type('{enter}')// Da enter para loguearse
		//MSG
		cy.get('.msg-btn-aceptar').click() // En caso de un mensaje, cierra la ventana
		
		cy.wait(3000); // Espera 3 segundos mientras se realiza esta prueba
		
		})

})