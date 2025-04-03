
# 📱 CRUD de usuários e envio de e-mail para redefinição de senha.

## 🧩 Tecnologias Utilizadas

- **Laravel** (PHP)
- **Blade** (Template Engine)
- **Docker** (Containers)
- **Queues** (Filas)
- **MailHog** (Serviço de e-mail local)
- **Makefile** (Automatização de tarefas)

## 🌟 Pré-requisitos

Certifique-se de ter o seguinte software instalado antes de começar:

- [Docker](https://www.docker.com/)
- [Make](https://www.gnu.org/software/make/)
- [PHP](https://www.php.net/)
- [Composer](https://getcomposer.org/)


## 🚀 Como Iniciar

Para iniciar o projeto, siga as etapas abaixo:

1. Certifique-se de que você tenha o Docker e o Make instalados em seu sistema.

2. Clone este repositório:

```
git clone https://github.com/CarolAgueraAguiar/project-crud-users
```

3.Navegue até o diretório do projeto:

```
cd project-crud-users
```

4.Inicie o container Docker:

```
make up
```

5.Inicie o servidor de desenvolvimento:

```
make composer-install
```

6.Execute as migrations do banco de dados:

```
make migrate
```

7.Inicie o serviço de filas com Horizon:
```
make horizon
```

8.Acesse a aplicação via postman/insomnia (ou outro), para fazer as requests.

## 📋 Estrutura do Projeto

Explicação da estrutura de pastas e arquivos do projeto.

```
src/  
  ├── Application/         # Onde ficam as portas de entrada da aplicação, requests, controllers...
  ├── Domain/              # Contém as regras de negócio, comunicação com banco, models, jobs, repositories, use cases...
  └── Support/             # Tratamento de erros, comunicação com serviços externos como ViaCep, etc.
```

## 🔧 Scripts Disponíveis
No diretório do projeto, você pode usar os seguintes comandos com o Makefile:

- make up: Inicializa o container Docker.
- make composer-install: Instala as dependências do projeto.
- make migrate: Executa as migrations do banco de dados.
- make horizon: Inicia o Horizon (gerenciador de filas do Laravel).

## 📩 Teste de E-mail Local

```
Para testar a funcionalidade de e-mails localmente, você pode usar o MailHog. O serviço está configurado para capturar os e-mails enviados pela aplicação sem precisar de um servidor de e-mail real. Basta acessar o painel do MailHog no navegador.

No endereço -> http://localhost:8025/
```
