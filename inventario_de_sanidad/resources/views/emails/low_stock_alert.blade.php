<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Alerta de Stock Bajo</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f4;font-family:Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="min-width:100%;">
    <tr>
      <td align="center">
        <table 
          cellpadding="0" 
          cellspacing="0" 
          style="
            background-color:#ffffff;
            margin:20px auto;
            border-radius:8px;
            overflow:hidden;
            box-shadow:0 2px 4px rgba(0,0,0,0.1);
            width:100%;
            max-width:600px;
          ">
          <!-- Header -->
          <tr>
            <td style="background-color:#d9534f;color:#ffffff;padding:16px;text-align:center;">
              <h1 style="margin:0;font-size:20px;line-height:1.2;">⚠️ Alerta: Stock Bajo</h1>
            </td>
          </tr>
          <!-- Body -->
          <tr>
            <td style="padding:16px;color:#333333;line-height:1.5;font-size:16px;">
              <p style="margin:0 0 12px;">
                El material <strong>{{ $materialName }}</strong> tiene una cantidad actual de 
                <strong>{{ $storage->units }}</strong>, que está por debajo del mínimo permitido 
                (<strong>{{ $storage->min_units }}</strong>) en el almacenamiento de 
                <strong>{{ $storage->storage_type === 'use' ? 'uso' : 'reserva' }}</strong>
                en el almacen de <strong>{{ $storage->storage === 'odontology' ? 'Odontología' : 'CAE' }}</strong>.
              </p>
              <p style="margin:0 0 12px;">
                Por favor, toma las medidas necesarias para reponer el stock.
              </p>
              <hr style="border:none;border-top:1px solid #e0e0e0;margin:16px 0;">
              <p style="margin:0;font-size:12px;color:#777777;">
                Este es un mensaje automático. No responda a este correo.
              </p>
            </td>
          </tr>
          <!-- Footer -->
          <tr>
            <td style="background-color:#f9f9f9;color:#777777;padding:12px;text-align:center;font-size:12px;">
              Portal del Departamento de Sanidad
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
