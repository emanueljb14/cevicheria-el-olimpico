@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 900px;">
    <!-- Encabezado Estilo El Olímpico -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                 style="width: 48px; height: 48px; background-color: #f2a922; color: #0d1b1e;">
                <i class="bi bi-person-plus-fill fs-5 fw-bold"></i>
            </div>
            <div>
                <span class="badge rounded-pill text-uppercase tracking-wider px-3 py-1 mb-1" 
                      style="background-color: #fff8e7; color: #d99100; border: 1px solid #fce8b3; font-size: 0.68rem; font-weight: 700;">
                    Módulo de Clientes
                </span>
                <h4 class="fw-bold mb-0 text-dark" style="font-family: 'Playfair Display', Georgia, serif;">Registrar Cliente</h4>
            </div>
        </div>
        <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary rounded-pill px-3 fs-7 fw-medium">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <!-- Card Estilo Claro Minimalista -->
    <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5" style="background-color: #ffffff; border: 1px solid #eaeaea !important;">
        <form action="{{ route('clientes.store') }}" method="POST">
            @csrf

            <!-- Identificación -->
            <div class="mb-4">
                <h6 class="text-dark fw-bold mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-card-heading" style="color: #d99100;"></i> Documentación de Identidad
                </h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="tipo_documento" class="form-label text-uppercase text-secondary fw-bold fs-7">Tipo Doc. <span class="text-danger">*</span></label>
                        <select name="tipo_documento" id="tipo_documento" class="form-select border-0 text-dark p-3 @error('tipo_documento') is-invalid @enderror" style="background-color: #f8f9fa;" required>
                            <option value="DNI" {{ old('tipo_documento') == 'DNI' ? 'selected' : '' }}>DNI</option>
                            <option value="RUC" {{ old('tipo_documento') == 'RUC' ? 'selected' : '' }}>RUC</option>
                            <option value="CE" {{ old('tipo_documento') == 'CE' ? 'selected' : '' }}>Carné de Extranjería</option>
                        </select>
                    </div>

                    <div class="col-md-8">
                        <label for="numero_documento" class="form-label text-uppercase text-secondary fw-bold fs-7">N° Documento <span class="text-danger">*</span></label>
                        <input type="text" name="numero_documento" id="numero_documento" class="form-control border-0 text-dark p-3 @error('numero_documento') is-invalid @enderror" style="background-color: #f8f9fa;" value="{{ old('numero_documento') }}" placeholder="Ej. 72839102" required>
                        @error('numero_documento')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mt-3">
                        <label for="nombre" class="form-label text-uppercase text-secondary fw-bold fs-7">Nombre Completo / Razón Social <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="nombre" class="form-control border-0 text-dark p-3 @error('nombre') is-invalid @enderror" style="background-color: #f8f9fa;" value="{{ old('nombre') }}" placeholder="Ej. Juan Pérez u Holas S.A.C." required>
                        @error('nombre')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <hr style="border-color: #f0f0f0;" class="my-4">

            <!-- Contacto -->
            <div class="mb-4">
                <h6 class="text-dark fw-bold mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-geo-alt-fill" style="color: #d99100;"></i> Datos de Contacto
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="telefono" class="form-label text-uppercase text-secondary fw-bold fs-7">Teléfono / Celular</label>
                        <input type="text" name="telefono" id="telefono" class="form-control border-0 text-dark p-3" style="background-color: #f8f9fa;" value="{{ old('telefono') }}" placeholder="987654321">
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label text-uppercase text-secondary fw-bold fs-7">Correo Electrónico</label>
                        <input type="email" name="email" id="email" class="form-control border-0 text-dark p-3" style="background-color: #f8f9fa;" value="{{ old('email') }}" placeholder="cliente@ejemplo.com">
                    </div>

                    <div class="col-12 mt-3">
                        <label for="direccion" class="form-label text-uppercase text-secondary fw-bold fs-7">Dirección de Entrega</label>
                        <input type="text" name="direccion" id="direccion" class="form-control border-0 text-dark p-3" style="background-color: #f8f9fa;" value="{{ old('direccion') }}" placeholder="Av. Los Olivos 123">
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="d-flex align-items-center justify-content-end gap-3 pt-4 mt-4 border-top" style="border-color: #f0f0f0 !important;">
                <a href="{{ route('clientes.index') }}" class="btn btn-link text-decoration-none text-muted fw-semibold px-3">Cancelar</a>
                <button type="submit" class="btn rounded-pill px-4 py-2-5 fw-bold shadow-sm text-dark" style="background-color: #f2a922; border: none;">
                    <i class="bi bi-person-check me-1"></i> Guardar Cliente
                </button>
            </div>
        </form>
    </div>
</div>
@endsection