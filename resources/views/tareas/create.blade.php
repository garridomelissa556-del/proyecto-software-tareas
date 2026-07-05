<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800">
            Nueva Tarea
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-6">

                <form action="{{ route('tareas.store') }}" method="POST">

                    @csrf

                    <!-- Título -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Título
                        </label>

                        <input
                            type="text"
                            name="titulo"
                            value="{{ old('titulo') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            required>

                        @error('titulo')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción
                        </label>

                        <textarea
                            name="descripcion"
                            rows="4"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('descripcion') }}</textarea>

                        @error('descripcion')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Estado
                        </label>

                        <select
                            name="estado"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                            <option value="Pendiente"
                                {{ old('estado') == 'Pendiente' ? 'selected' : '' }}>
                                Pendiente
                            </option>

                            <option value="En progreso"
                                {{ old('estado') == 'En progreso' ? 'selected' : '' }}>
                                En progreso
                            </option>

                            <option value="Completada"
                                {{ old('estado') == 'Completada' ? 'selected' : '' }}>
                                Completada
                            </option>

                        </select>

                        @error('estado')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prioridad -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Prioridad
                        </label>

                        <select
                            name="prioridad"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                            <option value="Baja"
                                {{ old('prioridad') == 'Baja' ? 'selected' : '' }}>
                                Baja
                            </option>

                            <option value="Media"
                                {{ old('prioridad') == 'Media' ? 'selected' : '' }}>
                                Media
                            </option>

                            <option value="Alta"
                                {{ old('prioridad') == 'Alta' ? 'selected' : '' }}>
                                Alta
                            </option>

                        </select>

                        @error('prioridad')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha límite -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha límite
                        </label>

                        <input
                            type="date"
                            name="fecha_limite"
                            value="{{ old('fecha_limite') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                        @error('fecha_limite')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-3">

                        <a href="{{ route('tareas.index') }}"
                           class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                            Cancelar
                        </a>

                        <button
                            type="submit"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">
                            Guardar
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>