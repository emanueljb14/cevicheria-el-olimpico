import pandas as pd
import numpy as np
import os

# Determina la ruta raíz del proyecto (un nivel arriba de analytics_py/scripts)
BASE_DIR = os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
csv_path = os.path.join(BASE_DIR, "storage", "app", "exports", "ventas.csv")

def analizar_datos():
    if not os.path.exists(csv_path):
        print(f"Error: No se encontró el archivo en {csv_path}")
        return

    # 1. Cargar datos con Pandas
    df = pd.read_csv(csv_path)
    print("=== VISTA PREVIA DE DATOS (PANDAS) ===")
    print(df.head())
    print("\n" + "="*40 + "\n")

    # 2. Cálculos Estadísticos (NumPy & Pandas)
    ventas_totales = df['total'].values  # Arreglo NumPy
    
    mediana_ventas = np.median(ventas_totales)
    desviacion_std = np.std(ventas_totales)
    promedio_ventas = np.mean(ventas_totales)

    print("=== ESTADÍSTICAS DE VENTAS ===")
    print(f"Total de registros: {len(df)}")
    print(f"Promedio por ítem vendido: S/ {promedio_ventas:.2f}")
    print(f"Mediana de ventas: S/ {mediana_ventas:.2f}")
    print(f"Desviación Estándar (Dispersión): S/ {desviacion_std:.2f}")
    print("\n" + "="*40 + "\n")

    # 3. Comparación de Productos
    comparativa = df.groupby('producto').agg(
        cantidad_vendida=('cantidad', 'sum'),
        ingresos_totales=('total', 'sum')
    ).sort_values(by='cantidad_vendida', ascending=False)

    print("=== COMPARATIVA DE PRODUCTOS MÁS VENDIDOS ===")
    print(comparativa)

if __name__ == "__main__":
    analizar_datos()