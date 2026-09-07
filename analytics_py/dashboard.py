import os
import joblib
import numpy as np
import pandas as pd
import streamlit as st
import tensorflow as tf
import torch
import torch.nn as nn
from sqlalchemy import create_engine

# -----------------------------------------------------------------------------
# 1. CONFIGURACIÓN PANTALLA Y METADATOS
# -----------------------------------------------------------------------------
st.set_page_config(
    page_title="El Olímpico | Cyber Analytics Engine",
    page_icon="🌊",
    layout="wide",
    initial_sidebar_state="expanded",
)


# -----------------------------------------------------------------------------
# DEFINICIÓN CLASE PYTORCH (Requerida para cargar el estado del modelo)
# -----------------------------------------------------------------------------
class VentasRedNeuronal(nn.Module):

  def __init__(self):
    super(VentasRedNeuronal, self).__init__()
    self.capas = nn.Sequential(
        nn.Linear(1, 16),
        nn.ReLU(),
        nn.Linear(16, 8),
        nn.ReLU(),
        nn.Linear(8, 1),
    )

  def forward(self, x):
    return self.capas(x)


# -----------------------------------------------------------------------------
# 2. ESTILOS CSS SUPER PREMIUM (Ultra Dark Glassmorphism, Neon & Glow)
# -----------------------------------------------------------------------------
st.markdown(
    """
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap');

    html, body, [class*="css"] {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* Fondo general Cyber Deep Slate */
    .stApp {
        background: radial-gradient(circle at 50% -20%, #1e1b4b 0%, #0f172a 45%, #020617 100%);
    }

    /* Reducir espacio superior */
    .block-container {
        padding-top: 1.2rem !important;
        padding-bottom: 2rem !important;
        max-width: 96% !important;
    }

    /* Glassmorphism Sidebar */
    [data-testid="stSidebar"] {
        background: rgba(15, 23, 42, 0.6) !important;
        backdrop-filter: blur(20px) !important;
        border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
    }

    /* Header Hero Gradient */
    .hero-container {
        padding: 24px 32px;
        background: rgba(15, 23, 42, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 24px;
        backdrop-filter: blur(16px);
        margin-bottom: 24px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
    }

    .hero-title {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #38bdf8 0%, #818cf8 40%, #c084fc 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -0.02em;
        margin: 0;
    }

    .hero-subtitle {
        color: #94a3b8;
        font-size: 1rem;
        font-weight: 500;
        margin-top: 6px;
    }

    /* Tarjetas KPI Neon Glow */
    .kpi-card {
        background: rgba(15, 23, 42, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        padding: 22px 24px;
        backdrop-filter: blur(12px);
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
    }

    .kpi-card:hover {
        transform: translateY(-5px);
        border-color: rgba(56, 189, 248, 0.4);
        box-shadow: 0 20px 40px -15px rgba(56, 189, 248, 0.25);
    }

    .kpi-label {
        font-size: 0.72rem;
        color: #64748b;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .kpi-val {
        font-size: 2rem;
        font-weight: 800;
        color: #f8fafc;
        line-height: 1.1;
    }

    .cyan-glow { color: #38bdf8; text-shadow: 0 0 12px rgba(56,189,248,0.4); }
    .emerald-glow { color: #34d399; text-shadow: 0 0 12px rgba(52,211,153,0.4); }
    .amber-glow { color: #fbbf24; text-shadow: 0 0 12px rgba(251,191,36,0.4); }
    .purple-glow { color: #c084fc; text-shadow: 0 0 12px rgba(192,132,252,0.4); }

    /* Estilos Tabs Premium */
    .stTabs [data-baseweb="tab-list"] {
        gap: 12px;
        background-color: transparent;
    }

    .stTabs [data-baseweb="tab"] {
        height: 48px;
        border-radius: 12px;
        background: rgba(15, 23, 42, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.05);
        color: #94a3b8;
        font-weight: 700;
        padding: 0 24px;
        transition: all 0.2s ease;
    }

    .stTabs [aria-selected="true"] {
        background: linear-gradient(135deg, rgba(56, 189, 248, 0.2) 0%, rgba(129, 140, 248, 0.2) 100%) !important;
        border: 1px solid rgba(56, 189, 248, 0.5) !important;
        color: #38bdf8 !important;
        box-shadow: 0 4px 20px rgba(56, 189, 248, 0.15);
    }

    /* Tablas de Streamlit integradas en modo oscuro */
    [data-testid="stDataFrame"] {
        background: rgba(15, 23, 42, 0.5) !important;
        border-radius: 16px !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
    }
</style>
""",
    unsafe_allow_html=True,
)

# -----------------------------------------------------------------------------
# 3. CONEXIÓN A LA BASE DE DATOS Y CARGA CACHEADA
# -----------------------------------------------------------------------------
DB_URI = "mysql+mysqlconnector://root:@127.0.0.1:3306/el_olimpo"
CURRENT_DIR = os.path.dirname(os.path.abspath(__file__))
MODELS_DIR = os.path.join(CURRENT_DIR, "models")

sklearn_path = os.path.join(MODELS_DIR, "model_sklearn.pkl")
tf_path = os.path.join(MODELS_DIR, "model_tf.keras")
pytorch_path = os.path.join(MODELS_DIR, "model_pytorch.pth")


@st.cache_data(ttl=30)
def cargar_datos_completos():
  engine = create_engine(DB_URI)

  # 1. Ventas & Detalles
  q_ventas = """
        SELECT 
            p.id AS pedido_id,
            p.created_at AS fecha_hora,
            DATE(p.created_at) AS fecha,
            HOUR(p.created_at) AS hora,
            DAYNAME(p.created_at) AS dia_semana,
            pr.id AS producto_id,
            pr.nombre AS producto,
            c.nombre AS categoria,
            dp.cantidad,
            dp.precio_unitario AS precio,
            dp.subtotal AS total,
            p.tipo,
            p.mesa_id
        FROM detalle_pedidos dp
        JOIN pedidos p ON dp.pedido_id = p.id
        JOIN productos pr ON dp.producto_id = pr.id
        LEFT JOIN categorias c ON pr.categoria_id = c.id
        WHERE p.estado != 'cancelado'
    """

  # 2. Inventario
  q_inventario = """
        SELECT nombre_insumo, stock, stock_minimo, unidad_medida, precio_unitario 
        FROM inventarios
    """

  df_v = pd.read_sql(q_ventas, engine)
  df_i = pd.read_sql(q_inventario, engine)

  return df_v, df_i


# -----------------------------------------------------------------------------
# 4. INTERFAZ DE USUARIO PRINCIPAL
# -----------------------------------------------------------------------------

# HERO BANNER
st.markdown(
    """
<div class="hero-container">
    <div class="hero-title">🌊 EL OLÍMPICO · ANALYTICS PRO</div>
    <div class="hero-subtitle">Centro Inteligente de Control Operacional, Inventarios e Inferencia Predictiva de IA</div>
</div>
""",
    unsafe_allow_html=True,
)

try:
  df, df_inv = cargar_datos_completos()

  if df.empty:
    st.warning("⚡ La base de datos no registra ventas activas por el momento.")
  else:
    df["fecha"] = pd.to_datetime(df["fecha"])

    # --- SIDEBAR: FILTROS AVANZADOS ---
    st.sidebar.markdown("### 🎛️ Filtros Estratégicos")

    min_date = df["fecha"].min().date()
    max_date = df["fecha"].max().date()

    rango_fechas = st.sidebar.date_input(
        "📅 Período de Análisis",
        value=(min_date, max_date),
        min_value=min_date,
        max_value=max_date,
    )

    cat_list = ["Todas"] + list(df["categoria"].dropna().unique())
    cat_select = st.sidebar.selectbox("📂 Categoría de Producto", cat_list)

    tipo_pedido = ["Todos"] + list(df["tipo"].dropna().unique())
    tipo_select = st.sidebar.selectbox("🛵 Modalidad de Servicio", tipo_pedido)

    # Filtrado dinámico
    df_f = df.copy()
    if isinstance(rango_fechas, tuple) and len(rango_fechas) == 2:
      df_f = df_f[
          (df_f["fecha"].dt.date >= rango_fechas[0])
          & (df_f["fecha"].dt.date <= rango_fechas[1])
      ]
    if cat_select != "Todas":
      df_f = df_f[df_f["categoria"] == cat_select]
    if tipo_select != "Todos":
      df_f = df_f[df_f["tipo"] == tipo_select]

    # --- TARJETAS KPI DE IMPACTO ---
    k1, k2, k3, k4 = st.columns(4)

    ingresos_totales = df_f["total"].sum()
    total_pedidos = df_f["pedido_id"].nunique()
    ticket_prom = (
        ingresos_totales / total_pedidos if total_pedidos > 0 else 0
    )
    plato_top = (
        df_f.groupby("producto")["cantidad"].sum().idxmax()
        if not df_f.empty
        else "N/A"
    )

    with k1:
      st.markdown(
          f"""
                <div class="kpi-card">
                    <div class="kpi-label">Ingresos Totales</div>
                    <div class="kpi-val cyan-glow">S/ {ingresos_totales:,.2f}</div>
                </div>
            """,
          unsafe_allow_html=True,
      )

    with k2:
      st.markdown(
          f"""
                <div class="kpi-card">
                    <div class="kpi-label">Ticket Promedio</div>
                    <div class="kpi-val emerald-glow">S/ {ticket_prom:,.2f}</div>
                </div>
            """,
          unsafe_allow_html=True,
      )

    with k3:
      st.markdown(
          f"""
                <div class="kpi-card">
                    <div class="kpi-label">Platos Despachados</div>
                    <div class="kpi-val purple-glow">{int(df_f["cantidad"].sum()):,}</div>
                </div>
            """,
          unsafe_allow_html=True,
      )

    with k4:
      st.markdown(
          f"""
                <div class="kpi-card">
                    <div class="kpi-label">Plato Estrella</div>
                    <div class="kpi-val amber-glow" style="font-size: 1.3rem;">{plato_top}</div>
                </div>
            """,
          unsafe_allow_html=True,
      )

    st.markdown("<br>", unsafe_allow_html=True)

    # --- PESTAÑAS PRINCIPALES ---
    tab1, tab2, tab3 = st.tabs([
        "📊 Performance de Ventas",
        "🔥 Control Operativo",
        "🤖 Red Neuronal & Predicciones",
    ])

    # TAB 1: PERFORMANCE DE VENTAS
    with tab1:
      col1, col2 = st.columns(2)

      with col1:
        st.markdown("#### 📈 Evolución Diaria de Ventas")
        ventas_diarias = df_f.groupby("fecha")["total"].sum().reset_index()
        st.line_chart(ventas_diarias, x="fecha", y="total", color="#38bdf8")

      with col2:
        st.markdown("#### 🍰 Ventas por Categoría")
        ventas_cat = df_f.groupby("categoria")["total"].sum().reset_index()
        st.bar_chart(ventas_cat, x="categoria", y="total", color="#818cf8")

      st.markdown("#### 🏆 Ranking Estratégico de Platos")
      top_tabla = (
          df_f.groupby(["categoria", "producto"])
          .agg(Vendidos=("cantidad", "sum"), Recaudacion=("total", "sum"))
          .reset_index()
          .sort_values(by="Recaudacion", ascending=False)
      )

      top_tabla["Recaudacion"] = top_tabla["Recaudacion"].apply(
          lambda x: f"S/ {x:,.2f}"
      )
      st.dataframe(top_tabla, use_container_width=True, hide_index=True)

    # TAB 2: OPERACIONAL Y INVENTARIOS
    with tab2:
      co1, co2 = st.columns(2)

      with co1:
        st.markdown("#### ⏰ Mapa de Calor de Pedidos (Horas Pico)")
        ventas_hora = (
            df_f.groupby("hora")["pedido_id"].nunique().reset_index()
        )
        ventas_hora.columns = ["Hora", "Pedidos Atendidos"]
        st.bar_chart(
            ventas_hora, x="Hora", y="Pedidos Atendidos", color="#fbbf24"
        )

      with co2:
        st.markdown("#### 📦 Estado de Insumos e Inventario")
        if not df_inv.empty:
          df_inv["Estado Stock"] = np.where(
              df_inv["stock"] <= df_inv["stock_minimo"],
              "🚨 Crítico",
              "🟢 Abastecido",
          )
          st.dataframe(df_inv, use_container_width=True, hide_index=True)
        else:
          st.info("Sin registros de inventario disponibles.")

    # TAB 3: PREDICCIONES IA AVANZADAS
    with tab3:
      st.markdown(
          "#### 🤖 Algoritmos de Aprendizaje Automático e Inferencia"
      )

      p_col1, p_col2 = st.columns(2)

      with p_col1:
        st.markdown("##### 🔮 Proyección Global de Ingresos")
        
        # Agrupar datos diarios globales
        df_diario = df.groupby("fecha")["total"].sum().reset_index()
        num_dias = len(df_diario)
        y_max = float(df_diario["total"].max()) if num_dias > 0 and df_diario["total"].max() > 0 else 1.0
        max_dia = float(num_dias) if num_dias > 0 else 1.0

        # Algoritmo 1: Sklearn Linear Regression
        if os.path.exists(sklearn_path):
          try:
            proximo_dia_sk = pd.DataFrame({"dia_num": [num_dias + 1]})
            m_sk = joblib.load(sklearn_path)
            p_sk = m_sk.predict(proximo_dia_sk)[0]
            st.success(
                f"**Linear Regression (scikit-learn):** S/ {p_sk:,.2f} para"
                " mañana"
            )
          except Exception as sk_err:
            st.error(f"Error en Scikit-Learn: {sk_err}")

        # Algoritmo 2: TensorFlow Neural Network
        if os.path.exists(tf_path):
          try:
            proximo_dia_tf = np.array([[(num_dias + 1) / max_dia]], dtype=np.float32)
            m_tf = tf.keras.models.load_model(tf_path)
            p_tf_raw = m_tf.predict(proximo_dia_tf, verbose=0)[0][0]
            
            # Desnormalización adaptativa según escala de salida del modelo
            p_tf = float(p_tf_raw * y_max) if p_tf_raw <= 2.0 else float(p_tf_raw)
            p_tf = max(0.0, p_tf)
            
            st.info(
                f"**Red Neuronal Densa (TensorFlow):** S/ {p_tf:,.2f} para mañana"
            )
          except Exception as tf_err:
            st.error(f"Error en TensorFlow: {tf_err}")

        # Algoritmo 3: PyTorch Deep Learning Model
        if os.path.exists(pytorch_path):
          try:
            proximo_dia_pt = torch.tensor(
                [[(num_dias + 1) / max_dia]], dtype=torch.float32
            )

            m_pt = VentasRedNeuronal()
            m_pt.load_state_dict(
                torch.load(pytorch_path, weights_only=True)
            )
            m_pt.eval()

            with torch.no_grad():
              p_pt_raw = m_pt(proximo_dia_pt).item()
              p_pt = float(p_pt_raw * y_max) if p_pt_raw <= 2.0 else float(p_pt_raw)
              p_pt = max(0.0, p_pt)

            st.warning(
                f"**Red Neuronal Deep Learning (PyTorch):** S/ {p_pt:,.2f} para"
                " mañana"
            )
          except Exception as pt_err:
            st.error(f"Error al cargar PyTorch: {pt_err}")

      with p_col2:
        st.markdown(
            "##### 🎯 Predicción de Demanda de Insumos por Plato"
        )
        prod_target = st.selectbox(
            "Seleccionar Plato:", df["producto"].unique()
        )

        df_prod = df[df["producto"] == prod_target].groupby("fecha")[
            "cantidad"
        ].sum()
        promedio_diario = df_prod.mean() if not df_prod.empty else 0

        st.metric(
            label=f"Proyección de Preparación Diaria: {prod_target}",
            value=f"{int(np.ceil(promedio_diario))} Porciones",
            delta="Sugerencia de Producción para Cocina",
        )

except Exception as e:
  st.error(f"❌ Ocurrió un error al cargar la plataforma analytics: {e}")