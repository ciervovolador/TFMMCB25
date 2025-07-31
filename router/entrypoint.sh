#!/bin/bash

# Habilitar reenvío de paquetes (routing)
echo 1 > /proc/sys/net/ipv4/ip_forward

# Limpiar reglas anteriores
iptables -F
iptables -t nat -F
iptables -F FORWARD

# Reglas DNAT: Redirige tráfico entrante en interfaz pública (eth1) hacia servicios internos
iptables -t nat -A PREROUTING -i eth1 -p tcp --dport 80   -j DNAT --to-destination 172.20.0.11:80   # HTTP
iptables -t nat -A PREROUTING -i eth1 -p tcp --dport 2022 -j DNAT --to-destination 172.20.0.11:22   # SSH Apache
iptables -t nat -A PREROUTING -i eth1 -p udp --dport 53   -j DNAT --to-destination 172.20.0.10:53   # DNS UDP
iptables -t nat -A PREROUTING -i eth1 -p tcp --dport 53   -j DNAT --to-destination 172.20.0.10:53   # DNS TCP

# Regla SNAT: permite que la red privada acceda a internet usando IP pública
iptables -t nat -A POSTROUTING -o eth1 -s 172.20.0.0/24 -j SNAT --to-source 203.0.113.20

# Permitir tráfico relacionado y establecido (para respuestas)
iptables -A FORWARD -m state --state RELATED,ESTABLISHED -j ACCEPT

# Permitir tráfico nuevo hacia los servicios internos necesarios
iptables -A FORWARD -p tcp -d 172.20.0.11 --dport 80 -j ACCEPT
iptables -A FORWARD -p tcp -d 172.20.0.11 --dport 22 -j ACCEPT
iptables -A FORWARD -p udp -d 172.20.0.10 --dport 53 -j ACCEPT
iptables -A FORWARD -p tcp -d 172.20.0.10 --dport 53 -j ACCEPT

# Inicia el servicio SSH del contenedor
exec /usr/sbin/sshd -D
