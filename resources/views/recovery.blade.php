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

        <div class="subtitle" style="font-size: 32px; color: #FFF; margin-bottom: 10px;">Olá! Vamos recuperar seu acesso?</span></div>

        <div class="text" style="font-size: 14px; color: #FFF; margin-bottom: 10px; width: 50%; margin: auto; margin-bottom: 10px;">Seu Token de recuperação de acesso é:</div>

        <div class="text" style="font-size: 28px; color: #FFF; margin-bottom: 10px; width: 50%; margin: auto; margin-bottom: 20px;">{{ $code }}</div>

    </div>

</body>
</html>
