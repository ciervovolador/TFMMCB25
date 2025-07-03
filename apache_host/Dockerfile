FROM ubuntu:22.04

# Instalar Apache y OpenSSH
RUN apt-get update && \
    apt-get install -y apache2 openssh-server && \
    mkdir /var/run/sshd && \
    echo 'root:toor' | chpasswd && \
    sed -i 's/#PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config

EXPOSE 22 80

CMD service apache2 start && /usr/sbin/sshd -D

