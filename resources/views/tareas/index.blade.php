<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-brand font-mono uppercase tracking-wide">Panel</p>
                <h2 class="font-display font-semibold text-2xl text-ink">
                    Mis tareas
                </h2>
            </div>

            <div class="flex items-center gap-4">
                @php
                    $porcentaje = $totalTareas > 0 ? round(($completadas / $totalTareas) * 100) : 0;
                @endphp

                <div class="progress-ring" style="--percent: {{ $porcentaje }}">
                    <span>{{ $completadas }}/{{ $totalTareas }}</span>
                </div>

                <a href="{{ route('tareas.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-brand hover:bg-brand-dark text-white font-semibold rounded-lg shadow transition">
                    + Nueva tarea
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 bg-mint/10 border border-mint text-mint font-medium px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-3">

                @forelse($tareas as $tarea)

                    @if($tarea->prioridad == 'Alta')
                        @php($borde = 'border-l-4 border-coral')
                    @elseif($tarea->prioridad == 'Media')
                        @php($borde = 'border-l-4 border-amber')
                    @else
                        @php($borde = 'border-l-4 border-mint')
                    @endif

                    <div class="bg-white rounded-lg shadow-sm {{ $borde }} px-6 py-4 flex items-center justify-between hover:shadow-md transition">

                        <div>
                            <p class="font-display font-semibold text-ink">{{ $tarea->titulo }}</p>
                            <p class="text-sm text-ink/60 font-mono mt-1">
                                {{ $tarea->fecha_limite ? $tarea->fecha_limite->format('d/m/Y') : 'Sin fecha' }}
                            </p>
                        </div>

                        <div class="flex items-center gap-3">

                            @if($tarea->estado == 'Pendiente')
                                <span class="bg-amber/10 text-amber px-3 py-1 rounded-full text-sm font-medium">Pendiente</span>
                            @elseif($tarea->estado == 'En progreso')
                                <span class="bg-brand-light text-brand px-3 py-1 rounded-full text-sm font-medium">En progreso</span>
                            @else
                                <span class="bg-mint/10 text-mint px-3 py-1 rounded-full text-sm font-medium">Completada</span>
                            @endif

                            <a href="{{ route('tareas.show', $tarea) }}" class="text-brand hover:text-brand-dark font-medium text-sm">Ver</a>
                            <a href="{{ route('tareas.edit', $tarea) }}" class="text-ink/60 hover:text-ink font-medium text-sm">Editar</a>

                            <form action="{{ route('tareas.destroy', $tarea) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar esta tarea?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-coral hover:text-red-700 font-medium text-sm">Eliminar</button>
                            </form>

                        </div>
                    </div>

                @empty

                    <div class="bg-white rounded-lg shadow-sm px-6 py-12 text-center">
                        <p class="font-display text-lg text-ink">Aún no tienes tareas</p>
                        <p class="text-ink/60 mt-1">Crea la primera y empieza a tachar pendientes ✓</p>
                    </div>

                @endforelse

            </div>

            <div class="mt-6">
                {{ $tareas->links() }}
            </div>

        </div>
    </div>

</x-app-layout>