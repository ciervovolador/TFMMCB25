#!/bin/bash
set -e

# Configurar ruta por defecto hacia el router
ip route del default 2>/dev/null || true
ip route add default via 203.0.113.20 dev eth0 || true

# Forzar DNS y proteger resolv.conf
echo "nameserver 203.0.113.20" > /etc/resolv.conf
chattr +i /etc/resolv.conf 2>/dev/null || true

# Iniciar entorno gráfico con VNC y SSH
vncserver :1
/usr/sbin/sshd -D
