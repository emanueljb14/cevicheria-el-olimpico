@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 900px;">
    <!-- Encabezado Estilo El Olímpico -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                 style="width: 48px; height: 48px; background-color: #f2a922; color: #0d1b1e;">
                <i class="bi bi-box-seam-fill fs-5 fw-bold"></i>
            </div>
            <div>
                <span class="badge rounded-pill text-uppercase tracking-wider px-3 py-1 mb-1" 
                      style="background-color: #fff8e7; color: #d99100; border: 1px solid #fce8b3; font-size: 0.68rem; font-weight: 700;">
                    Nuestra Carta
                </span>
                <h4 class="fw-bold mb-0 text-dark" style="font-family: 'Playfair Display', Georgia, serif;">Nuevo Platillo o Bebida</h4>
            </div>
        </div>
        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary rounded-pill px-3 fs-7 fw-medium">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <!-- Card Estilo Claro Minimalista -->
    <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5" style="background-color: #ffffff; border: 1px solid #eaeaea !important;">
        <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                <!-- Nombre del Producto -->
                <div class="col-md-7">
                    <label for="nombre" class="form-label text-uppercase text-secondary fw-bold fs-7 mb-2">Nombre del Platillo <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" id="nombre" class="form-control border-0 text-dark p-3 @error('nombre') is-invalid @enderror" style="background-color: #f8f9fa;" value="{{ old('nombre') }}" placeholder="Ej. Ceviche Carretillero, Jalea Mixta..." required autofocus>
                    @error('nombre')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Categoría -->
                <div class="col-md-5">
                    <label for="categoria_id" class="form-label text-uppercase text-secondary fw-bold fs-7 mb-2">Categoría <span class="text-danger">*</span></label>
                    <select name="categoria_id" id="categoria_id" class="form-select border-0 text-dark p-3 @error('categoria_id') is-invalid @enderror" style="background-color: #f8f9fa;" required>
                        <option value="" disabled selected>Seleccionar...</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Precio -->
                <div class="col-md-6">
                    <label for="precio" class="form-label text-uppercase text-secondary fw-bold fs-7 mb-2">Precio de Venta <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text border-0 fw-bold" style="background-color: #f8f9fa; color: #d99100;">S/</span>
                        <input type="number" step="0.01" min="0" name="precio" id="precio" class="form-control border-0 text-dark p-3 @error('precio') is-invalid @enderror" style="background-color: #f8f9fa;" value="{{ old('precio') }}" placeholder="0.00" required>
                    </div>
                    @error('precio')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Stock -->
                <div class="col-md-6">
                    <label for="stock" class="form-label text-uppercase text-secondary fw-bold fs-7 mb-2">Porciones / Stock Disponible</label>
                    <input type="number" name="stock" id="stock" class="form-control border-0 text-dark p-3 @error('stock') is-invalid @enderror" style="background-color: #f8f9fa;" value="{{ old('stock', 0) }}" min="0">
                    @error('stock')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="col-12">
                    <label for="descripcion" class="form-label text-uppercase text-secondary fw-bold fs-7 mb-2">Ingredientes / Presentación del Plato</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="form-control border-0 text-dark p-3" style="background-color: #f8f9fa;" placeholder="Ej. Pescado fresco del día, limón piurano, camote glaseado y choclo desgranado...">{{ old('descripcion') }}</textarea>
                </div>

                <!-- Estado -->
                <div class="col-12">
                    <div class="p-3 rounded-3 d-flex align-items-center justify-content-between" style="background-color: #f8f9fa;">
                        <div>
                            <h6 class="mb-0 text-dark fw-bold">Disponible para Pedidos</h6>
                            <small class="text-secondary">Si se desactiva, no figurará en la carta ni en los pedidos al momento.</small>
                        </div>
                        <div class="form-check form-switch fs-4 mb-0">
                            <input class="form-check-input" type="checkbox" role="switch" id="estado" name="estado" value="1" {{ old('estado', '1') == '1' ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="d-flex align-items-center justify-content-end gap-3 pt-4 mt-4 border-top" style="border-color: #f0f0f0 !important;">
                <a href="{{ route('productos.index') }}" class="btn btn-link text-decoration-none text-muted fw-semibold px-3">Cancelar</a>
                <button type="submit" class="btn rounded-pill px-4 py-2-5 fw-bold shadow-sm text-dark" style="background-color: #f2a922; border: none;">
                    <i class="bi bi-plus-circle me-1"></i> Publicar en Carta
                </button>
            </div>
        </form>
    </div>
</div>
@endsection