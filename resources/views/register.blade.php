<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; background-color: #002025;">

    <div class="container" style="text-align: center; max-width: 800px; padding: 200px 20px 200px 20px; margin: auto; background-color: #002025">

        <img src="{{ url('images/logo.png') }}" alt="Imagem" class="image" style="max-width: 100%; height: auto; border-radius: 5px; margin-bottom: 15px;">

        <div class="subtitle" style="font-size: 32px; color: #FFF; margin-bottom: 10px;">Seu acesso <span style="color: #F0FE2F;">chegou.</span></div>

        <div class="text" style="font-size: 14px; color: #FFF; margin-bottom: 10px; width: 50%; margin: auto; margin-bottom: 10px;">Olá! Que bom te ver por aqui!</div>

        <div class="text" style="font-size: 14px; color: #FFF; margin-bottom: 10px; width: 50%; margin: auto; margin-bottom: 20px;">Seu acesso à Cille acaba de chegar!</div>

        <a href="https://app.cille.io/solicitar-conta?token={{ $token }}" target="_blank" style="text-decoration: none;">

            <button class="button" style="background-color: #F0FE2F; color: #002025; padding: 12px 16px 12px 16px; border: none; border-radius: 5px; font-size: 14px; cursor: pointer; font-weight: bold; width: 310px;">Acessar agora!</button>

        </a>

    </div>

</body>
</html>
