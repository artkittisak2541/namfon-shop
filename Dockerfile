FROM php:8.2-apache

# ✅ ติดตั้ง PostgreSQL และเปิด mod_rewrite
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql \
    && a2enmod rewrite

# ✅ ตั้ง DirectoryIndex ให้ apache รู้จัก index.php
RUN echo "DirectoryIndex index.php" > /etc/apache2/conf-enabled/directoryindex.conf

# ✅ คัดลอกโปรเจกต์ไปไว้ใน apache root
COPY . /var/www/html/

# ✅ ตั้งสิทธิ์และความปลอดภัยให้ apache
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

EXPOSE 80
