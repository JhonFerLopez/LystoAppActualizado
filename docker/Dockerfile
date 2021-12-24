FROM php:7.1-fpm

# Get argument defined in docker-compose file
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
	git \
	curl \
	libpng-dev \
	libonig-dev \
	libxml2-dev \
	zip \
	unzip \
	libzip-dev \
	libmagickwand-dev \
	&& docker-php-ext-install pdo_mysql \
	&& docker-php-ext-install mbstring \
	&& docker-php-ext-install exif \
	&& docker-php-ext-install pcntl \
	&& docker-php-ext-install bcmath \
	&& docker-php-ext-configure gd --with-freetype \
	&& docker-php-ext-install gd \
	&& docker-php-ext-install zip \
	&& docker-php-ext-install intl \
	&& docker-php-ext-install mysqli \
	&& docker-php-ext-install opcache \
	&& docker-php-source delete \
	&& pecl install imagick \
	&& pecl install redis

RUN echo "extension=imagick.so" > /usr/local/etc/php/conf.d/docker-php-ext-imagick.ini
RUN echo "extension=redis.so" > /usr/local/etc/php/conf.d/docker-php-ext-redis.ini

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Get latest Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
	chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www

USER $user

# Expose port 9000 and start php-fpm server
EXPOSE 9000

CMD ["php-fpm"]
