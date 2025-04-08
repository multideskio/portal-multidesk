# Use a imagem PHP 8.2 com Apache
FROM php:8.2-apache

# Atualizar pacotes e instalar dependências
# <-- necessário para PostgreSQL
RUN apt-get update && apt-get install -y --no-install-recommends \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    libpng-dev \
    libgd-dev \
    libfreetype6-dev \
    libjpeg-dev \
    libwebp-dev \
    libmagickwand-dev \
    libpq-dev \
    tzdata && \
    rm -rf /var/lib/apt/lists/*

# Configurar o timezone da máquina para horário de Brasília
RUN ln -sf /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime && \
    echo "America/Sao_Paulo" > /etc/timezone

# Instalação do Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configurar e instalar a extensão GD com suporte a freetype, jpeg e webp
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd

# Instalar outras extensões PHP
RUN docker-php-ext-install -j$(nproc) intl pdo_mysql zip mysqli \
    && docker-php-ext-install -j$(nproc) pdo_pgsql pgsql  # <-- adiciona PostgreSQL

# Instalação do Redis
RUN pecl install redis && docker-php-ext-enable redis

# Configurar o Git
RUN git config --global --add safe.directory /var/www/html

# Copiar o código do projeto
COPY . /var/www/html

# Habilitar mod_rewrite do Apache
RUN a2enmod rewrite

# Configurar o Apache para usar o diretório /var/www/html/Public como DocumentRoot
RUN sed -i 's|/var/www/html|/var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Definir o ServerName
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Criar e copiar o php.ini personalizado
COPY php.ini /usr/local/etc/php/conf.d/php.ini

# Definir diretório de trabalho
WORKDIR /var/www/html

# Dar permissões de escrita ao diretório "writable"
RUN chmod -R 775 /var/www/html/writable /var/www/html/public/assets && \
    chown -R www-data:www-data /var/www/html/writable /var/www/html/public/assets

# Copiar o entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expor as portas necessárias
EXPOSE 80
EXPOSE 8080

# Configurar o script de entrada
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
