FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

# Instala dependencias
RUN apt-get update && apt-get install -y \
    apache2 \
    php \
    php-cli \
    libapache2-mod-php \
    php-mysql \
    mysql-client \
    openssh-server \
    tzdata \
    msmtp msmtp-mta

# Configura zona horaria
RUN ln -fs /usr/share/zoneinfo/Europe/Madrid /etc/localtime && \
    dpkg-reconfigure -f noninteractive tzdata

# Configura SSH
RUN mkdir /var/run/sshd && \
    echo 'root:toor' | chpasswd && \
    sed -i 's/#PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config

# Habilita mysqli si aplica (por seguridad)
RUN phpenmod mysqli || true

# Exposición de puertos
EXPOSE 80 22

# Inicia Apache
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
