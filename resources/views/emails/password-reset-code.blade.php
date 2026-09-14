<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de verificación</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f4f4f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 480px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }
        .logo {
            font-size: 22px;
            font-weight: 800;
            color: #3a0ca3;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }
        p {
            color: #52525b;
            font-size: 15px;
            line-height: 1.6;
        }
        .code-box {
            background: #eef2ff;
            border: 2px dashed #6366f1;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            font-size: 34px;
            font-weight: 700;
            letter-spacing: 12px;
            color: #1e1b4b;
            margin: 24px 0;
        }
        .hint {
            font-size: 13px;
            color: #a1a1aa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">SCAPE</div>
        <p>Hola,</p>
        <p>Recibimos una solicitud para restablecer tu contraseña. Usa el siguiente código de verificación:</p>

        <div class="code-box">{{ $code }}</div>

        <p>Este código es válido por <strong>60 minutos</strong>. Si no solicitaste este cambio, ignora este correo.</p>

        <p class="hint">SCAPE — Control de Acceso Inteligente</p>
    </div>
</body>
</html>