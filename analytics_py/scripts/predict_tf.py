import os
# Silenciar avisos informativos y de compilación de TensorFlow (AVX, GPU, etc.)
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '2'

import pandas as pd
import numpy as np
import tensorflow as tf

BASE_DIR = os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
csv_path = os.path.join(BASE_DIR, "storage", "app", "exports", "ventas.csv")
model_path = os.path.join(BASE_DIR, "analytics_py", "models", "model_tf.keras")

def entrenar_tf():
    if not os.path.exists(csv_path):
        print("Error: No se encontró el archivo ventas.csv")
        return

    df = pd.read_csv(csv_path)
    df['fecha'] = pd.to_datetime(df['fecha']).dt.date
    
    ventas_diarias = df.groupby('fecha')['total'].sum().reset_index()
    ventas_diarias['dia_num'] = np.arange(len(ventas_diarias))

    X = ventas_diarias[['dia_num']].values
    y = ventas_diarias['total'].values

    # Uso explícito de Input() para evitar la advertencia de input_shape en Dense()
    model = tf.keras.Sequential([
        tf.keras.layers.Input(shape=(1,)),
        tf.keras.layers.Dense(units=8, activation='relu'),
        tf.keras.layers.Dense(units=1)
    ])

    model.compile(optimizer='adam', loss='mean_squared_error')
    model.fit(X, y, epochs=150, verbose=0)

    # Guardar modelo de red neuronal
    model.save(model_path)
    print("=== TENSORFLOW ===")
    print(f"Modelo guardado exitosamente en: {model_path}")

    proximo_dia = np.array([[len(ventas_diarias)]])
    prediccion = model.predict(proximo_dia, verbose=0)[0][0]
    print(f"Proyección de ventas para el próximo día: S/ {prediccion:.2f}\n")

if __name__ == "__main__":
    entrenar_tf()