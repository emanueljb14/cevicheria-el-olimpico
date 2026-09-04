@extends('layouts.app')

@section('title', 'Editar reporte')
@section('page-title', 'Editar reporte')

@push('styles')
<style>
    .edit-report{--navy:#062b3d;--sea:#00a7a7;--gold:#f6c453;--line:#dfe9eb;max-width:760px;margin:auto}.edit-head{margin-bottom:24px}.edit-head small{color:var(--sea);font-size:11px;font-weight:800;letter-spacing:.16em;text-transform:uppercase}.edit-head h1{margin:8px 0 6px;color:var(--navy);font:700 clamp(30px,4vw,43px) 'Playfair Display',serif}.edit-head p{margin:0;color:#68757d}.edit-card{padding:27px;border:1px solid var(--line);border-radius:20px;background:#fff;box-shadow:0 12px 32px rgba(6,43,61,.07)}.report-meta{display:grid;grid-template-columns:repeat(3,1fr);gap:11px;margin-bottom:24px}.meta-box{padding:13px;border-radius:12px;background:#f3f8f8}.meta-box small,.meta-box strong{display:block}.meta-box small{margin-bottom:4px;color:#78898f;font-size:10px;text-transform:uppercase}.meta-box strong{color:var(--navy);font-size:13px;text-transform:capitalize}.field label{display:block;margin-bottom:8px;color:var(--navy);font-size:13px;font-weight:800}.field input{width:100%;height:51px;padding:0 14px;border:1px solid #cfdddf;border-radius:13px;outline:none}.field input:focus{border-color:var(--sea);box-shadow:0 0 0 4px rgba(0,167,167,.1)}.error{display:block;margin-top:6px;color:#b43a34;font-size:12px}.actions{display:flex;justify-content:flex-end;gap:10px;margin-top:24px}.actions a,.actions button{display:inline-flex;min-height:47px;align-items:center;gap:8px;padding:0 19px;border-radius:999px;font-weight:800;text-decoration:none}.actions a{color:var(--navy);border:1px solid var(--line)}.actions button{border:0;color:var(--navy);background:var(--gold)}body.dark-mode .edit-head h1,body.dark-mode .meta-box strong,body.dark-mode .field label{color:#fff}body.dark-mode .edit-card{border-color:#28505f;background:#0b3447}body.dark-mode .meta-box{background:#092b3c}body.dark-mode .field input{color:#fff;border-color:#315867;background:#0c3a4e}@media(max-width:600px){.report-meta{grid-template-columns:1fr}.actions{flex-direction:column-reverse}.actions a,.actions button{justify-content:center}}
</style>
@endpush

@section('content')
<div class="edit-report">
    <header class="edit-head"><small>Administración</small><h1>Editar reporte</h1><p>Solo se modificará el título; los resultados calculados permanecerán iguales.</p></header>
    <form class="edit-card" method="POST" action="{{ route('reportes.update',$reporte) }}">
        @csrf @method('PUT')
        <div class="report-meta"><div class="meta-box"><small>Tipo</small><strong>{{ $reporte->tipo }}</strong></div><div class="meta-box"><small>Fecha inicial</small><strong>{{ $reporte->fecha_inicio?->format('d/m/Y') ?? 'Sin fecha' }}</strong></div><div class="meta-box"><small>Monto</small><strong>S/ {{ number_format($reporte->monto_total,2) }}</strong></div></div>
        <div class="field"><label for="titulo">Título del reporte</label><input id="titulo" name="titulo" type="text" value="{{ old('titulo',$reporte->titulo) }}" maxlength="255" required autofocus>@error('titulo')<span class="error">{{ $message }}</span>@enderror</div>
        <div class="actions"><a href="{{ route('reportes.index') }}">Cancelar</a><button type="submit"><i class="fa-solid fa-floppy-disk"></i> Guardar cambios</button></div>
    </form>
</div>
@endsection

