FROM debian:bullseye-slim

RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y \
      iptables openssh-server iproute2 iputils-ping net-tools && \
    mkdir -p /var/run/sshd && \
    echo 'root:summer2020' | chpasswd && \
    sed -i 's/#PermitRootLogin.*/PermitRootLogin yes/' /etc/ssh/sshd_config && \
    ssh-keygen -A

# Habilitar IP forwarding
RUN echo "net.ipv4.ip_forward=1" >> /etc/sysctl.conf

EXPOSE 22

CMD sysctl -w net.ipv4.ip_forward=1 && \
    iptables -t nat -A POSTROUTING -o eth1 -j MASQUERADE && \
    /usr/sbin/sshd -D



# Configura gateway
CMD ["bash", "-c", "ip route del default || true && ip route add default via 172.20.0.254 && exec /usr/sbin/apache2ctl -D FOREGROUND"]







