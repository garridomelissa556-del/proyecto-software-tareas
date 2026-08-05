<x-app-layout>
    <x-slot name="header">
        <p class="text-sm text-brand font-mono uppercase tracking-wide">
            Editar
        </p>

        <h2 class="font-display font-semibold text-2xl text-ink">
            {{ $tarea->titulo }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg p-8">

                <form
                    action="{{ route('tareas.update', $tarea) }}"
                    method="POST"
                    novalidate
                >
                    @csrf
                    @method('PUT')

                    <div class="mb-5">
                        <label
                            for="titulo"
                            class="block text-sm font-medium text-ink mb-2"
                        >
                            Título
                        </label>

                        <input
                            id="titulo"
                            type="text"
                            name="titulo"
                            value="{{ old('titulo', $tarea->titulo) }}"
                            maxlength="255"
                            class="w-full rounded-lg border-line focus:border-brand focus:ring-brand"
                        >

                        @error('titulo')
                            <p class="text-coral text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label
                            for="descripcion"
                            class="block text-sm font-medium text-ink mb-2"
                        >
                            Descripción
                        </label>

                        <textarea
                            id="descripcion"
                            name="descripcion"
                            rows="4"
                            class="w-full rounded-lg border-line focus:border-brand focus:ring-brand"
                        >{{ old('descripcion', $tarea->descripcion) }}</textarea>

                        @error('descripcion')
                            <p class="text-coral text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                        <div>
                            <label
                                for="estado"
                                class="block text-sm font-medium text-ink mb-2"
                            >
                                Estado
                            </label>

                            <select
                                id="estado"
                                name="estado"
                                class="w-full rounded-lg border-line focus:border-brand focus:ring-brand"
                            >
                                <option
                                    value="Pendiente"
                                    {{ old('estado', $tarea->estado) == 'Pendiente' ? 'selected' : '' }}
                                >
                                    Pendiente
                                </option>

                                <option
                                    value="En progreso"
                                    {{ old('estado', $tarea->estado) == 'En progreso' ? 'selected' : '' }}
                                >
                                    En progreso
                                </option>

                                <option
                                    value="Completada"
                                    {{ old('estado', $tarea->estado) == 'Completada' ? 'selected' : '' }}
                                >
                                    Completada
                                </option>
                            </select>

                            @error('estado')
                                <p class="text-coral text-sm mt-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label
                                for="prioridad"
                                class="block text-sm font-medium text-ink mb-2"
                            >
                                Prioridad
                            </label>

                            <select
                                id="prioridad"
                                name="prioridad"
                                class="w-full rounded-lg border-line focus:border-brand focus:ring-brand"
                            >
                                <option
                                    value="Baja"
                                    {{ old('prioridad', $tarea->prioridad) == 'Baja' ? 'selected' : '' }}
                                >
                                    Baja
                                </option>

                                <option
                                    value="Media"
                                    {{ old('prioridad', $tarea->prioridad) == 'Media' ? 'selected' : '' }}
                                >
                                    Media
                                </option>

                                <option
                                    value="Alta"
                                    {{ old('prioridad', $tarea->prioridad) == 'Alta' ? 'selected' : '' }}
                                >
                                    Alta
                                </option>
                            </select>

                            @error('prioridad')
                                <p class="text-coral text-sm mt-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-8">
                        <label
                            for="fecha_limite"
                            class="block text-sm font-medium text-ink mb-2"
                        >
                            Fecha límite
                        </label>

                        <input
                            id="fecha_limite"
                            type="date"
                            name="fecha_limite"
                            value="{{ old(
                                'fecha_limite',
                                optional($tarea->fecha_limite)->format('Y-m-d')
                            ) }}"
                            min="{{ now('America/Guayaquil')->toDateString() }}"
                            class="w-full rounded-lg border-line focus:border-brand focus:ring-brand"
                        >

                        <p class="text-xs text-ink/50 mt-2">
                            Solo puedes escoger hoy o una fecha futura.
                        </p>

                        @error('fecha_limite')
                            <p class="text-coral text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    @php
                        $etiquetasSeleccionadas = old(
                            'etiquetas',
                            $tarea->etiquetas->pluck('id')->all()
                        );
                    @endphp

                    <div class="mb-8">
                        <label class="block text-sm font-medium text-ink mb-2">
                            Etiquetas
                        </label>

                        @if ($etiquetas->isEmpty())
                            <p class="text-sm text-ink/60">
                                No tienes etiquetas todavía.

                                <a
                                    href="{{ route('etiquetas.create') }}"
                                    class="text-brand hover:text-brand-dark font-medium"
                                >
                                    Crea una
                                </a>.
                            </p>
                        @else
                            <div class="flex flex-wrap gap-3">
                                @foreach ($etiquetas as $etiqueta)
                                    <label class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-line cursor-pointer">
                                        <input
                                            type="checkbox"
                                            name="etiquetas[]"
                                            value="{{ $etiqueta->id }}"
                                            {{ collect($etiquetasSeleccionadas)->contains($etiqueta->id) ? 'checked' : '' }}
                                        >

                                        <span
                                            class="w-2.5 h-2.5 rounded-full inline-block"
                                            style="background-color: {{ $etiqueta->color }}"
                                        ></span>

                                        <span class="text-sm text-ink">
                                            {{ $etiqueta->nombre }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        @endif

                        @error('etiquetas')
                            <p class="text-coral text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                        @error('etiquetas.*')
                            <p class="text-coral text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <a
                            href="{{ route('tareas.index') }}"
                            class="px-4 py-2 text-ink/60 hover:text-ink font-medium"
                        >
                            Cancelar
                        </a>

                        <button
                            type="submit"
                            class="px-5 py-2 bg-amber hover:brightness-95 text-white font-semibold rounded-lg shadow"
                        >
                            Actualizar
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>