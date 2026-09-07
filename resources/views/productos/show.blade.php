@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 850px;">
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
                    Detalles del Platillo
                </span>
                <h4 class="fw-bold mb-0 text-dark" style="font-family: 'Playfair Display', Georgia, serif;">
                    {{ $producto->nombre }}
                </h4>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-warning rounded-pill px-3 fs-7 fw-bold text-dark shadow-sm" style="background-color: #f2a922; border: none;">
                <i class="bi bi-pencil me-1"></i> Editar
            </a>
            <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary rounded-pill px-3 fs-7 fw-medium">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    <!-- Card Principal de Detalles -->
    <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5" style="background-color: #ffffff; border: 1px solid #eaeaea !important;">
        <div class="row g-4">
            <!-- Categoría y Estado -->
            <div class="col-md-6">
                <label class="text-uppercase text-secondary fw-bold fs-7 tracking-wider mb-1 d-block">Categoría</label>
                <div class="fs-6 fw-bold text-dark">
                    <span class="badge rounded-pill px-3 py-2" style="background-color: #f8f9fa; color: #333; border: 1px solid #ddd;">
                        <i class="bi bi-folder-fill me-1" style="color: #d99100;"></i>
                        {{ $producto->categoria->nombre ?? 'Sin categoría' }}
                    </span>
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-uppercase text-secondary fw-bold fs-7 tracking-wider mb-1 d-block">Estado en Carta</label>
                <div>
                    @if($producto->estado)
                        <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-2 fw-bold">
                            <i class="bi bi-check-circle-fill me-1"></i> Disponible
                        </span>
                    @else
                        <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 fw-bold">
                            <i class="bi bi-x-circle-fill me-1"></i> Agotado / No Disponible
                        </span>
                    @endif
                </div>
            </div>

            <!-- Precio y Stock -->
            <div class="col-md-6">
                <label class="text-uppercase text-secondary fw-bold fs-7 tracking-wider mb-1 d-block">Precio de Venta</label>
                <div class="fs-3 fw-bold" style="color: #d99100;">
                    S/ {{ number_format($producto->precio, 2) }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-uppercase text-secondary fw-bold fs-7 tracking-wider mb-1 d-block">Stock / Porciones Disponibles</label>
                <div class="fs-5 fw-bold text-dark">
                    <i class="bi bi-stack me-1 text-secondary"></i> {{ $producto->stock }} porciones
                </div>
            </div>

            <hr style="border-color: #f0f0f0;" class="my-2">

            <!-- Descripción / Ingredientes -->
            <div class="col-12">
                <label class="text-uppercase text-secondary fw-bold fs-7 tracking-wider mb-1 d-block">Descripción / Ingredientes</label>
                <div class="p-3 rounded-3 text-dark fs-6" style="background-color: #f8f9fa; line-height: 1.6;">
                    {{ $producto->descripcion ?? 'Sin descripción o detalle de ingredientes registrado.' }}
                </div>
            </div>

            <!-- Información Adicional de Registro -->
            <div class="col-12 pt-2">
                <div class="d-flex gap-4 text-secondary fs-7">
                    <div>
                        <i class="bi bi-calendar3 me-1"></i> 
                        <strong>Creado:</strong> {{ $producto->created_at ? $producto->created_at->format('d/m/Y h:i A') : 'N/A' }}
                    </div>
                    <div>
                        <i class="bi bi-clock-history me-1"></i> 
                        <strong>Última actualización:</strong> {{ $producto->updated_at ? $producto->updated_at->format('d/m/Y h:i A') : 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection