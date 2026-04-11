<?php
use App\Models\User;

it('returns a successful response', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/');

    $response->assertStatus(302); // Redirige a /welcome o /login dependiendo del estado, pero 302 es esperado si hay lógica de redirección.
    // Si queremos probar que carga bien una página, probemos /welcome si es admin
    $user->update(['tipo' => 'admin', 'estado' => 'activo']);
    $response = $this->actingAs($user)->get('/welcome');
    $response->assertStatus(200);
});
