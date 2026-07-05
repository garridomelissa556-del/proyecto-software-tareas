<?php

use App\Models\Tarea;
use App\Models\User;

// -----------------------------------------------------------------
// PRUEBAS FUNCIONALES (CRUD)
// -----------------------------------------------------------------

test('un usuario autenticado puede ver el listado de sus tareas', function () {
    $user = User::factory()->create();
    Tarea::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('tareas.index'));

    $response->assertOk();
    $response->assertSee('Pendiente');
});

test('un usuario autenticado puede crear una tarea', function () {
    $user = User::factory()->create();

    $datos = [
        'titulo' => 'Estudiar para el examen de SQA',
        'descripcion' => 'Repasar ISO 9126 y McCall',
        'estado' => 'Pendiente',
        'fecha_limite' => now()->addDays(5)->format('Y-m-d'),
        'prioridad' => 'Alta',
    ];

    $response = $this->actingAs($user)->post(route('tareas.store'), $datos);

    $response->assertRedirect(route('tareas.index'));
    $this->assertDatabaseHas('tareas', [
        'titulo' => 'Estudiar para el examen de SQA',
        'user_id' => $user->id,
    ]);
});

test('un usuario autenticado puede ver el detalle de su tarea', function () {
    $user = User::factory()->create();
    $tarea = Tarea::factory()->create(['user_id' => $user->id, 'titulo' => 'Tarea de prueba']);

    $response = $this->actingAs($user)->get(route('tareas.show', $tarea));

    $response->assertOk();
    $response->assertSee('Tarea de prueba');
});

test('un usuario autenticado puede actualizar su tarea', function () {
    $user = User::factory()->create();
    $tarea = Tarea::factory()->create(['user_id' => $user->id, 'estado' => 'Pendiente']);

    $response = $this->actingAs($user)->put(route('tareas.update', $tarea), [
        'titulo' => $tarea->titulo,
        'descripcion' => $tarea->descripcion,
        'estado' => 'Completada',
        'fecha_limite' => $tarea->fecha_limite?->format('Y-m-d'),
        'prioridad' => $tarea->prioridad,
    ]);

    $response->assertRedirect(route('tareas.index'));
    $this->assertDatabaseHas('tareas', [
        'id' => $tarea->id,
        'estado' => 'Completada',
    ]);
});

test('un usuario autenticado puede eliminar su tarea', function () {
    $user = User::factory()->create();
    $tarea = Tarea::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->delete(route('tareas.destroy', $tarea));

    $response->assertRedirect(route('tareas.index'));
    $this->assertDatabaseMissing('tareas', ['id' => $tarea->id]);
});

// -----------------------------------------------------------------
// PRUEBAS DE VALIDACIÓN
// -----------------------------------------------------------------

test('no se puede crear una tarea sin titulo', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('tareas.store'), [
        'titulo' => '',
        'estado' => 'Pendiente',
        'prioridad' => 'Media',
    ]);

    $response->assertSessionHasErrors('titulo');
    $this->assertDatabaseCount('tareas', 0);
});

test('no se puede crear una tarea con un estado invalido', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('tareas.store'), [
        'titulo' => 'Tarea con estado invalido',
        'estado' => 'Terminado',
        'prioridad' => 'Media',
    ]);

    $response->assertSessionHasErrors('estado');
});

test('no se puede crear una tarea con una prioridad invalida', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('tareas.store'), [
        'titulo' => 'Tarea con prioridad invalida',
        'estado' => 'Pendiente',
        'prioridad' => 'Urgentisima',
    ]);

    $response->assertSessionHasErrors('prioridad');
});

// -----------------------------------------------------------------
// PRUEBAS DE SISTEMA / SEGURIDAD (control de acceso)
// -----------------------------------------------------------------

test('un usuario no puede ver la tarea de otro usuario', function () {
    $propietario = User::factory()->create();
    $otroUsuario = User::factory()->create();
    $tarea = Tarea::factory()->create(['user_id' => $propietario->id]);

    $response = $this->actingAs($otroUsuario)->get(route('tareas.show', $tarea));

    $response->assertForbidden();
});

test('un usuario no puede editar la tarea de otro usuario', function () {
    $propietario = User::factory()->create();
    $otroUsuario = User::factory()->create();
    $tarea = Tarea::factory()->create(['user_id' => $propietario->id, 'estado' => 'Pendiente']);

    $response = $this->actingAs($otroUsuario)->put(route('tareas.update', $tarea), [
        'titulo' => 'Intento de edicion ajena',
        'estado' => 'Completada',
        'prioridad' => 'Alta',
    ]);

    $response->assertForbidden();
    $this->assertDatabaseHas('tareas', [
        'id' => $tarea->id,
        'estado' => 'Pendiente',
    ]);
});

test('un usuario no puede eliminar la tarea de otro usuario', function () {
    $propietario = User::factory()->create();
    $otroUsuario = User::factory()->create();
    $tarea = Tarea::factory()->create(['user_id' => $propietario->id]);

    $response = $this->actingAs($otroUsuario)->delete(route('tareas.destroy', $tarea));

    $response->assertForbidden();
    $this->assertDatabaseHas('tareas', ['id' => $tarea->id]);
});

test('un usuario autenticado puede ver el listado de sus tareas', function () {
    $user = User::factory()->create();
    Tarea::factory()->create(['user_id' => $user->id, 'estado' => 'Pendiente']);
    Tarea::factory()->create(['user_id' => $user->id, 'estado' => 'En progreso']);
    Tarea::factory()->create(['user_id' => $user->id, 'estado' => 'Completada']);

    $response = $this->actingAs($user)->get(route('tareas.index'));

    $response->assertOk();
    $response->assertSee('Pendiente');
});