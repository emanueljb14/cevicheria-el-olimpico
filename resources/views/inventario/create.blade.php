@extends('layouts.app')

@section('title', 'Registrar Nuevo Insumo')
@section('page-title', 'Crear Insumo')

@push('styles')
<style>
    .inv-create-wrapper {
        max-width: 860px;
        margin: 0 auto;
    }

    /* NAVEGACIÓN BREADCRUMB */
    .inv-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: var(--muted, #68757d);
        margin-bottom: 16px;
    }
    .inv-breadcrumb a {
        color: var(--sea, #00a7a7);
        text-decoration: none;
        font-weight: 600;
    }
    .inv-breadcrumb a:hover {
        text-decoration: underline;
    }

    /* CARD CONTENEDOR */
    .inv-card-form {
        background: var(--paper, #ffffff);
        border: 1px solid var(--line, #dfe9eb);
        border-radius: 20px;
        box-shadow: 0 16px 40px rgba(6, 43, 61, 0.06);
        overflow: hidden;
    }

    /* CABECERA DE LA CARD */
    .inv-card-header {
        padding: 24px 32px;
        background: linear-gradient(135deg, #062b3d 0%, #0d3a4d 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }
    .inv-card-header-info h2 {
        margin: 0;
        font-family: 'Playfair Display', serif;
        font-size: 22px;
        font-weight: 700;
        color: #ffffff;
    }
    .inv-card-header-info p {
        margin: 4px 0 0 0;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.7);
    }
    .inv-header-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #f6c453;
        backdrop-filter: blur(4px);
    }

    /* CUERPO DEL FORMULARIO */
    .inv-card-body {
        padding: 32px;
    }

    /* SECCIONES FORMULARIO */
    .form-section {
        margin-bottom: 28px;
    }
    .form-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--sea, #00a7a7);
        margin-bottom: 20px;
        padding-bottom: 8px;
        border-bottom: 2px solid rgba(0, 167, 167, 0.15);
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    .col-span-2 {
        grid-column: span 2;
    }

    /* CAMPOS Y LABELS */
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .form-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--navy, #062b3d);
        letter-spacing: 0.03em;
    }
    .form-label .required {
        color: #e63946;
        margin-left: 2px;
    }

    /* INPUT GROUPS E INPUTS */
    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }
    .input-wrapper i.input-icon {
        position: absolute;
        left: 14px;
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
        transition: color 0.2s;
    }
    .form-control {
        width: 100%;
        height: 44px;
        padding: 0 14px 0 40px;
        border: 1px solid var(--line, #cfdddf);
        border-radius: 12px;
        font-size: 14px;
        color: #18242b;
        background: var(--paper, #ffffff);
        outline: none;
        transition: all 0.2s ease;
    }
    .form-control:focus {
        border-color: var(--sea, #00a7a7);
        box-shadow: 0 0 0 3px rgba(0, 167, 167, 0.12);
    }
    .form-control:focus + i.input-icon,
    .input-wrapper:focus-within i.input-icon {
        color: var(--sea, #00a7a7);
    }

    /* SUFIJOS DENTRO DEL INPUT (Ej: S/, Kg) */
    .input-addon-right {
        position: absolute;
        right: 14px;
        font-size: 12px;
        font-weight: 700;
        color: #94a3b8;
        pointer-events: none;
    }

    /* SUGERENCIAS RÁPIDAS DE UNIDAD (CHIPS) */
    .unit-chips {
        display: flex;
        gap: 6px;
        margin-top: 6px;
        flex-wrap: wrap;
    }
    .chip {
        padding: 3px 10px;
        border-radius: 999px;
        border: 1px solid var(--line, #cfdddf);
        background: var(--bg-subtle, #f8fafc);
        color: var(--muted, #68757d);
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .chip:hover {
        background: var(--sea, #00a7a7);
        color: #ffffff;
        border-color: var(--sea, #00a7a7);
    }

    /* HELP TEXT */
    .help-text {
        font-size: 11px;
        color: var(--muted, #68757d);
        margin-top: 4px;
    }

    /* ERRORES */
    .alert-danger-custom {
        margin-bottom: 24px;
        padding: 16px;
        border-radius: 12px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        font-size: 13px;
    }
    .alert-danger-custom ul {
        margin: 6px 0 0 0;
        padding-left: 20px;
    }

    /* PIE DEL FORMULARIO Y BOTONES */
    .inv-card-footer {
        padding: 20px 32px;
        background: var(--bg-subtle, #f8fafc);
        border-top: 1px solid var(--line, #dfe9eb);
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
    }
    .btn-secondary-custom {
        padding: 11px 22px;
        border-radius: 999px;
        border: 1px solid var(--line, #cfdddf);
        background: #ffffff;
        color: var(--navy, #062b3d);
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-secondary-custom:hover {
        background: #f1f5f9;
        color: #000;
    }
    .btn-primary-custom {
        padding: 11px 26px;
        border-radius: 999px;
        border: none;
        background: var(--gold, #f6c453);
        color: var(--navy, #062b3d);
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(246, 196, 83, 0.3);
        transition: all 0.2s ease;
    }
    .btn-primary-custom:hover {
        background: var(--gold-dark, #e0b043);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(224, 176, 67, 0.4);
    }

    /* DARK MODE ADAPTABILITY */
    body.dark-mode .inv-card-form {
        background: #0b3447;
        border-color: #28505f;
    }
    body.dark-mode .inv-card-footer {
        background: #082837;
        border-color: #28505f;
    }
    body.dark-mode .form-label {
        color: #ffffff;
    }
    body.dark-mode .form-control {
        background: #0d3a4d;
        border-color: #315565;
        color: #ffffff;
    }
    body.dark-mode .form-control:focus {
        border-color: #00a7a7;
    }
    body.dark-mode .btn-secondary-custom {
        background: #0d3a4d;
        border-color: #315565;
        color: #ffffff;
    }
    body.dark-mode .chip {
        background: #0d3a4d;
        border-color: #315565;
        color: #cbd5e1;
    }

    @media (max-width: 640px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        .col-span-2 {
            grid-column: span 1;
        }
        .inv-card-body {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="inv-create-wrapper">

    <!-- Navegación simple -->
    <nav class="inv-breadcrumb">
        <a href="{{ route('inventario.index') }}"><i class="fa-solid fa-arrow-left"></i> Volver al inventario</a>
        <span>/</span>
        <span>Nuevo Insumo</span>
    </nav>

    <!-- Errores de Validación -->
    @if ($errors->any())
        <div class="alert-danger-custom">
            <div style="font-weight:700; display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-triangle-exclamation"></i> Hay errores en el formulario:
            </div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Card Principal -->
    <div class="inv-card-form">
        
        <!-- Encabezado Tarjeta -->
        <div class="inv-card-header">
            <div class="inv-card-header-info">
                <h2>Registrar Insumo</h2>
                <p>Ingresa los detalles del nuevo material o ingrediente para el almacén</p>
            </div>
            <div class="inv-header-icon">
                <i class="fa-solid fa-box-archive"></i>
            </div>
        </div>

        <form action="{{ route('inventario.store') }}" method="POST">
            @csrf
            
            <div class="inv-card-body">
                
                <!-- SECCIÓN 1: DATOS BÁSICOS -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fa-solid fa-info-circle"></i> Información General
                    </div>

                    <div class="form-grid">
                        <!-- Nombre del insumo -->
                        <div class="form-group col-span-2">
                            <label class="form-label" for="nombre_insumo">
                                Nombre del Insumo <span class="required">*</span>
                            </label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-tag input-icon"></i>
                                <input type="text" 
                                       id="nombre_insumo" 
                                       name="nombre_insumo" 
                                       class="form-control" 
                                       value="{{ old('nombre_insumo') }}" 
                                       placeholder="Ej: Carne de Res, Limón, Aceite Vegetal, Servilletas" 
                                       required 
                                       autofocus>
                            </div>
                        </div>

                        <!-- Producto Asociado -->
                        <div class="form-group">
                            <label class="form-label" for="producto_id">
                                Producto Comercial Asociado
                            </label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-utensils input-icon"></i>
                                <select id="producto_id" name="producto_id" class="form-control">
                                    <option value="">-- Sin asociación (Genérico) --</option>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->id }}" {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                                            {{ $producto->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="help-text">Vínculalo si este insumo pertenece a un plato directo del menú.</span>
                        </div>

                        <!-- Unidad de medida -->
                        <div class="form-group">
                            <label class="form-label" for="unidad_medida">
                                Unidad de Medida <span class="required">*</span>
                            </label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-scale-balanced input-icon"></i>
                                <input type="text" 
                                       id="unidad_medida" 
                                       name="unidad_medida" 
                                       class="form-control" 
                                       value="{{ old('unidad_medida') }}" 
                                       placeholder="Ej: Kg, Litros, Unidades" 
                                       required>
                            </div>
                            <!-- Sugerencias de chips rápidos -->
                            <div class="unit-chips">
                                <span class="chip" onclick="setUnit('Kg')">Kg</span>
                                <span class="chip" onclick="setUnit('Gramos')">Gramos</span>
                                <span class="chip" onclick="setUnit('Litros')">Litros</span>
                                <span class="chip" onclick="setUnit('Unidades')">Unidades</span>
                                <span class="chip" onclick="setUnit('Paquetes')">Paquetes</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 2: CONTROL DE STOCK Y COSTO -->
                <div class="form-section" style="margin-bottom: 0;">
                    <div class="form-section-title">
                        <i class="fa-solid fa-cubes-stacked"></i> Inventario & Costos
                    </div>

                    <div class="form-grid">
                        <!-- Stock Inicial -->
                        <div class="form-group">
                            <label class="form-label" for="stock">
                                Stock Inicial <span class="required">*</span>
                            </label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-boxes-storage input-icon"></i>
                                <input type="number" 
                                       step="0.01" 
                                       id="stock" 
                                       name="stock" 
                                       class="form-control" 
                                       value="{{ old('stock', '0.00') }}" 
                                       min="0" 
                                       required>
                            </div>
                        </div>

                        <!-- Stock Mínimo -->
                        <div class="form-group">
                            <label class="form-label" for="stock_minimo">
                                Stock Mínimo de Alerta <span class="required">*</span>
                            </label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-triangle-exclamation input-icon"></i>
                                <input type="number" 
                                       step="0.01" 
                                       id="stock_minimo" 
                                       name="stock_minimo" 
                                       class="form-control" 
                                       value="{{ old('stock_minimo', '5.00') }}" 
                                       min="0" 
                                       required>
                            </div>
                            <span class="help-text">Notificará cuando el stock esté igual o por debajo de este valor.</span>
                        </div>

                        <!-- Precio Unitario -->
                        <div class="form-group col-span-2">
                            <label class="form-label" for="precio_unitario">
                                Costo / Precio Unitario Aproximado
                            </label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-coins input-icon"></i>
                                <input type="number" 
                                       step="0.01" 
                                       id="precio_unitario" 
                                       name="precio_unitario" 
                                       class="form-control" 
                                       value="{{ old('precio_unitario') }}" 
                                       placeholder="0.00" 
                                       min="0">
                                <span class="input-addon-right">PEN (S/)</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- PIE DE BOTONES -->
            <div class="inv-card-footer">
                <a href="{{ route('inventario.index') }}" class="btn-secondary-custom">
                    Cancelar
                </a>
                <button type="submit" class="btn-primary-custom">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar Insumo
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Función auxiliar para autocompletar la unidad con las etiquetas chips
    function setUnit(unit) {
        const input = document.getElementById('unidad_medida');
        input.value = unit;
        input.focus();
    }
</script>
@endpush