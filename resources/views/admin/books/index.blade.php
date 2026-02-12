@extends('layouts.app')

@section('content')
    <div class="table-content">

        <div class="table-menu">
            {{-- FILTROS (GET) --}}
            @include('admin.books.partials.filter')

            {{-- PAGINACIÓN (simple) --}}
            <div>
                {{ $records->links() }}
            </div>
        </div>

        <div class="table-content">
            @forelse ($records as $record)
                @include('admin.books.partials.item', ['record' => $record])
            @empty
                <p>No hay libros con esos filtros.</p>
            @endforelse
        </div>
    </div>

    {{-- Panel derecho: ahora lo adapto a BOOK --}}
    <div class="main-content">
        <form method="POST" action="{{ route('admin.books.store') }}">
            @csrf

            <div class="form-options">
                <div class="tabs">
                    <button type="button">GENERAL</button>
                </div>

                <div class="buttons">
                    {{-- Crear libro --}}
                    <button type="submit" title="Guardar">
                        <x-icon.content-save />
                    </button>

                    {{-- Limpiar formulario --}}
                    <button type="reset" title="Limpiar">
                        <x-icon.broom />

                    </button> {{-- Eliminar libro --}}
                    <button type="button" title="Eliminar" class="delete-btn" style="display: none;">
                        <x-icon.delete />
                    </button>

                </div>
            </div>

            <div class="form-fields">
                <div>
                    <label for="title">Título</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                        placeholder="El imperio del vampiro">
                    @error('title')
                        <div style="color:red;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="isbn">ISBN</label>
                    <input type="text" name="isbn" id="isbn" value="{{ old('isbn') }}" placeholder="978...">
                    @error('isbn')
                        <div style="color:red;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="description">Sinopsis</label>
                    <textarea name="description" id="description" placeholder="...">{{ old('description') }}</textarea>
                    @error('description')
                        <div style="color:red;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <style>
        .edit-tab {
            cursor: pointer;
        }

        .edit-tab.selected {
            border: 2px solid blue;
        }
    </style>
@endpush
