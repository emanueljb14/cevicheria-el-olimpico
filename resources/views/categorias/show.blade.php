@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 850px;">
    <!-- Encabezado Estilo El Olímpico -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                 style="width: 48px; height: 48px; background-color: #f2a922; color: #0d1b1e;">
                <i class="bi bi-folder2-open fs-5 fw-bold"></i>
            </div>
            <div>
                <span class="badge rounded-pill text-uppercase tracking-wider px-3 py-1 mb-1" 
                      style="background-color: #fff8e7; color: #d99100; border: 1px solid #fce8b3; font-size: 0.68rem; font-weight: 700;">
                    Detalles de la Categoría
                </span>
                <h4 class="fw-bold mb-0 text-dark" style="font-family: 'Playfair Display', Georgia, serif;">
                    {{ $categoria->nombre }}
                </h4>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-warning rounded-pill px-3 fs-7 fw-bold text-dark shadow-sm" style="background-color: #f2a922; border: none;">
                <i class="bi bi-pencil me-1"></i> Editar
            </a>
            <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary rounded-pill px-3 fs-7 fw-medium">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    <!-- Card de Información Principal -->
    <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 mb-4" style="background-color: #ffffff; border: 1px solid #eaeaea !important;">
        <div class="row g-4">
            <div class="col-md-6">
                <label class="text-uppercase text-secondary fw-bold fs-7 tracking-wider mb-1 d-block">
                    Nombre de la Categoría
                </label>
                <div class="fs-5 fw-bold text-dark">
                    <i class="bi bi-tag-fill me-2" style="color: #d99100;"></i>{{ $categoria->nombre }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-uppercase text-secondary fw-bold fs-7 tracking-wider mb-1 d-block">
                    Fecha de Registro
                </label>
                <div class="fs-6 text-dark fw-medium">
                    <i class="bi bi-calendar3 me-2 text-secondary"></i>
                    {{ $categoria->created_at ? $categoria->created_at->format('d/m/Y h:i A') : 'No registrada' }}
                </div>
            </div>

            <div class="col-12 mt-3">
                <label class="text-uppercase text-secondary fw-bold fs-7 tracking-wider mb-1 d-block">
                    Descripción
                </label>
                <div class="p-3 rounded-3 text-dark" style="background-color: #f8f9fa;">
                    {{ $categoria->descripcion ?? 'Sin descripción registrada para esta categoría.' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Productos Asociados -->
    <div class="card border-0 rounded-4 shadow-sm p-4" style="background-color: #ffffff; border: 1px solid #eaeaea !important;">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2" style="font-family: 'Playfair Display', Georgia, serif;">
                <i class="bi bi-box-seam" style="color: #d99100;"></i> Platillos / Productos Asociados
            </h5>
            <span class="badge rounded-pill bg-light text-dark border px-3">
                {{ $categoria->productos ? $categoria->productos->count() : 0 }} Registrados
            </span>
        </div>

        @if(isset($categoria->productos) && $categoria->productos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase text-secondary fs-7 fw-bold border-0">Platillo</th>
                            <th class="text-uppercase text-secondary fs-7 fw-bold border-0">Precio</th>
                            <th class="text-uppercase text-secondary fs-7 fw-bold border-0">Stock</th>
                            <th class="text-uppercase text-secondary fs-7 fw-bold border-0 text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categoria->productos as $producto)
                            <tr>
                                <td class="fw-bold text-dark">{{ $producto->nombre }}</td>
                                <td class="fw-bold" style="color: #d99100;">S/ {{ number_format($producto->precio, 2) }}</td>
                                <td>{{ $producto->stock }} porciones</td>
                                <td class="text-center">
                                    @if($producto->estado)
                                        <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3">Disponible</span>
                                    @else
                                        <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-3">Agotado</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4 text-muted">
                <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                <p class="mb-0">Aún no hay platillos o productos asignados a esta categoría.</p>
            </div>
        @endif
    </div>
</div>
@endsection