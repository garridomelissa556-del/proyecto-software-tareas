<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Mis Tareas
            </h2>

            <a href="{{ route('tareas.create') }}"
class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow transition"
            + Nueva tarea
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-lg overflow-hidden">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-slate-100">
                        <tr>

                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
                                Título
                            </th>

                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
                                Estado
                            </th>

                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
                                Prioridad
                            </th>

                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
                                Fecha límite
                            </th>

                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">
                                Acciones
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">

                        @forelse($tareas as $tarea)

                            <tr class="hover:bg-slate-50">

                                <td class="px-6 py-4">
                                    {{ $tarea->titulo }}
                                </td>

                                <td class="px-6 py-4">

                                    @if($tarea->estado == 'Pendiente')

                                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">
                                            Pendiente
                                        </span>

                                    @elseif($tarea->estado == 'En progreso')

                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                            En progreso
                                        </span>

                                    @else

                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                                            Completada
                                        </span>

                                    @endif

                                </td>

                                <td class="px-6 py-4">

                                    @if($tarea->prioridad == 'Alta')

                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                                            Alta
                                        </span>

                                    @elseif($tarea->prioridad == 'Media')

                                        <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-sm">
                                            Media
                                        </span>

                                    @else

                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                            Baja
                                        </span>

                                    @endif

                                </td>

                                <td class="px-6 py-4">
                                  {{ $tarea->fecha_limite ? $tarea->fecha_limite->format('d/m/Y') : 'Sin fecha' }}
                                </td>

                                <td class="px-6 py-4">

                                    <div class="flex justify-center gap-2">

                                        <a href="{{ route('tareas.show', $tarea) }}"
                                           class="bg-sky-600 hover:bg-sky-700 text-white px-3 py-1 rounded">
                                            Ver
                                        </a>

                                        <a href="{{ route('tareas.edit', $tarea) }}"
                                           class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1 rounded">
                                            Editar
                                        </a>

                                        <form action="{{ route('tareas.destroy', $tarea) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Deseas eliminar esta tarea?')">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                                Eliminar
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="text-center py-8 text-gray-500">

                                    No hay tareas registradas.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-6">
                {{ $tareas->links() }}
            </div>

        </div>
    </div>

</x-app-layout>