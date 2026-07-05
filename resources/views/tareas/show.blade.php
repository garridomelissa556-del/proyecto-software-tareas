<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-brand font-mono uppercase tracking-wide">Detalle</p>
                <h2 class="font-display font-semibold text-2xl text-ink">
                    {{ $tarea->titulo }}
                </h2>
            </div>

            <a href="{{ route('tareas.index') }}" class="text-ink/60 hover:text-ink font-medium text-sm">
                ← Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg p-8 space-y-6">

                <div>
                    <h3 class="text-xs font-mono uppercase tracking-wide text-ink/50">Descripción</h3>
                    <p class="mt-2 text-ink">{{ $tarea->descripcion ?: 'Sin descripción.' }}</p>
                </div>

                <div class="flex gap-10">
                    <div>
                        <h3 class="text-xs font-mono uppercase tracking-wide text-ink/50">Estado</h3>
                        <div class="mt-2">
                            @if($tarea->estado == 'Pendiente')
                                <span class="bg-amber/10 text-amber px-3 py-1 rounded-full text-sm font-medium">Pendiente</span>
                            @elseif($tarea->estado == 'En progreso')
                                <span class="bg-brand-light text-brand px-3 py-1 rounded-full text-sm font-medium">En progreso</span>
                            @else
                                <span class="bg-mint/10 text-mint px-3 py-1 rounded-full text-sm font-medium">Completada</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-mono uppercase tracking-wide text-ink/50">Prioridad</h3>
                        <div class="mt-2">
                            @if($tarea->prioridad == 'Alta')
                                <span class="bg-coral/10 text-coral px-3 py-1 rounded-full text-sm font-medium">Alta</span>
                            @elseif($tarea->prioridad == 'Media')
                                <span class="bg-amber/10 text-amber px-3 py-1 rounded-full text-sm font-medium">Media</span>
                            @else
                                <span class="bg-mint/10 text-mint px-3 py-1 rounded-full text-sm font-medium">Baja</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-mono uppercase tracking-wide text-ink/50">Fecha límite</h3>
                        <p class="mt-2 font-mono text-ink">
                            {{ $tarea->fecha_limite ? $tarea->fecha_limite->format('d/m/Y') : 'Sin fecha' }}
                        </p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-line">
                    <a href="{{ route('tareas.edit', $tarea) }}" class="px-4 py-2 bg-amber hover:brightness-95 text-white font-semibold rounded-lg">Editar</a>

                    <form action="{{ route('tareas.destroy', $tarea) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar esta tarea?')">
                        @csrf
                        @method('DELETE')
                        <button class="px-4 py-2 bg-coral hover:brightness-95 text-white font-semibold rounded-lg">Eliminar</button>
                    </form>
                </div>

            </div>

        </div>
    </div>

</x-app-layout>