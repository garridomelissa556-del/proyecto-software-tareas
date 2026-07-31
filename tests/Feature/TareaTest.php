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
    $this->assertSoftDeleted('tareas', ['id' => $tarea->id]);
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

test('un usuario autenticado puede ver tareas con distintos estados en el listado', function () {
    $user = User::factory()->create();
    Tarea::factory()->create(['user_id' => $user->id, 'estado' => 'Pendiente']);
    Tarea::factory()->create(['user_id' => $user->id, 'estado' => 'En progreso']);
    Tarea::factory()->create(['user_id' => $user->id, 'estado' => 'Completada']);

    $response = $this->actingAs($user)->get(route('tareas.index'));

    $response->assertOk();
    $response->assertSee('Pendiente');
});

// -----------------------------------------------------------------
// PRUEBAS DE BÚSQUEDA, FILTROS Y ORDEN (V1.1)
// -----------------------------------------------------------------

test('el listado se puede filtrar por estado', function () {
    $user = User::factory()->create();
    Tarea::factory()->create(['user_id' => $user->id, 'titulo' => 'Tarea pendiente', 'estado' => 'Pendiente']);
    Tarea::factory()->create(['user_id' => $user->id, 'titulo' => 'Tarea completada', 'estado' => 'Completada']);

    $response = $this->actingAs($user)->get(route('tareas.index', ['estado' => 'Completada']));

    $response->assertOk();
    $response->assertSee('Tarea completada');
    $response->assertDontSee('Tarea pendiente');
});

test('el listado se puede filtrar por prioridad', function () {
    $user = User::factory()->create();
    Tarea::factory()->create(['user_id' => $user->id, 'titulo' => 'Tarea alta', 'prioridad' => 'Alta']);
    Tarea::factory()->create(['user_id' => $user->id, 'titulo' => 'Tarea baja', 'prioridad' => 'Baja']);

    $response = $this->actingAs($user)->get(route('tareas.index', ['prioridad' => 'Alta']));

    $response->assertOk();
    $response->assertSee('Tarea alta');
    $response->assertDontSee('Tarea baja');
});

test('el listado se puede buscar por texto en el titulo o la descripcion', function () {
    $user = User::factory()->create();
    Tarea::factory()->create(['user_id' => $user->id, 'titulo' => 'Estudiar para el examen', 'descripcion' => 'Repasar apuntes']);
    Tarea::factory()->create(['user_id' => $user->id, 'titulo' => 'Comprar víveres', 'descripcion' => 'Ir al mercado']);

    $response = $this->actingAs($user)->get(route('tareas.index', ['buscar' => 'examen']));

    $response->assertOk();
    $response->assertSee('Estudiar para el examen');
    $response->assertDontSee('Comprar víveres');
});

test('los filtros solo aplican sobre las tareas del usuario autenticado', function () {
    $user = User::factory()->create();
    $otroUsuario = User::factory()->create();
    Tarea::factory()->create(['user_id' => $user->id, 'titulo' => 'Tarea propia', 'estado' => 'Pendiente']);
    Tarea::factory()->create(['user_id' => $otroUsuario->id, 'titulo' => 'Tarea ajena', 'estado' => 'Pendiente']);

    $response = $this->actingAs($user)->get(route('tareas.index', ['estado' => 'Pendiente']));

    $response->assertOk();
    $response->assertSee('Tarea propia');
    $response->assertDontSee('Tarea ajena');
});

test('el listado se puede ordenar por prioridad de mayor a menor', function () {
    $user = User::factory()->create();
    Tarea::factory()->create(['user_id' => $user->id, 'titulo' => 'Tarea baja', 'prioridad' => 'Baja']);
    Tarea::factory()->create(['user_id' => $user->id, 'titulo' => 'Tarea alta', 'prioridad' => 'Alta']);
    Tarea::factory()->create(['user_id' => $user->id, 'titulo' => 'Tarea media', 'prioridad' => 'Media']);

    $response = $this->actingAs($user)->get(route('tareas.index', ['orden' => 'prioridad']));

    $response->assertOk();
    $contenido = $response->getContent();

    $posicionAlta = strpos($contenido, 'Tarea alta');
    $posicionMedia = strpos($contenido, 'Tarea media');
    $posicionBaja = strpos($contenido, 'Tarea baja');

    expect($posicionAlta)->toBeLessThan($posicionMedia);
    expect($posicionMedia)->toBeLessThan($posicionBaja);
});

// -----------------------------------------------------------------
// PRUEBAS DE PAPELERA DE RECICLAJE (V1.2)
// -----------------------------------------------------------------

test('al eliminar una tarea esta no se borra de la base de datos sino que se marca como eliminada', function () {
    $user = User::factory()->create();
    $tarea = Tarea::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->delete(route('tareas.destroy', $tarea));

    $this->assertSoftDeleted('tareas', ['id' => $tarea->id]);
});

test('una tarea eliminada no aparece en el listado principal', function () {
    $user = User::factory()->create();
    $tarea = Tarea::factory()->create(['user_id' => $user->id, 'titulo' => 'Organizar el armario']);

    $this->actingAs($user)->delete(route('tareas.destroy', $tarea));

    $response = $this->actingAs($user)->get(route('tareas.index'));

    $response->assertOk();
    $response->assertDontSee('Organizar el armario');
});

test('un usuario autenticado puede ver sus tareas eliminadas en la papelera', function () {
    $user = User::factory()->create();
    $tarea = Tarea::factory()->create(['user_id' => $user->id, 'titulo' => 'Tarea en papelera']);
    $tarea->delete();

    $response = $this->actingAs($user)->get(route('tareas.papelera'));

    $response->assertOk();
    $response->assertSee('Tarea en papelera');
});

test('un usuario autenticado puede restaurar una tarea eliminada', function () {
    $user = User::factory()->create();
    $tarea = Tarea::factory()->create(['user_id' => $user->id]);
    $tarea->delete();

    $response = $this->actingAs($user)->patch(route('tareas.restaurar', $tarea->id));

    $response->assertRedirect(route('tareas.papelera'));
    $this->assertDatabaseHas('tareas', ['id' => $tarea->id, 'deleted_at' => null]);
});

test('un usuario autenticado puede eliminar una tarea de forma definitiva desde la papelera', function () {
    $user = User::factory()->create();
    $tarea = Tarea::factory()->create(['user_id' => $user->id]);
    $tarea->delete();

    $response = $this->actingAs($user)->delete(route('tareas.forzar', $tarea->id));

    $response->assertRedirect(route('tareas.papelera'));
    $this->assertDatabaseMissing('tareas', ['id' => $tarea->id]);
});

test('un usuario no puede restaurar la tarea eliminada de otro usuario', function () {
    $propietario = User::factory()->create();
    $otroUsuario = User::factory()->create();
    $tarea = Tarea::factory()->create(['user_id' => $propietario->id]);
    $tarea->delete();

    $response = $this->actingAs($otroUsuario)->patch(route('tareas.restaurar', $tarea->id));

    $response->assertForbidden();
    $this->assertSoftDeleted('tareas', ['id' => $tarea->id]);
});

test('un usuario no puede eliminar de forma definitiva la tarea de otro usuario', function () {
    $propietario = User::factory()->create();
    $otroUsuario = User::factory()->create();
    $tarea = Tarea::factory()->create(['user_id' => $propietario->id]);
    $tarea->delete();

    $response = $this->actingAs($otroUsuario)->delete(route('tareas.forzar', $tarea->id));

    $response->assertForbidden();
    $this->assertSoftDeleted('tareas', ['id' => $tarea->id]);
});