<?php

namespace App\Http\Controllers;

use App\Models\Etiqueta;
use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TareaController extends Controller
{
    public function index(Request $request)
    {
        $orden = $request->input('orden', 'fecha_limite');

        $tareas = Tarea::where('user_id', Auth::id())
            ->with('etiquetas')
            ->buscar($request->input('buscar'))
            ->deEstado($request->input('estado'))
            ->dePrioridad($request->input('prioridad'))
            ->deEtiqueta($request->input('etiqueta'))
            ->when($orden === 'prioridad', function ($query) {
                $query->orderByRaw(
                    "CASE prioridad
                        WHEN 'Alta' THEN 1
                        WHEN 'Media' THEN 2
                        WHEN 'Baja' THEN 3
                    END"
                );
            })
            ->when($orden === 'reciente', function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->when(
                ! in_array($orden, ['prioridad', 'reciente']),
                function ($query) {
                    $query->orderBy('fecha_limite');
                }
            )
            ->paginate(10)
            ->withQueryString();

        $totalTareas = Tarea::where(
            'user_id',
            Auth::id()
        )->count();

        $completadas = Tarea::where(
            'user_id',
            Auth::id()
        )
            ->where('estado', 'Completada')
            ->count();

        $etiquetas = Etiqueta::where(
            'user_id',
            Auth::id()
        )
            ->orderBy('nombre')
            ->get();

        return view(
            'tareas.index',
            compact(
                'tareas',
                'totalTareas',
                'completadas',
                'etiquetas'
            )
        );
    }

    public function create()
    {
        $etiquetas = Etiqueta::where(
            'user_id',
            Auth::id()
        )
            ->orderBy('nombre')
            ->get();

        return view(
            'tareas.create',
            compact('etiquetas')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            $this->reglasTarea(),
            $this->mensajesTarea()
        );

        $etiquetas = $validated['etiquetas'] ?? [];

        unset($validated['etiquetas']);

        $validated['user_id'] = Auth::id();

        $tarea = Tarea::create($validated);

        $tarea->etiquetas()->sync(
            $this->etiquetasDelUsuario($etiquetas)
        );

        return redirect()
            ->route('tareas.index')
            ->with(
                'success',
                'Tarea creada correctamente.'
            );
    }

    public function show(Tarea $tarea)
    {
        abort_if(
            $tarea->user_id !== Auth::id(),
            403
        );

        return view(
            'tareas.show',
            compact('tarea')
        );
    }

    public function edit(Tarea $tarea)
    {
        abort_if(
            $tarea->user_id !== Auth::id(),
            403
        );

        $etiquetas = Etiqueta::where(
            'user_id',
            Auth::id()
        )
            ->orderBy('nombre')
            ->get();

        return view(
            'tareas.edit',
            compact('tarea', 'etiquetas')
        );
    }

    public function update(
        Request $request,
        Tarea $tarea
    ) {
        abort_if(
            $tarea->user_id !== Auth::id(),
            403
        );

        $validated = $request->validate(
            $this->reglasTarea(),
            $this->mensajesTarea()
        );

        $etiquetas = $validated['etiquetas'] ?? [];

        unset($validated['etiquetas']);

        $tarea->update($validated);

        $tarea->etiquetas()->sync(
            $this->etiquetasDelUsuario($etiquetas)
        );

        return redirect()
            ->route('tareas.index')
            ->with(
                'success',
                'Tarea actualizada correctamente.'
            );
    }

    private function reglasTarea(): array
    {
        $hoyEcuador = now(
            'America/Guayaquil'
        )->toDateString();

        return [
            'titulo' => [
                'bail',
                'required',
                'string',
                'max:255',
            ],

            'descripcion' => [
                'nullable',
                'string',
            ],

            'estado' => [
                'required',
                'in:Pendiente,En progreso,Completada',
            ],

            'fecha_limite' => [
                'bail',
                'nullable',
                'date_format:Y-m-d',

                // Solo permite hoy o fechas futuras.
                'after_or_equal:' . $hoyEcuador,
            ],

            'prioridad' => [
                'required',
                'in:Baja,Media,Alta',
            ],

            'etiquetas' => [
                'nullable',
                'array',
            ],

            'etiquetas.*' => [
                'integer',
                'distinct',
            ],
        ];
    }

    private function mensajesTarea(): array
    {
        return [
            'titulo.required' =>
                'Debes escribir un título para la tarea.',

            'titulo.string' =>
                'El título debe contener texto válido.',

            'titulo.max' =>
                'El título no puede superar los 255 caracteres.',

            'descripcion.string' =>
                'La descripción debe contener texto válido.',

            'estado.required' =>
                'Debes seleccionar un estado.',

            'estado.in' =>
                'El estado seleccionado no es válido.',

            'fecha_limite.date_format' =>
                'Debes seleccionar una fecha válida.',

            'fecha_limite.after_or_equal' =>
                'La fecha límite no puede ser anterior al día de hoy.',

            'prioridad.required' =>
                'Debes seleccionar una prioridad.',

            'prioridad.in' =>
                'La prioridad seleccionada no es válida.',

            'etiquetas.array' =>
                'Las etiquetas seleccionadas no son válidas.',

            'etiquetas.*.integer' =>
                'Una de las etiquetas seleccionadas no es válida.',

            'etiquetas.*.distinct' =>
                'No puedes seleccionar dos veces la misma etiqueta.',
        ];
    }

    private function etiquetasDelUsuario(
        array $ids
    ): array {
        return Etiqueta::where(
            'user_id',
            Auth::id()
        )
            ->whereIn('id', $ids)
            ->pluck('id')
            ->all();
    }

    public function destroy(Tarea $tarea)
    {
        abort_if(
            $tarea->user_id !== Auth::id(),
            403
        );

        $tarea->delete();

        return redirect()
            ->route('tareas.index')
            ->with(
                'success',
                'Tarea enviada a la papelera correctamente.'
            );
    }

    public function papelera()
    {
        $tareas = Tarea::onlyTrashed()
            ->where('user_id', Auth::id())
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return view(
            'tareas.papelera',
            compact('tareas')
        );
    }

    public function restaurar(string $id)
    {
        $tarea = Tarea::onlyTrashed()
            ->findOrFail($id);

        abort_if(
            $tarea->user_id !== Auth::id(),
            403
        );

        $tarea->restore();

        return redirect()
            ->route('tareas.papelera')
            ->with(
                'success',
                'Tarea restaurada correctamente.'
            );
    }

    public function forzarEliminacion(string $id)
    {
        $tarea = Tarea::onlyTrashed()
            ->findOrFail($id);

        abort_if(
            $tarea->user_id !== Auth::id(),
            403
        );

        $tarea->forceDelete();

        return redirect()
            ->route('tareas.papelera')
            ->with(
                'success',
                'Tarea eliminada definitivamente.'
            );
    }
}