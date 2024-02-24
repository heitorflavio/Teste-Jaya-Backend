<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Executando o Projeto

### Utilizando Docker (Ubuntu)

1. Certifique-se de ter o Docker instalado. Se não tiver, siga as [instruções de instalação do Docker no Ubuntu 20.04](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-20-04).

2. Clone o projeto do repositório:

    ```bash
    git clone https://github.com/heitorflavio/Teste-Jaya-Backend.git
    ```

3. Navegue até o diretório do projeto:

    ```bash
    cd Teste-Jaya-Backend
    ```

4. Instale as dependências do projeto usando o Composer:

    ```bash
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php83-composer:latest \
        composer install --ignore-platform-reqs
    ```

5. Inicie o arquivo de ambiente (`.env`):

    ```bash
    cp .env.example .env
    ```

6. Inicie o projeto usando o Docker:

    ```bash
    docker compose up --build
    ```

7. Após iniciar o projeto, gere a chave do aplicativo:

    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

8. Se as migrações e seeds não iniciarem automaticamente, execute:

    ```bash
    ./vendor/bin/sail artisan migrate
    ```

    Em seguida:

    ```bash
    ./vendor/bin/sail artisan db:seed
    ```

### Utilizando PHP (Ubuntu)

1. Instale o PHP, MySQL e o Composer. Você pode encontrar instruções nos seguintes links:

   - PHP: [Instruções de Instalação PHP 8.3 no Ubuntu 22.04](https://www.linuxtuto.com/how-to-install-php-8-3-on-ubuntu-22-04/)
   - Composer: [Download Composer](https://getcomposer.org/download/)
   - MySQL: [Instruções de Instalação MySQL no Ubuntu 20.04](https://www.digitalocean.com/community/tutorials/how-to-install-mysql-on-ubuntu-20-04)

2. Clone o projeto do repositório:

    ```bash
    git clone https://github.com/heitorflavio/Teste-Jaya-Backend.git
    ```

3. Navegue até o diretório do projeto:

    ```bash
    cd Teste-Jaya-Backend
    ```

4. Instale as dependências do projeto usando o Composer:

    ```bash
    composer install
    ```

5. Inicie o arquivo de ambiente (`.env`):

    ```bash
    cp .env.example .env
    ```

6. Configure as informações do banco de dados dentro do arquivo `.env`.

7. Gere a chave do aplicativo:

    ```bash
    php artisan key:generate
    ```

8. Execute as migrações e as seeds do banco de dados:

    ```bash
    php artisan migrate --seed
    ```

9. Inicie o servidor:

    ```bash
    php artisan serve
    ```

## Acessando a Documentação do Swagger

Para acessar a documentação do Swagger, siga os exemplos abaixo:

- Acesse a rota `domain/api/documentation#/Payments`:

    [http://localhost/api/documentation](http://localhost/api/documentation)

  ou

    [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)