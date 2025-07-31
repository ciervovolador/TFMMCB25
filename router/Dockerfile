FROM debian:bullseye-slim

# Instalación de paquetes necesarios
RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y \
      iptables openssh-server iproute2 iputils-ping net-tools && \
    mkdir -p /var/run/sshd && \
    echo 'root:summer2020' | chpasswd && \
    sed -i 's/#PermitRootLogin.*/PermitRootLogin yes/' /etc/ssh/sshd_config && \
    ssh-keygen -A

# Copiar el script de inicio
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 22

CMD ["/entrypoint.sh"]
