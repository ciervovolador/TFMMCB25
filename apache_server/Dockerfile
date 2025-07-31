FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    apache2 \
    php \
    php-cli \
    libapache2-mod-php \
    php-mysql \
    mysql-client \
    openssh-server \
    tzdata \
    msmtp msmtp-mta \
    iproute2 \
    openssl \
    ssl-cert

# Configura zona horaria
RUN ln -fs /usr/share/zoneinfo/Europe/Madrid /etc/localtime && \
    dpkg-reconfigure -f noninteractive tzdata

# Configura SSH
RUN mkdir /var/run/sshd && \
    echo 'root:toor' | chpasswd && \
    sed -i 's/#PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config

# Habilita mysqli si aplica (por seguridad)
RUN phpenmod mysqli || true

# Crear certificado autofirmado (clave RSA 1024 bits para menos seguridad)
RUN openssl req -x509 -nodes -days 365 -newkey rsa:1024 \
    -keyout /etc/ssl/private/apache-selfsigned.key \
    -out /etc/ssl/certs/apache-selfsigned.crt \
    -subj "/C=ES/ST=Madrid/L=Madrid/O=Insegura Corp/CN=localhost"

# Configuración HTTPS vulnerable
RUN echo "<IfModule mod_ssl.c>
    <VirtualHost _default_:443>
        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/html

        SSLEngine on
        SSLCertificateFile /etc/ssl/certs/apache-selfsigned.crt
        SSLCertificateKeyFile /etc/ssl/private/apache-selfsigned.key

        # Configurar protocolos inseguros y cifrados débiles
        SSLProtocol TLSv1
        SSLCipherSuite ALL:!aNULL:!eNULL
        SSLHonorCipherOrder off

        <Directory /var/www/html>
            Options Indexes FollowSymLinks
            AllowOverride All
            Require all granted
        </Directory>

        ErrorLog \${APACHE_LOG_DIR}/error.log
        CustomLog \${APACHE_LOG_DIR}/access.log combined
    </VirtualHost>
</IfModule>" > /etc/apache2/sites-available/default-ssl.conf

# Habilitar módulos y sitio SSL
RUN a2enmod ssl && \
    a2ensite default-ssl

# Copiar archivos web a /var/www/html (modifica según donde esté tu web)
COPY web/ /var/www/html/

# Exponer puertos HTTP, HTTPS y SSH
EXPOSE 80 443 22

# Configurar ruta por defecto
CMD ["bash", "-c", "ip route del default || true; ip route add default via 172.20.0.254; exec /usr/sbin/apache2ctl -D FOREGROUND"]
