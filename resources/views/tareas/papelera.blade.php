<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-brand font-mono uppercase tracking-wide">Papelera</p>
                <h2 class="font-display font-semibold text-2xl text-ink">
                    Tareas eliminadas
                </h2>
            </div>

            <a href="{{ route('tareas.index') }}"
               class="inline-flex items-center px-4 py-2 bg-brand hover:bg-brand-dark text-white font-semibold rounded-lg shadow transition">
                Volver a mis tareas
            </a>
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

                    <div class="bg-white rounded-lg shadow-sm border-l-4 border-line px-6 py-4 flex items-center justify-between">

                        <div>
                            <p class="font-display font-semibold text-ink">{{ $tarea->titulo }}</p>
                            <p class="text-sm text-ink/60 font-mono mt-1">
                                Eliminada el {{ $tarea->deleted_at->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        <div class="flex items-center gap-3">

                            <form action="{{ route('tareas.restaurar', $tarea->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="text-brand hover:text-brand-dark font-medium text-sm">Restaurar</button>
                            </form>

                            <form action="{{ route('tareas.forzar', $tarea->id) }}" method="POST"
                                  onsubmit="return confirm('Esta acción eliminará la tarea de forma definitiva. ¿Continuar?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-coral hover:text-red-700 font-medium text-sm">Eliminar definitivamente</button>
                            </form>

                        </div>
                    </div>

                @empty

                    <div class="bg-white rounded-lg shadow-sm px-6 py-12 text-center">
                        <p class="font-display text-lg text-ink">La papelera está vacía</p>
                        <p class="text-ink/60 mt-1">Las tareas que elimines aparecerán aquí</p>
                    </div>

                @endforelse

            </div>

            <div class="mt-6">
                {{ $tareas->links() }}
            </div>

        </div>
    </div>

</x-app-layout>
