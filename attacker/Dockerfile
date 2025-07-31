FROM dorowu/ubuntu-desktop-lxde-vnc

# Eliminar fuente obsoleta de Chrome que da error de firma
RUN rm -f /etc/apt/sources.list.d/google-chrome.list

# Actualizar e instalar herramientas necesarias
RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y \
      curl gnupg2 nmap net-tools iputils-ping openssh-server firefox && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Añadir Metasploit Framework
RUN curl https://apt.metasploit.com/metasploit-framework.gpg.key | gpg --dearmor > /etc/apt/trusted.gpg.d/metasploit.gpg && \
    echo "deb https://apt.metasploit.com/ buster main" > /etc/apt/sources.list.d/metasploit.list && \
    apt-get update && \
    apt-get install -y metasploit-framework && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Configurar root y SSH
RUN echo 'root:toor' | chpasswd && \
    sed -i 's/#PermitRootLogin.*/PermitRootLogin yes/' /etc/ssh/sshd_config

EXPOSE 22 80 6080

# Usa el CMD de la imagen base (ya lanza VNC, escritorio, websockify, etc.)
