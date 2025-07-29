FROM php:8.2-apache

# ✅ ติดตั้ง mysqli และ pgsql สำหรับ PHP
RUN docker-php-ext-install mysqli pdo_pgsql pgsql && a2enmod rewrite

# ✅ ตั้ง DirectoryIndex ให้ Apache รู้จัก index.php
RUN echo "DirectoryIndex index.php" > /etc/apache2/conf-enabled/directoryindex.conf

# ✅ คัดลอกไฟล์ทั้งหมดเข้า Apache root
COPY . /var/www/html/

# ✅ ตั้งสิทธิ์ให้ Apache
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# ✅ เปิดพอร์ต 80
EXPOSE 80
