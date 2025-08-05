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

RUN ln -fs /usr/share/zoneinfo/Europe/Madrid /etc/localtime && \
    dpkg-reconfigure -f noninteractive tzdata

RUN mkdir /var/run/sshd && \
    echo 'root:toor' | chpasswd && \
    sed -i 's/#PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config

RUN phpenmod mysqli || true

RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/ssl/private/apache-selfsigned.key \
    -out /etc/ssl/certs/apache-selfsigned.crt \
    -subj "/C=ES/ST=Madrid/L=Madrid/O=Insegura Corp/CN=localhost"

COPY default-ssl.conf /etc/apache2/sites-available/default-ssl.conf

RUN a2enmod ssl

RUN a2ensite default-ssl

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

COPY web/ /var/www/html/

EXPOSE 80 443 22

CMD ["bash", "-c", "ip route del default || true; ip route add default via 172.20.0.254; /usr/sbin/apache2ctl -D FOREGROUND"]
