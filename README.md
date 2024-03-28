# App-Crawler

Bem-vindo ao App-Crawler! Este é um sistema desenvolvido em Laravel que possibilita realizar um crawler, nesse caso uma página da Wikipedia.

## Funcionalidades Principais

- **Busca de dados:** Os usuários podem realizar buscas de dados sobre moedas diversas.
   
- **Crawler:** Realização de web crawler de página com informações de moedas, com base no padrão internacional que define códigos de três letras para as moedas.

- **Registro de Informações:** Após busca as informações são registradas para posterior consulta.

## Requisitos

- PHP >= 8.1.27
- Composer
- Laravel Framework v.10

## Instalação

1. Clone este repositório: `git clone https://github.com/rafaeldoria/docker-laravel.git`
2. Acesse o diretório do docker: `cd docker-laravel`
3. Copie o arquivo `.env.example` para `.env`: `cp .env.example .env`
4. Configure as variáveis de ambiente no arquivo `.env`, especialmente a conexão com o banco de dados. 
5. Acesse o diretório do docker: `cd app-crawler`
6. Clone o código fonto do projeto de transações: ` git clone https://github.com/rafaeldoria/app_transactions.git . `
7. Suba os containers: `docker-compose up -d` 
8. Instale as dependências do Composer: `docker exec app-php composer install`
9. Copie o arquivo `.env.example` para `.env`: `docker exec app-php cp .env.example .env`
10. Configure as variáveis de ambiente no arquivo `.env`, especialmente a conexão com o banco de dados (as mesmas do env do passo 3).
11. Gere a chave de aplicativo: `docker exec app-php php artisan key:generate`
12. Execute as migrações do banco de dados: `docker exec app-php php artisan migrate --seed`
13. Acesse: `http://localhost:8180/api`

## Testes

docker exec app-php php artisan test --colors=always
