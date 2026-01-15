<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Bem-vindo(a)</title>
</head>
<body style="font-family: Arial, sans-serif; color: #0f172a; line-height: 1.5;">
    <h1 style="color:#0f172a;">Bem-vindo(a) à EduX!</h1>
    <p>Olá {{ $user->preferredName() }},</p>
    <p>Seu acesso foi criado com sucesso. Use as credenciais abaixo para entrar:</p>
    <ul>
        <li><strong>Link:</strong> <a href="{{ $loginUrl }}">{{ $loginUrl }}</a></li>
        <li><strong>E-mail:</strong> {{ $user->email }}</li>
        <li><strong>Senha:</strong> {{ $plainPassword }}</li>
    </ul>
    <p>Recomendamos alterar a senha após o primeiro login.</p>
    <p>Bom aprendizado!</p>
</body>
</html>
