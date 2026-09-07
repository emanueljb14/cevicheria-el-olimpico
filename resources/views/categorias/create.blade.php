@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 760px;">
    <!-- Encabezado Estilo El Olímpico -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                 style="width: 48px; height: 48px; background-color: #f2a922; color: #0d1b1e;">
                <i class="bi bi-folder-plus fs-5 fw-bold"></i>
            </div>
            <div>
                <span class="badge rounded-pill text-uppercase tracking-wider px-3 py-1 mb-1" 
                      style="background-color: #fff8e7; color: #d99100; border: 1px solid #fce8b3; font-size: 0.68rem; font-weight: 700;">
                    Gestión de Carta
                </span>
                <h4 class="fw-bold mb-0 text-dark" style="font-family: 'Playfair Display', Georgia, serif;">Nueva Categoría</h4>
            </div>
        </div>
        <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary rounded-pill px-3 fs-7 fw-medium">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <!-- Card Estilo Claro Minimalista -->
    <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5" style="background-color: #ffffff; border: 1px solid #eaeaea !important;">
        <form action="{{ route('categorias.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="nombre" class="form-label text-uppercase text-secondary fw-bold fs-7 tracking-wider mb-2">
                    Nombre de la Categoría <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text border-0 text-secondary" style="background-color: #f8f9fa;">
                        <i class="bi bi-tag-fill" style="color: #d99100;"></i>
                    </span>
                    <input type="text" name="nombre" id="nombre" 
                           class="form-control border-0 text-dark p-3 fs-6 @error('nombre') is-invalid @enderror" 
                           style="background-color: #f8f9fa;" 
                           value="{{ old('nombre') }}" placeholder="Ej. Ceviches, Jaleas, Bebidas" required autofocus>
                </div>
                @error('nombre')
                    <div class="text-danger small mt-2 d-flex align-items-center"><i class="bi bi-exclamation-circle me-1"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="descripcion" class="form-label text-uppercase text-secondary fw-bold fs-7 tracking-wider mb-2">
                    Descripción Breve
                </label>
                <textarea name="descripcion" id="descripcion" rows="3" 
                          class="form-control border-0 text-dark p-3 fs-6 @error('descripcion') is-invalid @enderror" 
                          style="background-color: #f8f9fa;" 
                          placeholder="Escribe una breve reseña del tipo de platillos...">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <div class="text-danger small mt-2 d-flex align-items-center"><i class="bi bi-exclamation-circle me-1"></i> {{ $message }}</div>
                @enderror
            </div>

            <!-- Footer con Botón Dorado de la Web -->
            <div class="d-flex align-items-center justify-content-end gap-3 pt-4 mt-4 border-top" style="border-color: #f0f0f0 !important;">
                <a href="{{ route('categorias.index') }}" class="btn btn-link text-decoration-none text-muted fw-semibold px-3">
                    Cancelar
                </a>
                <button type="submit" class="btn rounded-pill px-4 py-2-5 fw-bold shadow-sm text-dark" 
                        style="background-color: #f2a922; border: none;">
                    <i class="bi bi-check2-circle me-1"></i> Guardar Categoría
                </button>
            </div>
        </form>
    </div>
</div>
@endsection