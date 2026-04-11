<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'numerodocumento' => '12345678',
        'tipo' => 'empleado',
        'tipodocumento' => 'cc',
    ]);

    $this->assertGuest();
    $response->assertRedirect(route('login'));
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'estado' => 'inactivo',
    ]);
});
