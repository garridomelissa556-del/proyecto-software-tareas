<?php

namespace App\Http\Controllers;

use App\Models\Etiqueta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EtiquetaController extends Controller
{
    /**
     * Mostrar todas las etiquetas del usuario autenticado.
     */
    public function index()
    {
        $etiquetas = Etiqueta::where('user_id', Auth::id())
            ->withCount('tareas')
            ->orderBy('nombre')
            ->get();

        return view('etiquetas.index', compact('etiquetas'));
    }

    /**
     * Mostrar formulario para crear una etiqueta.
     */
    public function create()
    {
        return view('etiquetas.create');
    }

    /**
     * Guardar una nueva etiqueta.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => [
                'required', 'string', 'max:50',
                Rule::unique('etiquetas')->where('user_id', Auth::id()),
            ],
            'color' => 'required|string|max:7',
        ]);

        $validated['user_id'] = Auth::id();

        Etiqueta::create($validated);

        return redirect()
            ->route('etiquetas.index')
            ->with('success', 'Etiqueta creada correctamente.');
    }

    /**
     * Mostrar formulario para editar.
     */
    public function edit(Etiqueta $etiqueta)
    {
        abort_if($etiqueta->user_id !== Auth::id(), 403);

        return view('etiquetas.edit', compact('etiqueta'));
    }

    /**
     * Actualizar una etiqueta.
     */
    public function update(Request $request, Etiqueta $etiqueta)
    {
        abort_if($etiqueta->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'nombre' => [
                'required', 'string', 'max:50',
                Rule::unique('etiquetas')->where('user_id', Auth::id())->ignore($etiqueta->id),
            ],
            'color' => 'required|string|max:7',
        ]);

        $etiqueta->update($validated);

        return redirect()
            ->route('etiquetas.index')
            ->with('success', 'Etiqueta actualizada correctamente.');
    }

    /**
     * Eliminar una etiqueta.
     */
    public function destroy(Etiqueta $etiqueta)
    {
        abort_if($etiqueta->user_id !== Auth::id(), 403);

        $etiqueta->delete();

        return redirect()
            ->route('etiquetas.index')
            ->with('success', 'Etiqueta eliminada correctamente.');
    }
}
