import pandas as pd
import numpy as np
import os
import joblib
from sklearn.linear_model import LinearRegression

BASE_DIR = os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
csv_path = os.path.join(BASE_DIR, "storage", "app", "exports", "ventas.csv")
model_path = os.path.join(BASE_DIR, "analytics_py", "models", "model_sklearn.pkl")

def entrenar_sklearn():
    if not os.path.exists(csv_path):
        print("Error: No se encontró el archivo ventas.csv")
        return

    df = pd.read_csv(csv_path)
    df['fecha'] = pd.to_datetime(df['fecha']).dt.date
    
    ventas_diarias = df.groupby('fecha')['total'].sum().reset_index()
    ventas_diarias['dia_num'] = np.arange(len(ventas_diarias))

    # Definir variables de entrada y objetivo
    X = ventas_diarias[['dia_num']]
    y = ventas_diarias['total']

    # Entrenar el modelo
    model = LinearRegression()
    model.fit(X, y)

    # Guardar el modelo entrenado
    joblib.dump(model, model_path)
    print("=== SCIKIT-LEARN ===")
    print(f"Modelo guardado exitosamente en: {model_path}")
    
    # Predecir con un DataFrame para mantener consistencia de columnas y evitar warnings
    proximo_dia = pd.DataFrame({'dia_num': [len(ventas_diarias)]})
    prediccion = model.predict(proximo_dia)[0]
    print(f"Proyección de ventas para el próximo día: S/ {prediccion:.2f}\n")

if __name__ == "__main__":
    entrenar_sklearn()