<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TareaController extends Controller
{
    /**
     * Mostrar todas las tareas del usuario autenticado.
     */
    public function index(Request $request)
    {
        $orden = $request->input('orden', 'fecha_limite');

        $tareas = Tarea::where('user_id', Auth::id())
            ->buscar($request->input('buscar'))
            ->deEstado($request->input('estado'))
            ->dePrioridad($request->input('prioridad'))
            ->when($orden === 'prioridad', function ($q) {
                $q->orderByRaw("CASE prioridad WHEN 'Alta' THEN 1 WHEN 'Media' THEN 2 WHEN 'Baja' THEN 3 END");
            })
            ->when($orden === 'reciente', function ($q) {
                $q->orderBy('created_at', 'desc');
            })
            ->when(! in_array($orden, ['prioridad', 'reciente']), function ($q) {
                $q->orderBy('fecha_limite');
            })
            ->paginate(10)
            ->withQueryString();

        $totalTareas = Tarea::where('user_id', Auth::id())->count();
        $completadas = Tarea::where('user_id', Auth::id())->where('estado', 'Completada')->count();

        return view('tareas.index', compact('tareas', 'totalTareas', 'completadas'));
    }

    /**
     * Mostrar formulario para crear una tarea.
     */
    public function create()
    {
        return view('tareas.create');
    }

    /**
     * Guardar una nueva tarea.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:Pendiente,En progreso,Completada',
            'fecha_limite' => 'nullable|date',
            'prioridad' => 'required|in:Baja,Media,Alta',
        ]);

        $validated['user_id'] = Auth::id();

        Tarea::create($validated);

        return redirect()
            ->route('tareas.index')
            ->with('success', 'Tarea creada correctamente.');
    }

    /**
     * Mostrar una tarea.
     */
    public function show(Tarea $tarea)
    {
        abort_if($tarea->user_id !== Auth::id(), 403);

        return view('tareas.show', compact('tarea'));
    }

    /**
     * Mostrar formulario para editar.
     */
    public function edit(Tarea $tarea)
    {
        abort_if($tarea->user_id !== Auth::id(), 403);

        return view('tareas.edit', compact('tarea'));
    }

    /**
     * Actualizar una tarea.
     */
    public function update(Request $request, Tarea $tarea)
    {
        abort_if($tarea->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:Pendiente,En progreso,Completada',
            'fecha_limite' => 'nullable|date',
            'prioridad' => 'required|in:Baja,Media,Alta',
        ]);

        $tarea->update($validated);

        return redirect()
            ->route('tareas.index')
            ->with('success', 'Tarea actualizada correctamente.');
    }

    /**
     * Eliminar una tarea.
     */
    public function destroy(Tarea $tarea)
    {
        abort_if($tarea->user_id !== Auth::id(), 403);

        $tarea->delete();

        return redirect()
            ->route('tareas.index')
            ->with('success', 'Tarea eliminada correctamente.');
    }

    /**
     * Mostrar las tareas eliminadas del usuario autenticado.
     */
    public function papelera()
    {
        $tareas = Tarea::onlyTrashed()
            ->where('user_id', Auth::id())
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return view('tareas.papelera', compact('tareas'));
    }

    /**
     * Restaurar una tarea eliminada.
     */
    public function restaurar(string $id)
    {
        $tarea = Tarea::onlyTrashed()->findOrFail($id);

        abort_if($tarea->user_id !== Auth::id(), 403);

        $tarea->restore();

        return redirect()
            ->route('tareas.papelera')
            ->with('success', 'Tarea restaurada correctamente.');
    }

    /**
     * Eliminar una tarea de forma definitiva.
     */
    public function forzarEliminacion(string $id)
    {
        $tarea = Tarea::onlyTrashed()->findOrFail($id);

        abort_if($tarea->user_id !== Auth::id(), 403);

        $tarea->forceDelete();

        return redirect()
            ->route('tareas.papelera')
            ->with('success', 'Tarea eliminada de forma definitiva.');
    }
}
