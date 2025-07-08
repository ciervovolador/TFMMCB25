FROM ubuntu:22.04

RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y bind9 dnsutils openssh-server && \
    mkdir /var/run/sshd && \
    echo 'root:toor' | chpasswd && \
    sed -i 's/#PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config

# Copiar configuraciones de BIND
COPY named.conf.local /etc/bind/named.conf.local
COPY named.conf.options /etc/bind/named.conf.options
COPY zones /etc/bind/zones

EXPOSE 22
EXPOSE 53/tcp
EXPOSE 53/udp

CMD bash -c "named -g -c /etc/bind/named.conf & /usr/sbin/sshd -D"

