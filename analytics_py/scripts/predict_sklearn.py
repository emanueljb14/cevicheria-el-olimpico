import pandas as pd
import numpy as np
import os
import joblib
from sklearn.linear_model import LinearRegression
from sqlalchemy import create_engine

# Configuración de rutas y conexión a MySQL
BASE_DIR = os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
model_path = os.path.join(BASE_DIR, "analytics_py", "models", "model_sklearn.pkl")

DB_URI = "mysql+mysqlconnector://root:@127.0.0.1:3306/el_olimpo"

def entrenar_sklearn():
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
            print("Aviso: No hay registros de ventas para entrenar el modelo.")
            return

        # 2. Agrupar ventas diarias
        df['fecha'] = pd.to_datetime(df['fecha']).dt.date
        ventas_diarias = df.groupby('fecha')['total'].sum().reset_index()
        ventas_diarias['dia_num'] = np.arange(len(ventas_diarias))

        # 3. Definir variables de entrada (X) y objetivo (y)
        X = ventas_diarias[['dia_num']]
        y = ventas_diarias['total']

        # 4. Entrenar el modelo de Regresión Lineal
        model = LinearRegression()
        model.fit(X, y)

        # 5. Guardar el binario .pkl
        os.makedirs(os.path.dirname(model_path), exist_ok=True)
        joblib.dump(model, model_path)
        print("=== SCIKIT-LEARN ===")
        print(f"Modelo guardado exitosamente en: {model_path}")

        # 6. Generar prueba de predicción
        proximo_dia = pd.DataFrame({'dia_num': [len(ventas_diarias)]})
        prediccion = model.predict(proximo_dia)[0]
        print(f"Proyección de ventas para el próximo día: S/ {prediccion:.2f}\n")

    except Exception as e:
        print(f"Error al entrenar el modelo Scikit-Learn: {e}")

if __name__ == "__main__":
    entrenar_sklearn()