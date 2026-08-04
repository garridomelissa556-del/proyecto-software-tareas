<x-app-layout>
    <x-slot name="header">
        <p class="text-sm text-brand font-mono uppercase tracking-wide">Nueva</p>
        <h2 class="font-display font-semibold text-2xl text-ink">
            Crear etiqueta
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg p-8">

                <form action="{{ route('etiquetas.store') }}" method="POST">

                    @csrf

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-ink mb-2">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}"
                            class="w-full rounded-lg border-line focus:border-brand focus:ring-brand" required>
                        @error('nombre') <p class="text-coral text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-medium text-ink mb-2">Color</label>
                        <input type="color" name="color" value="{{ old('color', '#6366F1') }}"
                            class="w-16 h-10 rounded-lg border-line focus:border-brand focus:ring-brand">
                        @error('color') <p class="text-coral text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('etiquetas.index') }}" class="px-4 py-2 text-ink/60 hover:text-ink font-medium">Cancelar</a>
                        <button type="submit" class="px-5 py-2 bg-brand hover:bg-brand-dark text-white font-semibold rounded-lg shadow">Guardar</button>
                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>
