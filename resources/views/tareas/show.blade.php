<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800">
                Detalle de la Tarea
            </h2>

            <a href="{{ route('tareas.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-8">

                <!-- Título -->
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-700">
                        Título
                    </h3>

                    <p class="mt-2 text-gray-900">
                        {{ $tarea->titulo }}
                    </p>
                </div>

                <!-- Descripción -->
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-700">
                        Descripción
                    </h3>

                    <p class="mt-2 text-gray-900">
                        {{ $tarea->descripcion ?: 'Sin descripción.' }}
                    </p>
                </div>

                <!-- Estado -->
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-700">
                        Estado
                    </h3>

                    <div class="mt-2">

                        @if($tarea->estado == 'Pendiente')

                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full">
                                Pendiente
                            </span>

                        @elseif($tarea->estado == 'En progreso')

                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                                En progreso
                            </span>

                        @else

                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full">
                                Completada
                            </span>

                        @endif

                    </div>
                </div>

                <!-- Prioridad -->
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-700">
                        Prioridad
                    </h3>

                    <div class="mt-2">

                        @if($tarea->prioridad == 'Alta')

                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full">
                                Alta
                            </span>

                        @elseif($tarea->prioridad == 'Media')

                            <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full">
                                Media
                            </span>

                        @else

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                Baja
                            </span>

                        @endif

                    </div>
                </div>

                <!-- Fecha límite -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-700">
                        Fecha límite
                    </h3>

                    <p class="mt-2 text-gray-900">
                        {{ $tarea->fecha_limite ? $tarea->fecha_limite->format('d/m/Y') : 'Sin fecha límite' }}
                    </p>
                </div>

                <!-- Acciones -->
                <div class="flex justify-end gap-3">

                    <a href="{{ route('tareas.edit', $tarea) }}"
                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">
                        Editar
                    </a>

                    <form action="{{ route('tareas.destroy', $tarea) }}"
                          method="POST"
                          onsubmit="return confirm('¿Deseas eliminar esta tarea?')">

                        @csrf
                        @method('DELETE')

                        <button
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                            Eliminar
                        </button>

                    </form>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>