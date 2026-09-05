import pandas as pd
import numpy as np
from sqlalchemy import create_engine

# Conexión directa a la base de datos MySQL (el_olimpo)
DB_URI = "mysql+mysqlconnector://root:@127.0.0.1:3306/el_olimpo"

def analizar_datos():
    try:
        # 1. Cargar datos en vivo desde MySQL con SQLAlchemy y Pandas
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
        df = pd.read_sql(query, engine)

        if df.empty:
            print("Aviso: No hay registros de ventas en la base de datos.")
            return

        print("=== VISTA PREVIA DE DATOS (MYSQL) ===")
        print(df.head())
        print("\n" + "="*40 + "\n")

        # 2. Cálculos Estadísticos (NumPy & Pandas)
        ventas_totales = df['total'].values  # Arreglo NumPy
        
        mediana_ventas = np.median(ventas_totales)
        desviacion_std = np.std(ventas_totales)
        promedio_ventas = np.mean(ventas_totales)

        print("=== ESTADÍSTICAS DE VENTAS EN VIVO ===")
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

    except Exception as e:
        print(f"Error al conectar con la base de datos MySQL: {e}")

if __name__ == "__main__":
    analizar_datos()