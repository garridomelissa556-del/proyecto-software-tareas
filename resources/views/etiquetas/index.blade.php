<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-brand font-mono uppercase tracking-wide">Organización</p>
                <h2 class="font-display font-semibold text-2xl text-ink">
                    Mis etiquetas
                </h2>
            </div>

            <a href="{{ route('etiquetas.create') }}"
               class="inline-flex items-center px-4 py-2 bg-brand hover:bg-brand-dark text-white font-semibold rounded-lg shadow transition">
                + Nueva etiqueta
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 bg-mint/10 border border-mint text-mint font-medium px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-3">

                @forelse($etiquetas as $etiqueta)

                    <div class="bg-white rounded-lg shadow-sm px-6 py-4 flex items-center justify-between hover:shadow-md transition">

                        <div class="flex items-center gap-3">
                            <span class="w-4 h-4 rounded-full inline-block" style="background-color: {{ $etiqueta->color }}"></span>
                            <p class="font-display font-semibold text-ink">{{ $etiqueta->nombre }}</p>
                            <span class="text-xs text-ink/50 font-mono">{{ $etiqueta->tareas_count }} tarea(s)</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('etiquetas.edit', $etiqueta) }}" class="text-ink/60 hover:text-ink font-medium text-sm">Editar</a>

                            <form action="{{ route('etiquetas.destroy', $etiqueta) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar esta etiqueta? Se quitará de todas las tareas.')">
                                @csrf
                                @method('DELETE')
                                <button class="text-coral hover:text-red-700 font-medium text-sm">Eliminar</button>
                            </form>
                        </div>
                    </div>

                @empty

                    <div class="bg-white rounded-lg shadow-sm px-6 py-12 text-center">
                        <p class="font-display text-lg text-ink">Aún no tienes etiquetas</p>
                        <p class="text-ink/60 mt-1">Créalas para organizar mejor tus tareas</p>
                    </div>

                @endforelse

            </div>

        </div>
    </div>

</x-app-layout>
