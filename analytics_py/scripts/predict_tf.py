import os

# 1. Silenciar todos los avisos de TensorFlow/oneDNN
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '3'
os.environ['TF_ENABLE_ONEDNN_OPTS'] = '0'

import numpy as np
import pandas as pd
import tensorflow as tf
from sqlalchemy import create_engine

# Configuración de rutas y conexión a MySQL
BASE_DIR = os.path.dirname(
    os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
)
model_path = os.path.join(BASE_DIR, "analytics_py", "models", "model_tf.keras")
DB_URI = "mysql+mysqlconnector://root:@127.0.0.1:3306/el_olimpo"


def entrenar_tf():
  try:
    engine = create_engine(DB_URI)
    query = """
            SELECT 
                p.created_at AS fecha,
                dp.subtotal AS total
            FROM detalle_pedidos dp
            JOIN pedidos p ON dp.pedido_id = p.id
            WHERE p.estado != 'cancelado'
        """
    df = pd.read_sql(query, engine)

    if df.empty:
      print(
          "Aviso: No hay registros de ventas para entrenar la red neuronal."
      )
      return

    df['fecha'] = pd.to_datetime(df['fecha']).dt.date
    ventas_diarias = df.groupby('fecha')['total'].sum().reset_index()
    ventas_diarias['dia_num'] = np.arange(
        1, len(ventas_diarias) + 1, dtype=np.float32
    )

    X = ventas_diarias[['dia_num']].values
    y = ventas_diarias[['total']].values.astype(np.float32)

    x_max = float(X.max()) if X.max() > 0 else 1.0
    y_max = float(y.max()) if y.max() > 0 else 1.0

    X_scaled = X / x_max
    y_scaled = y / y_max

    model = tf.keras.Sequential([
        tf.keras.layers.Input(shape=(1,)),
        tf.keras.layers.Dense(units=16, activation='relu'),
        tf.keras.layers.Dense(units=8, activation='relu'),
        tf.keras.layers.Dense(units=1, activation='linear'),
    ])

    optimizer = tf.keras.optimizers.Adam(learning_rate=0.01)
    model.compile(optimizer=optimizer, loss='mean_squared_error')
    model.fit(X_scaled, y_scaled, epochs=400, verbose=0)

    os.makedirs(os.path.dirname(model_path), exist_ok=True)
    model.save(model_path)

    proximo_dia = len(ventas_diarias) + 1
    proximo_dia_scaled = np.array([[proximo_dia / x_max]], dtype=np.float32)

    prediccion_scaled = model.predict(proximo_dia_scaled, verbose=0)[0][0]
    prediccion_final = max(0.0, float(prediccion_scaled * y_max))

    print("=== TENSORFLOW ===")
    print(f"Modelo guardado en: {model_path}")
    print(
        f"Proyección de ventas próximo día (Día {proximo_dia}): S/"
        f" {prediccion_final:.2f}\n"
    )

  except Exception as e:
    print(f"Error al entrenar la red neuronal TensorFlow: {e}")


if __name__ == '__main__':
  entrenar_tf()