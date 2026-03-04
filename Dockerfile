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

# Install Node dependencies and build assets
COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run build

# Create storage structure and set permissions
RUN mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Cache Laravel config/routes (view:cache skipped — compiles on-demand at runtime)
RUN php artisan config:cache \
    && php artisan event:cache \
    && php artisan route:cache

EXPOSE ${PORT:-8000}

# Run migrations then start the server
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
