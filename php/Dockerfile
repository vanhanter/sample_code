# Используем образ Apache с выбранной версией PHP
FROM php:8.3-apache

# Конфигурируем Apache для использования пользовательской директории
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Устанавливаем необходимые зависимости и библиотеки для расширений PHP
RUN apt update && apt install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    libmemcached-dev \
    liblz4-dev \
    libpq-dev \
    libz-dev \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Устанавливаем расширения PHP
RUN docker-php-ext-install pdo pdo_pgsql pdo_mysql mysqli \
    && docker-php-ext-install mbstring zip intl bcmath xml opcache \
    && docker-php-ext-enable opcache \
    && a2enmod rewrite

# Включаем все доступные модули PHP
RUN for mod in $(ls /etc/php/${PHP_VERSION}/cli/conf.d/); do phpenmod ${mod//.ini/}; done

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Устанавливаем Symfony-cli
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash  \
    && apt install symfony-cli -y

# Запускаем Apache в фоновом режиме
CMD ["apache2-foreground"]
