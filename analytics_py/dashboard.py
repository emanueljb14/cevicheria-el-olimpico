import streamlit as st
import pandas as pd
import numpy as np
import os
import joblib
import tensorflow as tf
import matplotlib.pyplot as plt

# 1. Configuración de pantalla
st.set_page_config(page_title="El Olímpico - Analytics", page_icon="🌊", layout="wide")

# Estilos CSS para tarjetas y métricas
st.markdown("""
<style>
    .metric-card {
        background-color: #1e293b;
        border-radius: 10px;
        padding: 15px 20px;
        border: 1px solid #334155;
        color: white;
    }
    .metric-title { font-size: 0.85rem; color: #94a3b8; font-weight: 600; }
    .metric-value { font-size: 1.8rem; font-weight: 700; color: #38bdf8; }
</style>
""", unsafe_allow_html=True)

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
csv_path = os.path.join(BASE_DIR, "storage", "app", "exports", "ventas.csv")
sklearn_path = os.path.join(BASE_DIR, "analytics_py", "models", "model_sklearn.pkl")
tf_path = os.path.join(BASE_DIR, "analytics_py", "models", "model_tf.keras")

st.title("📊 Panel Analítico - Cevichería El Olímpico")
st.caption("Sistema Integrado de Business Intelligence & Inferencia IA")
st.markdown("---")

if not os.path.exists(csv_path):
    st.error("No se encontró el archivo ventas.csv.")
else:
    df = pd.read_csv(csv_path)
    df['fecha'] = pd.to_datetime(df['fecha']).dt.date

    # 2. Tarjetas KPI superiores
    c1, c2, c3, c4 = st.columns(4)
    with c1:
        st.markdown(f'<div class="metric-card"><div class="metric-title">VENTAS TOTALES</div><div class="metric-value">S/ {df["total"].sum():,.2f}</div></div>', unsafe_allow_html=True)
    with c2:
        st.markdown(f'<div class="metric-card"><div class="metric-title">TICKET PROMEDIO</div><div class="metric-value">S/ {df["total"].mean():,.2f}</div></div>', unsafe_allow_html=True)
    with c3:
        st.markdown(f'<div class="metric-card"><div class="metric-title">PLATOS VENDIDOS</div><div class="metric-value">{int(df["cantidad"].sum())}</div></div>', unsafe_allow_html=True)
    with c4:
        top_prod = df.groupby('producto')['cantidad'].sum().idxmax()
        st.markdown(f'<div class="metric-card"><div class="metric-title">MÁS VENDIDO</div><div class="metric-value" style="font-size:1.2rem; margin-top:8px;">{top_prod}</div></div>', unsafe_allow_html=True)

    st.markdown("<br>", unsafe_allow_html=True)

    # 3. Sección de Gráficos Integrados
    col_g1, col_g2 = st.columns(2)

    with col_g1:
        st.subheader("📈 Tendencia Diaria de Ventas")
        ventas_diarias = df.groupby('fecha')['total'].sum().reset_index()
        st.line_chart(ventas_diarias, x='fecha', y='total', color="#38bdf8")

    with col_g2:
        st.subheader("📦 Ranking de Productos (Ingresos)")
        comparativa = df.groupby('producto')['total'].sum().reset_index().sort_values(by='total', ascending=True)
        st.bar_chart(comparativa, x='producto', y='total', color="#818cf8", horizontal=True)

    st.markdown("---")

    # 4. Tabla formateada y Predicciones
    col_t, col_p = st.columns([1, 1])

    with col_t:
        st.subheader("📋 Detalle General")
        tabla = df.groupby('producto').agg(Cantidad=('cantidad', 'sum'), Ingresos=('total', 'sum')).reset_index().sort_values(by='Ingresos', ascending=False)
        tabla['Ingresos'] = tabla['Ingresos'].apply(lambda x: f"S/ {x:,.2f}")
        st.dataframe(tabla, use_container_width=True, hide_index=True)

    with col_p:
        st.subheader("🤖 Proyección IA (Próximo Día)")
        proximo_dia = pd.DataFrame({'dia_num': [len(ventas_diarias)]})

        if os.path.exists(sklearn_path):
            m_sk = joblib.load(sklearn_path)
            p_sk = m_sk.predict(proximo_dia)[0]
            st.success(f"**Scikit-Learn (Regresión):** S/ {p_sk:,.2f}")

        if os.path.exists(tf_path):
            m_tf = tf.keras.models.load_model(tf_path)
            p_tf = m_tf.predict(proximo_dia.values, verbose=0)[0][0]
            st.info(f"**TensorFlow (Red Neuronal):** S/ {p_tf:,.2f}")