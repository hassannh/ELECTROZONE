FROM dunglas/frankenphp:php8.2-bookworm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl unzip zip libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions including pdo_mysql
RUN install-php-extensions \
    pdo_mysql \
    mysqli \
    ctype \
    curl \
    dom \
    fileinfo \
    mbstring \
    openssl \
    redis \
    tokenizer \
    xml \
    zip

# Install Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Install Node dependencies
COPY package.json package-lock.json ./
RUN npm ci

# Copy all source files
COPY . .

# Build frontend assets
RUN npm run build

# Create storage structure and set permissions
RUN mkdir -p \
    storage/app/public \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache/data \
    storage/framework/testing \
    storage/logs \
    bootstrap/cache \
    && php artisan storage:link \
    && chmod -R 777 storage bootstrap/cache

EXPOSE ${PORT:-8000}

# At runtime: cache config (reads real env vars), run migrations, then serve
CMD php artisan config:cache \
    && php artisan event:cache \
    && php artisan route:cache \
    && php artisan storage:link --force \
    && php artisan migrate --force \
    && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
