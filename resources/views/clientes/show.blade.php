@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 850px;">
    <!-- Encabezado Estilo El Olímpico -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                 style="width: 48px; height: 48px; background-color: #f2a922; color: #0d1b1e;">
                <i class="bi bi-person-badge-fill fs-5 fw-bold"></i>
            </div>
            <div>
                <span class="badge rounded-pill text-uppercase tracking-wider px-3 py-1 mb-1" 
                      style="background-color: #fff8e7; color: #d99100; border: 1px solid #fce8b3; font-size: 0.68rem; font-weight: 700;">
                    Módulo de Clientes
                </span>
                <h4 class="fw-bold mb-0 text-dark" style="font-family: 'Playfair Display', Georgia, serif;">
                    {{ $cliente->nombre }}
                </h4>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-warning rounded-pill px-3 fs-7 fw-bold text-dark shadow-sm" style="background-color: #f2a922; border: none;">
                <i class="bi bi-pencil me-1"></i> Editar
            </a>
            <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary rounded-pill px-3 fs-7 fw-medium">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    <!-- Card Principal de Detalles -->
    <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 mb-4" style="background-color: #ffffff; border: 1px solid #eaeaea !important;">
        <!-- Identificación -->
        <h6 class="text-dark fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-card-heading" style="color: #d99100;"></i> Datos de Identificación
        </h6>
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <label class="text-uppercase text-secondary fw-bold fs-7 tracking-wider mb-1 d-block">Tipo Documento</label>
                <div class="fs-6 fw-bold text-dark">
                    <span class="badge rounded-pill px-3 py-2 bg-light text-dark border">
                        {{ $cliente->tipo_documento }}
                    </span>
                </div>
            </div>

            <div class="col-md-8">
                <label class="text-uppercase text-secondary fw-bold fs-7 tracking-wider mb-1 d-block">Número de Documento</label>
                <div class="fs-5 fw-bold text-dark">
                    {{ $cliente->numero_documento }}
                </div>
            </div>

            <div class="col-12">
                <label class="text-uppercase text-secondary fw-bold fs-7 tracking-wider mb-1 d-block">Nombre Completo / Razón Social</label>
                <div class="fs-5 fw-bold" style="color: #0d1b1e;">
                    {{ $cliente->nombre }}
                </div>
            </div>
        </div>

        <hr style="border-color: #f0f0f0;" class="my-4">

        <!-- Contacto -->
        <h6 class="text-dark fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-geo-alt-fill" style="color: #d99100;"></i> Información de Contacto
        </h6>
        <div class="row g-4">
            <div class="col-md-6">
                <label class="text-uppercase text-secondary fw-bold fs-7 tracking-wider mb-1 d-block">Teléfono / Celular</label>
                <div class="fs-6 text-dark fw-medium">
                    <i class="bi bi-telephone-fill me-2" style="color: #d99100;"></i>
                    {{ $cliente->telefono ?? 'No registrado' }}
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-uppercase text-secondary fw-bold fs-7 tracking-wider mb-1 d-block">Correo Electrónico</label>
                <div class="fs-6 text-dark fw-medium">
                    <i class="bi bi-envelope-fill me-2" style="color: #d99100;"></i>
                    {{ $cliente->email ?? 'No registrado' }}
                </div>
            </div>

            <div class="col-12">
                <label class="text-uppercase text-secondary fw-bold fs-7 tracking-wider mb-1 d-block">Dirección de Entrega</label>
                <div class="p-3 rounded-3 text-dark fs-6" style="background-color: #f8f9fa;">
                    <i class="bi bi-house-door-fill me-2" style="color: #d99100;"></i>
                    {{ $cliente->direccion ?? 'Sin dirección registrada.' }}
                </div>
            </div>

            <!-- Auditoría de Registro -->
            <div class="col-12 pt-2">
                <div class="d-flex gap-4 text-secondary fs-7">
                    <div>
                        <i class="bi bi-calendar3 me-1"></i> 
                        <strong>Registrado:</strong> {{ $cliente->created_at ? $cliente->created_at->format('d/m/Y h:i A') : 'N/A' }}
                    </div>
                    <div>
                        <i class="bi bi-clock-history me-1"></i> 
                        <strong>Última actualización:</strong> {{ $cliente->updated_at ? $cliente->updated_at->format('d/m/Y h:i A') : 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection