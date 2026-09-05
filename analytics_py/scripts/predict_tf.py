import os
# Silenciar avisos informativos y de compilación de TensorFlow
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '2'

import pandas as pd
import numpy as np
import tensorflow as tf
from sqlalchemy import create_engine

# Configuración de rutas y conexión a MySQL
BASE_DIR = os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
model_path = os.path.join(BASE_DIR, "analytics_py", "models", "model_tf.keras")

DB_URI = "mysql+mysqlconnector://root:@127.0.0.1:3306/el_olimpo"

def entrenar_tf():
    try:
        # 1. Cargar datos en vivo desde MySQL
        engine = create_engine(DB_URI)
        query = """
            SELECT 
                p.created_at AS fecha,
                dp.subtotal AS total
            FROM detalle_pedidos dp
            JOIN pedidos p ON dp.pedido_id = p.id
        """
        df = pd.read_sql(query, engine)

        if df.empty:
            print("Aviso: No hay registros de ventas para entrenar la red neuronal.")
            return

        # 2. Agrupar ventas diarias
        df['fecha'] = pd.to_datetime(df['fecha']).dt.date
        ventas_diarias = df.groupby('fecha')['total'].sum().reset_index()
        ventas_diarias['dia_num'] = np.arange(len(ventas_diarias), dtype=np.float32)

        X = ventas_diarias[['dia_num']].values
        y = ventas_diarias['total'].values.astype(np.float32)

        # Normalización simple (Escalado por el máximo día para ayudar a la convergencia)
        max_dia = float(len(ventas_diarias)) if len(ventas_diarias) > 0 else 1.0
        X_scaled = X / max_dia

        # 3. Estructura de la red neuronal con Normalización y Capa ReLU de salida
        model = tf.keras.Sequential([
            tf.keras.layers.Input(shape=(1,)),
            tf.keras.layers.Dense(units=16, activation='relu'),
            tf.keras.layers.Dense(units=8, activation='relu'),
            tf.keras.layers.Dense(units=1, activation='relu')  # ReLU previene predicciones negativas (< 0)
        ])

        # Compilar con learning rate ajustado
        optimizer = tf.keras.optimizers.Adam(learning_rate=0.01)
        model.compile(optimizer=optimizer, loss='mean_squared_error')

        # Entrenamiento con más épocas para mejor aprendizaje
        model.fit(X_scaled, y, epochs=300, verbose=0)

        # 4. Guardar archivo de red neuronal (.keras)
        os.makedirs(os.path.dirname(model_path), exist_ok=True)
        model.save(model_path)
        print("=== TENSORFLOW ===")
        print(f"Modelo guardado exitosamente en: {model_path}")

        # 5. Prueba de inferencia (Próximo día escalado)
        proximo_dia_scaled = np.array([[len(ventas_diarias) / max_dia]], dtype=np.float32)
        prediccion = model.predict(proximo_dia_scaled, verbose=0)[0][0]
        print(f"Proyección de ventas para el próximo día: S/ {prediccion:.2f}\n")

    except Exception as e:
        print(f"Error al entrenar la red neuronal TensorFlow: {e}")

if __name__ == "__main__":
    entrenar_tf()