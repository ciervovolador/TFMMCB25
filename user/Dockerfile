FROM dorowu/ubuntu-desktop-lxde-vnc

# Eliminar posibles fuentes obsoletas
RUN rm -f /etc/apt/sources.list.d/google-chrome.list

# Actualizar e instalar herramientas necesarias y Thunderbird
RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y \
        thunderbird \
        net-tools iproute2 iputils-ping openssh-server && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilitar SSH y usuario root
RUN echo 'root:toor' | chpasswd && \
    sed -i 's/#PermitRootLogin.*/PermitRootLogin yes/' /etc/ssh/sshd_config && \
    mkdir -p /var/run/sshd && \
    ssh-keygen -A

# Establecer puerta de enlace predeterminada
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 22 80 6080

CMD ["/entrypoint.sh"]
