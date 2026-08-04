<?php

use App\Models\Etiqueta;
use App\Models\User;

// -----------------------------------------------------------------
// PRUEBAS FUNCIONALES (CRUD)
// -----------------------------------------------------------------

test('un usuario autenticado puede ver el listado de sus etiquetas', function () {
    $user = User::factory()->create();
    Etiqueta::factory()->create(['user_id' => $user->id, 'nombre' => 'Trabajo']);

    $response = $this->actingAs($user)->get(route('etiquetas.index'));

    $response->assertOk();
    $response->assertSee('Trabajo');
});

test('un usuario autenticado puede crear una etiqueta', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('etiquetas.store'), [
        'nombre' => 'Estudio',
        'color' => '#22C55E',
    ]);

    $response->assertRedirect(route('etiquetas.index'));
    $this->assertDatabaseHas('etiquetas', [
        'nombre' => 'Estudio',
        'user_id' => $user->id,
    ]);
});

test('no se pueden crear dos etiquetas con el mismo nombre para un mismo usuario', function () {
    $user = User::factory()->create();
    Etiqueta::factory()->create(['user_id' => $user->id, 'nombre' => 'Trabajo']);

    $response = $this->actingAs($user)->post(route('etiquetas.store'), [
        'nombre' => 'Trabajo',
        'color' => '#22C55E',
    ]);

    $response->assertSessionHasErrors('nombre');
    $this->assertDatabaseCount('etiquetas', 1);
});

test('un usuario autenticado puede actualizar su etiqueta', function () {
    $user = User::factory()->create();
    $etiqueta = Etiqueta::factory()->create(['user_id' => $user->id, 'nombre' => 'Trabajo']);

    $response = $this->actingAs($user)->put(route('etiquetas.update', $etiqueta), [
        'nombre' => 'Trabajo urgente',
        'color' => '#EF4444',
    ]);

    $response->assertRedirect(route('etiquetas.index'));
    $this->assertDatabaseHas('etiquetas', [
        'id' => $etiqueta->id,
        'nombre' => 'Trabajo urgente',
    ]);
});

test('un usuario autenticado puede eliminar su etiqueta', function () {
    $user = User::factory()->create();
    $etiqueta = Etiqueta::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->delete(route('etiquetas.destroy', $etiqueta));

    $response->assertRedirect(route('etiquetas.index'));
    $this->assertDatabaseMissing('etiquetas', ['id' => $etiqueta->id]);
});

// -----------------------------------------------------------------
// PRUEBAS DE SISTEMA / SEGURIDAD (control de acceso)
// -----------------------------------------------------------------

test('un usuario no ve las etiquetas de otro usuario en su listado', function () {
    $user = User::factory()->create();
    $otroUsuario = User::factory()->create();
    Etiqueta::factory()->create(['user_id' => $user->id, 'nombre' => 'Mia']);
    Etiqueta::factory()->create(['user_id' => $otroUsuario->id, 'nombre' => 'Ajena']);

    $response = $this->actingAs($user)->get(route('etiquetas.index'));

    $response->assertOk();
    $response->assertSee('Mia');
    $response->assertDontSee('Ajena');
});

test('un usuario no puede editar la etiqueta de otro usuario', function () {
    $propietario = User::factory()->create();
    $otroUsuario = User::factory()->create();
    $etiqueta = Etiqueta::factory()->create(['user_id' => $propietario->id, 'nombre' => 'Original']);

    $response = $this->actingAs($otroUsuario)->put(route('etiquetas.update', $etiqueta), [
        'nombre' => 'Intento ajeno',
        'color' => '#EF4444',
    ]);

    $response->assertForbidden();
    $this->assertDatabaseHas('etiquetas', ['id' => $etiqueta->id, 'nombre' => 'Original']);
});

test('un usuario no puede eliminar la etiqueta de otro usuario', function () {
    $propietario = User::factory()->create();
    $otroUsuario = User::factory()->create();
    $etiqueta = Etiqueta::factory()->create(['user_id' => $propietario->id]);

    $response = $this->actingAs($otroUsuario)->delete(route('etiquetas.destroy', $etiqueta));

    $response->assertForbidden();
    $this->assertDatabaseHas('etiquetas', ['id' => $etiqueta->id]);
});
