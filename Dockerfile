# Dockerfile for GYAN Project
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql opcache

# Enable Apache mod_rewrite and mod_headers
RUN a2enmod rewrite headers

# Set recommended PHP settings for production
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.revalidate_freq=60'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'expose_php=Off'; \
    echo 'upload_max_filesize=10M'; \
    echo 'post_max_size=12M'; \
    } > /usr/local/etc/php/conf.d/gyan-production.ini

# Set Apache ServerTokens to reduce info leakage
RUN echo 'ServerTokens Prod' >> /etc/apache2/conf-available/security.conf \
    && echo 'ServerSignature Off' >> /etc/apache2/conf-available/security.conf

# Set working directory
WORKDIR /var/www/html

# Copy project files (.dockerignore excludes .env, .git, uploads, etc.)
COPY . /var/www/html/

# Ensure correct permissions for uploads and config
RUN mkdir -p /var/www/html/public/uploads/{profiles,blogs,events,gallery,vision2035} \
    && chown -R www-data:www-data /var/www/html/public/uploads \
    && chmod -R 775 /var/www/html/public/uploads \
    && chown -R www-data:www-data /var/www/html

# Health check
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

# Expose port 80
EXPOSE 80
