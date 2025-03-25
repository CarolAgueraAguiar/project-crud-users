@component('mail::message')
# Olá {{ $user->name }}!

Você está recebendo este email porque recebemos uma solicitação de redefinição de senha para sua conta.

@component('mail::button', ['url' => $resetUrl])
Redefinir Senha
@endcomponent

Se você não solicitou uma redefinição de senha, nenhuma ação adicional é necessária.

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
