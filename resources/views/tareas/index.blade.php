<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-brand font-mono uppercase tracking-wide">
                    Panel
                </p>

                <h2 class="font-display font-semibold text-2xl text-ink">
                    Mis tareas
                </h2>
            </div>

            <div class="flex items-center gap-4">
                @php
                    $porcentaje = $totalTareas > 0
                        ? round(($completadas / $totalTareas) * 100)
                        : 0;
                @endphp

                <div class="progress-ring" style="--percent: {{ $porcentaje }}">
                    <span>{{ $completadas }}/{{ $totalTareas }}</span>
                </div>

                <a
                    href="{{ route('tareas.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-brand hover:bg-brand-dark text-white font-semibold rounded-lg shadow transition"
                >
                    + Nueva tarea
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-mint/10 border border-mint text-mint font-medium px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <form
                method="GET"
                action="{{ route('tareas.index') }}"
                class="bg-white rounded-lg shadow-sm p-4 mb-4 flex flex-wrap items-end gap-3"
            >
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs font-medium text-ink/60 mb-1">
                        Buscar
                    </label>

                    <input
                        type="text"
                        name="buscar"
                        value="{{ request('buscar') }}"
                        placeholder="Título o descripción..."
                        class="w-full rounded-lg border-line focus:border-brand focus:ring-brand text-sm"
                    >
                </div>

                <div>
                    <label class="block text-xs font-medium text-ink/60 mb-1">
                        Estado
                    </label>

                    <select
                        name="estado"
                        class="rounded-lg border-line focus:border-brand focus:ring-brand text-sm"
                    >
                        <option value="">Todos</option>

                        <option
                            value="Pendiente"
                            {{ request('estado') == 'Pendiente' ? 'selected' : '' }}
                        >
                            Pendiente
                        </option>

                        <option
                            value="En progreso"
                            {{ request('estado') == 'En progreso' ? 'selected' : '' }}
                        >
                            En progreso
                        </option>

                        <option
                            value="Completada"
                            {{ request('estado') == 'Completada' ? 'selected' : '' }}
                        >
                            Completada
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-ink/60 mb-1">
                        Prioridad
                    </label>

                    <select
                        name="prioridad"
                        class="rounded-lg border-line focus:border-brand focus:ring-brand text-sm"
                    >
                        <option value="">Todas</option>

                        <option
                            value="Alta"
                            {{ request('prioridad') == 'Alta' ? 'selected' : '' }}
                        >
                            Alta
                        </option>

                        <option
                            value="Media"
                            {{ request('prioridad') == 'Media' ? 'selected' : '' }}
                        >
                            Media
                        </option>

                        <option
                            value="Baja"
                            {{ request('prioridad') == 'Baja' ? 'selected' : '' }}
                        >
                            Baja
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-ink/60 mb-1">
                        Etiqueta
                    </label>

                    <select
                        name="etiqueta"
                        class="rounded-lg border-line focus:border-brand focus:ring-brand text-sm"
                    >
                        <option value="">Todas</option>

                        @foreach ($etiquetas as $etiqueta)
                            <option
                                value="{{ $etiqueta->id }}"
                                {{ request('etiqueta') == $etiqueta->id ? 'selected' : '' }}
                            >
                                {{ $etiqueta->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-ink/60 mb-1">
                        Ordenar por
                    </label>

                    <select
                        name="orden"
                        class="rounded-lg border-line focus:border-brand focus:ring-brand text-sm"
                    >
                        <option
                            value="fecha_limite"
                            {{ request('orden', 'fecha_limite') == 'fecha_limite' ? 'selected' : '' }}
                        >
                            Fecha límite
                        </option>

                        <option
                            value="prioridad"
                            {{ request('orden') == 'prioridad' ? 'selected' : '' }}
                        >
                            Prioridad
                        </option>

                        <option
                            value="reciente"
                            {{ request('orden') == 'reciente' ? 'selected' : '' }}
                        >
                            Más reciente
                        </option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="px-4 py-2 bg-brand hover:bg-brand-dark text-white font-semibold rounded-lg shadow text-sm"
                    >
                        Filtrar
                    </button>

                    @if (
                        request()->anyFilled([
                            'buscar',
                            'estado',
                            'prioridad',
                            'etiqueta'
                        ]) || request('orden')
                    )
                        <a
                            href="{{ route('tareas.index') }}"
                            class="px-4 py-2 text-ink/60 hover:text-ink font-medium text-sm"
                        >
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>

            <div class="space-y-3">
                @forelse ($tareas as $tarea)

                    @if ($tarea->prioridad == 'Alta')
                        @php($borde = 'border-l-4 border-coral')
                    @elseif ($tarea->prioridad == 'Media')
                        @php($borde = 'border-l-4 border-amber')
                    @else
                        @php($borde = 'border-l-4 border-mint')
                    @endif

                    <div class="bg-white rounded-lg shadow-sm {{ $borde }} px-6 py-4 flex items-center justify-between hover:shadow-md transition">
                        <div>
                            <p class="font-display font-semibold text-ink">
                                {{ $tarea->titulo }}
                            </p>

                            <p class="text-sm text-ink/60 font-mono mt-1">
                                {{ $tarea->fecha_limite
                                    ? $tarea->fecha_limite->format('d/m/Y')
                                    : 'Sin fecha' }}
                            </p>

                            @if ($tarea->etiquetas->isNotEmpty())
                                <div class="flex flex-wrap gap-1.5 mt-2">
                                    @foreach ($tarea->etiquetas as $etiqueta)
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium"
                                            style="background-color: {{ $etiqueta->color }}1a; color: {{ $etiqueta->color }}"
                                        >
                                            {{ $etiqueta->nombre }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-3">
                            @if ($tarea->estado == 'Pendiente')
                                <span class="bg-amber/10 text-amber px-3 py-1 rounded-full text-sm font-medium">
                                    Pendiente
                                </span>
                            @elseif ($tarea->estado == 'En progreso')
                                <span class="bg-brand-light text-brand px-3 py-1 rounded-full text-sm font-medium">
                                    En progreso
                                </span>
                            @else
                                <span class="bg-mint/10 text-mint px-3 py-1 rounded-full text-sm font-medium">
                                    Completada
                                </span>
                            @endif

                            <a
                                href="{{ route('tareas.show', $tarea) }}"
                                class="text-brand hover:text-brand-dark font-medium text-sm"
                            >
                                Ver
                            </a>

                            <a
                                href="{{ route('tareas.edit', $tarea) }}"
                                class="text-ink/60 hover:text-ink font-medium text-sm"
                            >
                                Editar
                            </a>

                            <form
                                action="{{ route('tareas.destroy', $tarea) }}"
                                method="POST"
                                class="form-eliminar"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="text-coral hover:text-red-700 font-medium text-sm"
                                >
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>

                @empty

                    <div class="bg-white rounded-lg shadow-sm px-6 py-12 text-center">
                        @if (
                            request()->anyFilled([
                                'buscar',
                                'estado',
                                'prioridad',
                                'etiqueta'
                            ])
                        )
                            <p class="font-display text-lg text-ink">
                                Ninguna tarea coincide con esos filtros
                            </p>

                            <p class="text-ink/60 mt-1">
                                Prueba con otros criterios o límpialos.
                            </p>
                        @else
                            <p class="font-display text-lg text-ink">
                                Aún no tienes tareas
                            </p>

                            <p class="text-ink/60 mt-1">
                                Crea la primera y empieza a tachar pendientes ✓
                            </p>
                        @endif
                    </div>

                @endforelse
            </div>

            <div class="mt-6">
                {{ $tareas->links() }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.form-eliminar').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    Swal.fire({
                        title: '¿Mover esta tarea a la papelera?',
                        text: 'Podrás restaurarla posteriormente desde la papelera.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Sí, mover',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true,
                        allowOutsideClick: false,
                        allowEscapeKey: true
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>