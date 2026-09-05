import streamlit as st
import pandas as pd
import numpy as np
import os
import joblib
import tensorflow as tf
from sqlalchemy import create_engine

# 1. Configuración de pantalla
st.set_page_config(page_title="El Olímpico - Analytics", page_icon="🌊", layout="wide")

# Estilos CSS Avanzados (UI/UX Dark Premium)
st.markdown("""
<style>
    .block-container {
        padding-top: 1.5rem !important;
        padding-bottom: 2rem !important;
        max-width: 95% !important;
    }
    h1 {
        font-family: 'Inter', sans-serif;
        font-weight: 800 !important;
        background: linear-gradient(90deg, #38bdf8 0%, #818cf8 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0px !important;
    }
    .stCaption { color: #94a3b8 !important; font-size: 0.95rem !important; font-weight: 500; }
    .metric-card {
        background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%);
        border-radius: 14px;
        padding: 18px 22px;
        border: 1px solid #334155;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
    }
    .metric-title { font-size: 0.75rem; color: #94a3b8; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 6px; }
    .metric-value { font-size: 1.8rem; font-weight: 800; color: #f8fafc; line-height: 1.2; }
    .metric-highlight { color: #38bdf8; }
    h3 { color: #f1f5f9 !important; font-size: 1.15rem !important; font-weight: 600 !important; margin-bottom: 1rem !important; }
    hr { border-color: #1e293b !important; margin: 1.8rem 0 !important; }
</style>
""", unsafe_allow_html=True)

# Conexión directa a MySQL (el_olimpo)
DB_URI = "mysql+mysqlconnector://root:@127.0.0.1:3306/el_olimpo"

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
sklearn_path = os.path.join(BASE_DIR, "analytics_py", "models", "model_sklearn.pkl")
tf_path = os.path.join(BASE_DIR, "analytics_py", "models", "model_tf.keras")

st.title("📊 Panel Analítico - Cevichería El Olímpico")
st.caption("Sistema Integrado de Business Intelligence & Inferencia IA (Datos en Vivo)")
st.markdown("---")

@st.cache_data(ttl=60)  # Recarga datos automáticamente cada 60 segundos
def cargar_datos_bd():
    engine = create_engine(DB_URI)
    query = """
        SELECT 
            p.created_at AS fecha,
            pr.nombre AS producto,
            c.nombre AS categoria,
            dp.cantidad,
            dp.precio_unitario AS precio,
            dp.subtotal AS total
        FROM detalle_pedidos dp
        JOIN pedidos p ON dp.pedido_id = p.id
        JOIN productos pr ON dp.producto_id = pr.id
        LEFT JOIN categorias c ON pr.categoria_id = c.id
    """
    return pd.read_sql(query, engine)

try:
    df = cargar_datos_bd()

    if df.empty:
        st.warning("Aún no hay registros de ventas almacenados en la base de datos.")
    else:
        df['fecha'] = pd.to_datetime(df['fecha']).dt.date

        # 2. Tarjetas KPI superiores
        c1, c2, c3, c4 = st.columns(4)
        with c1:
            st.markdown(f'<div class="metric-card"><div class="metric-title">VENTAS TOTALES</div><div class="metric-value metric-highlight">S/ {df["total"].sum():,.2f}</div></div>', unsafe_allow_html=True)
        with c2:
            st.markdown(f'<div class="metric-card"><div class="metric-title">TICKET PROMEDIO</div><div class="metric-value">S/ {df["total"].mean():,.2f}</div></div>', unsafe_allow_html=True)
        with c3:
            st.markdown(f'<div class="metric-card"><div class="metric-title">PLATOS VENDIDOS</div><div class="metric-value">{int(df["cantidad"].sum()):,}</div></div>', unsafe_allow_html=True)
        with c4:
            top_prod = df.groupby('producto')['cantidad'].sum().idxmax()
            st.markdown(f'<div class="metric-card"><div class="metric-title">MÁS VENDIDO</div><div class="metric-value" style="font-size:1.3rem; margin-top:4px; color:#f59e0b;">{top_prod}</div></div>', unsafe_allow_html=True)

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
            num_dias = len(ventas_diarias)

            # Inferencia con Scikit-Learn
            if os.path.exists(sklearn_path):
                proximo_dia_sk = pd.DataFrame({'dia_num': [num_dias]})
                m_sk = joblib.load(sklearn_path)
                p_sk = m_sk.predict(proximo_dia_sk)[0]
                st.success(f"**Scikit-Learn (Regresión):** S/ {p_sk:,.2f}")

            # Inferencia con TensorFlow (con escalado)
            if os.path.exists(tf_path):
                max_dia = float(num_dias) if num_dias > 0 else 1.0
                proximo_dia_tf = np.array([[num_dias / max_dia]], dtype=np.float32)
                
                m_tf = tf.keras.models.load_model(tf_path)
                p_tf = m_tf.predict(proximo_dia_tf, verbose=0)[0][0]
                st.info(f"**TensorFlow (Red Neuronal):** S/ {p_tf:,.2f}")

except Exception as e:
    st.error(f"Error al conectar con la base de datos MySQL: {e}")