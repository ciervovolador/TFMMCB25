#!/bin/bash

# Variables
EXT_IF="eth1"  # Interfaz hacia red pública
INT_IF="eth0"  # Interfaz hacia red privada
DNS_IP="172.20.0.10"
DNS_PORT="53"

# Habilitar forwarding
sysctl -w net.ipv4.ip_forward=1

# Limpiar reglas anteriores
iptables -F
iptables -t nat -F

# Configurar NAT (MASQUERADE para tráfico saliente)
iptables -t nat -A POSTROUTING -o $INT_IF -j MASQUERADE
iptables -t nat -A POSTROUTING -o $EXT_IF -j MASQUERADE

# Redirección DNS
iptables -t nat -A PREROUTING -i $EXT_IF -p udp --dport $DNS_PORT -j DNAT --to-destination $DNS_IP:$DNS_PORT
iptables -t nat -A PREROUTING -i $EXT_IF -p tcp --dport $DNS_PORT -j DNAT --to-destination $DNS_IP:$DNS_PORT

# Permitir tráfico DNS
iptables -A FORWARD -p udp -i $EXT_IF -o $INT_IF --dport $DNS_PORT -d $DNS_IP -j ACCEPT
iptables -A FORWARD -p tcp -i $EXT_IF -o $INT_IF --dport $DNS_PORT -d $DNS_IP -j ACCEPT
iptables -A FORWARD -p udp -i $INT_IF -o $EXT_IF --sport $DNS_PORT -s $DNS_IP -j ACCEPT
iptables -A FORWARD -p tcp -i $INT_IF -o $EXT_IF --sport $DNS_PORT -s $DNS_IP -j ACCEPT

echo "Configuración de red y reglas IPTABLES completada."

# Mantener el contenedor vivo
tail -f /dev/null
