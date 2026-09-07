import os
import numpy as np
import pandas as pd
from sqlalchemy import create_engine
import torch
import torch.nn as nn

# Configuración de rutas
BASE_DIR = os.path.dirname(
    os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
)
model_path = os.path.join(
    BASE_DIR, "analytics_py", "models", "model_pytorch.pth"
)
DB_URI = "mysql+mysqlconnector://root:@127.0.0.1:3306/el_olimpo"


# Definición de la Red Neuronal
class VentasRedNeuronal(nn.Module):

  def __init__(self):
    super(VentasRedNeuronal, self).__init__()
    self.capas = nn.Sequential(
        nn.Linear(1, 16),
        nn.ReLU(),
        nn.Linear(16, 8),
        nn.ReLU(),
        nn.Linear(8, 1),
        nn.ReLU(),
    )

  def forward(self, x):
    return self.capas(x)


def entrenar_pytorch():
  try:
    engine = create_engine(DB_URI)
    query = """
            SELECT p.created_at AS fecha, dp.subtotal AS total
            FROM detalle_pedidos dp
            JOIN pedidos p ON dp.pedido_id = p.id
        """
    df = pd.read_sql(query, engine)

    if df.empty:
      print("No hay registros para entrenar.")
      return

    df["fecha"] = pd.to_datetime(df["fecha"]).dt.date
    ventas_diarias = df.groupby("fecha")["total"].sum().reset_index()
    ventas_diarias["dia_num"] = np.arange(
        len(ventas_diarias), dtype=np.float32
    )

    X = ventas_diarias[["dia_num"]].values
    y = ventas_diarias[["total"]].values.astype(np.float32)

    max_dia = float(len(ventas_diarias)) if len(ventas_diarias) > 0 else 1.0
    X_scaled = X / max_dia

    X_tensor = torch.tensor(X_scaled, dtype=torch.float32)
    y_tensor = torch.tensor(y, dtype=torch.float32)

    model = VentasRedNeuronal()
    criterion = nn.MSELoss()
    optimizer = torch.optim.Adam(model.parameters(), lr=0.01)

    model.train()
    for epoch in range(300):
      optimizer.zero_grad()
      predictions = model(X_tensor)
      loss = criterion(predictions, y_tensor)
      loss.backward()
      optimizer.step()

    os.makedirs(os.path.dirname(model_path), exist_ok=True)
    torch.save(model.state_dict(), model_path)
    print("=== PYTORCH ===")
    print(f"Modelo guardado en: {model_path}")

    model.eval()
    with torch.no_grad():
      proximo_dia = torch.tensor(
          [[len(ventas_diarias) / max_dia]], dtype=torch.float32
      )
      prediccion = model(proximo_dia).item()

    print(f"Proyección próximo día: S/ {prediccion:.2f}\n")

  except Exception as e:
    print(f"Error en PyTorch: {e}")


if __name__ == "__main__":
  entrenar_pytorch()