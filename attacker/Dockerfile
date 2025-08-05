FROM dorowu/ubuntu-desktop-lxde-vnc

# ---- Eliminar fuentes obsoletas (como en user) ----
RUN rm -f /etc/apt/sources.list.d/google-chrome.list

# ---- Instalar herramientas de red (ping, telnet, SSH) ----
RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y \
        net-tools iproute2 iputils-ping openssh-server telnet && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# ---- Añadir Metasploit y Nmap ----
# 1. Añadir repositorio de Metasploit
RUN curl -fsSL https://apt.metasploit.com/metasploit-framework.gpg.key | gpg --dearmor > /etc/apt/trusted.gpg.d/metasploit.gpg && \
    echo "deb [arch=amd64] https://apt.metasploit.com/ buster main" > /etc/apt/sources.list.d/metasploit.list

# 2. Instalar Metasploit + Nmap + Firefox (para interfaz gráfica)
RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y \
        metasploit-framework \
        nmap \
        firefox && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# ---- Configurar SSH (como en user) ----
RUN echo 'root:toor' | chpasswd && \
    sed -i 's/#PermitRootLogin.*/PermitRootLogin yes/' /etc/ssh/sshd_config && \
    mkdir -p /var/run/sshd && \
    ssh-keygen -A

# ---- Copiar entrypoint IDÉNTICO al de user ----
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 22 80 6080
CMD ["/entrypoint.sh"]
