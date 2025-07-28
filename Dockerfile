FROM php:8.2-apache

# ติดตั้ง mysqli และเปิด mod_rewrite
RUN docker-php-ext-install mysqli && a2enmod rewrite

# ตั้งค่า DirectoryIndex ให้ Apache รู้ว่าให้โหลด index.php เป็นหน้าแรก
RUN echo "DirectoryIndex index.php" > /etc/apache2/conf-enabled/directoryindex.conf

# คัดลอกไฟล์เข้า container
COPY . /var/www/html/

# ตั้งสิทธิ์ให้ Apache อ่านไฟล์ได้
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

EXPOSE 80
