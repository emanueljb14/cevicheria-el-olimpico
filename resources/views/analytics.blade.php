@extends('layouts.app')

@section('title', 'Predicciones ML')
@section('page-title', 'Análisis Predictivo con Machine Learning')

@section('content')
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <!-- Reemplaza la URL según donde ejecutes Streamlit -->
        <iframe src="http://127.0.0.1:8501" width="100%" height="800px" style="border:none;"></iframe>
    </div>
</div>
@endsection