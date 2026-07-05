<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            TaskMaster - Sistema de Gestión de Tareas Académicas
        </h2>
    </x-slot>

    <div class="container mt-4">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">

            <div class="col-md-4 mb-3">
                <div class="card border-primary shadow">
                    <div class="card-body text-center">
                        <h5>Total de tareas</h5>
                        <h2>{{ $total }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card border-warning shadow">
                    <div class="card-body text-center">
                        <h5>Pendientes</h5>
                        <h2>{{ $pendientes }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card border-success shadow">
                    <div class="card-body text-center">
                        <h5>Completadas</h5>
                        <h2>{{ $completadas }}</h2>
                    </div>
                </div>
            </div>

        </div>

        <div class="mb-3">

            <a href="{{ route('tareas.create') }}" class="btn btn-primary">
                Nueva tarea
            </a>

            <a href="{{ route('tareas.index') }}" class="btn btn-secondary">
                Ver todas las tareas
            </a>

        </div>

        <div class="card shadow">

            <div class="card-header">

                Últimas tareas

            </div>

            <div class="card-body">

                @if($tareas->count())

                <table class="table table-hover">

                    <thead>

                        <tr>

                            <th>Título</th>

                            <th>Prioridad</th>

                            <th>Estado</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($tareas as $tarea)

                        <tr>

                            <td>{{ $tarea->titulo }}</td>

                            <td>{{ $tarea->prioridad }}</td>

                            <td>

                                @if($tarea->completada)

                                    <span class="badge bg-success">
                                        Completada
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Pendiente
                                    </span>

                                @endif

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

                @else

                    <div class="alert alert-info">

                        No existen tareas registradas.

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>