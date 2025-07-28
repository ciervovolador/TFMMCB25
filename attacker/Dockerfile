lsFROM kalilinux/kali-rolling

# Instalar herramientas y SSH
RUN apt-get update && \
    apt-get install -y metasploit-framework wireshark tshark nmap net-tools iputils-ping curl openssh-client openssh-server && \
    mkdir -p /var/run/sshd && \
    echo 'root:toor' | chpasswd && \
    sed -i 's/#PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config && \
    echo 'PermitRootLogin yes' >> /etc/ssh/sshd_config

# Exponer el puerto SSH
EXPOSE 22

# Ejecutar el servidor SSH como proceso principal
CMD ["/usr/sbin/sshd", "-D"]

