FROM dorowu/ubuntu-desktop-lxde-vnc

# Eliminar fuente obsoleta de Chrome que da error de firma
RUN rm -f /etc/apt/sources.list.d/google-chrome.list

# Actualizar sistema base e instalar herramientas básicas
RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y \
      software-properties-common \
      apt-transport-https \
      ca-certificates \
      curl \
      gnupg2

# Añadir repositorio de Metasploit
RUN curl -fsSL https://apt.metasploit.com/metasploit-framework.gpg.key | gpg --dearmor > /etc/apt/trusted.gpg.d/metasploit.gpg && \
    echo "deb [arch=amd64] https://apt.metasploit.com/ buster main" > /etc/apt/sources.list.d/metasploit.list

# Instalar el resto de herramientas
RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y \
      nmap \
      net-tools \
      iputils-ping \
      openssh-server \
      firefox \
      dnsutils \
      netcat-openbsd \
      telnet \
      iptables \
      iproute2 \
      metasploit-framework \
      attr && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Configurar root y SSH
RUN echo 'root:toor' | chpasswd && \
    sed -i 's/#PermitRootLogin.*/PermitRootLogin yes/' /etc/ssh/sshd_config && \
    mkdir -p /run/sshd

# Configuración de red persistente
RUN echo '#!/bin/bash\n\
# Esperar a que la interfaz esté disponible\n\
while [ ! -d /sys/class/net/eth0 ]; do sleep 1; done\n\
\n\
# Configurar dirección IP\n\
ip addr add 203.0.113.30/24 dev eth0 2>/dev/null || true\n\
ip link set eth0 up\n\
\n\
# Configurar ruta por defecto\n\
ip route del default 2>/dev/null || true\n\
ip route add default via 203.0.113.20 dev eth0\n\
\n\
# Configurar DNS\n\
echo "nameserver 203.0.113.20" > /etc/resolv.conf\n\
chattr +i /etc/resolv.conf 2>/dev/null || true\n\
\n\
exec "$@"' > /docker-entrypoint.sh && \
    chmod +x /docker-entrypoint.sh

EXPOSE 22 80 6080

ENTRYPOINT ["/docker-entrypoint.sh"]
CMD ["/usr/sbin/sshd", "-D"]
