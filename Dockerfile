FROM php:8.3-fpm

# Arguments defined in docker-compose.yml
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
  unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Ensure the working directory has the correct permissions
RUN chown -R $user:$user /var/www

# Copy the application code to the container
COPY . /var/www

# Use the existing user
USER $user

# Optionally, create the Composer directory and set permissions if needed
RUN mkdir -p /home/$user/.composer && \
  chown -R $user:$user /home/$user/.composer

# Run composer install
RUN composer install --no-interaction --optimize-autoloader --prefer-dist

