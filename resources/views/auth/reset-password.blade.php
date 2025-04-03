<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Redefinir Senha</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
  <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Redefinir Senha</h1>

    <form id="resetForm" method="POST" action="{{ route('resetPassword') }}">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">

      <div class="mb-4">
        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">E-mail</label>
        <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed">
      </div>

      <div class="mb-4">
        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Nova Senha</label>
        <input type="password" id="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div class="mb-6">
        <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirmar Nova Senha</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        Redefinir Senha
      </button>
    </form>
  </div>

  <script>
    document.getElementById('resetForm').addEventListener('submit', async function(e) {
      e.preventDefault();

      const form = e.target;
      const formData = new FormData(form);
      const button = form.querySelector('button[type="submit"]');

      button.disabled = true;
      button.textContent = 'Processando...';

      try {
        const response = await fetch(form.action, {
          method: 'POST'
          , body: formData
          , headers: {
            'Accept': 'application/json'
          }
        });

        const data = await response.json();

        if (!response.ok) {
          throw new Error(data.message || 'Erro ao redefinir senha');
        }

        alert(data.message);

        form.querySelector('#password').value = '';
        form.querySelector('#password_confirmation').value = '';

      } catch (error) {
        alert(error.message);
      } finally {
        button.disabled = false;
        button.textContent = 'Redefinir Senha';
      }
    });

  </script>
</body>
</html>
