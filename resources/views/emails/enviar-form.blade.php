<!DOCTYPE html>
<html>
<head>
    <title>Novo Formulário</title>
    <style>
        .button {
            background-color: #eab308;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <h1>Um novo formulário foi criado: {{ $titulo }}</h1>
    
    <p>Você recebeu este e-mail porque foi adicionado como destinatário.</p>
    
    <p>Para responder, clique no botão abaixo:</p>
    
    <p>
        <a href="{{ $url }}" class="button">Responder Formulário</a>
    </p>

    <p>Link logo abaixo caso queira divulgar ou abrir pelo link: <br> {{ $url }}</p>
</body>
</html>