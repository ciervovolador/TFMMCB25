#!/bin/bash

# Variables
EXT_IF="eth1"  # Interfaz pública (203.0.113.20)
INT_IF="eth0"  # Interfaz privada (172.20.0.254)
DNS_IP="172.20.0.10"
APACHE_IP="172.20.0.11"

# Habilitar forwarding
sysctl -w net.ipv4.ip_forward=1

# Limpiar reglas
iptables -F
iptables -t nat -F

# 1. REGLAS DNS (MÁXIMA PRIORIDAD)
iptables -t nat -A PREROUTING -i $EXT_IF -p udp --dport 53 -j DNAT --to $DNS_IP
iptables -t nat -A PREROUTING -i $EXT_IF -p tcp --dport 53 -j DNAT --to $DNS_IP

# 2. NAT ESTÁTICO PARA APACHE (EXCLUYENDO DNS)
iptables -t nat -A PREROUTING -i $EXT_IF -d 203.0.113.20 -p tcp -m multiport ! --dports 53 -j DNAT --to $APACHE_IP
iptables -t nat -A PREROUTING -i $EXT_IF -d 203.0.113.20 -p udp -m multiport ! --dports 53 -j DNAT --to $APACHE_IP

# 3. REGLAS FORWARD
# Permitir tráfico DNS
iptables -A FORWARD -i $EXT_IF -o $INT_IF -p udp --dport 53 -d $DNS_IP -j ACCEPT
iptables -A FORWARD -i $EXT_IF -o $INT_IF -p tcp --dport 53 -d $DNS_IP -j ACCEPT

# Permitir tráfico a Apache
iptables -A FORWARD -i $EXT_IF -o $INT_IF -d $APACHE_IP -j ACCEPT

# 4. MASQUERADE (NAT dinámico para tráfico saliente)
iptables -t nat -A POSTROUTING -o $INT_IF -j MASQUERADE
iptables -t nat -A POSTROUTING -o $EXT_IF -j MASQUERADE

# 5. CONFIGURACIÓN ADICIONAL PARA DNS RECURSIVO
iptables -t nat -A POSTROUTING -o $EXT_IF -p udp --dport 53 -j MASQUERADE
iptables -t nat -A POSTROUTING -o $EXT_IF -p tcp --dport 53 -j MASQUERADE

echo "Configuración aplicada:"
echo "- NAT estático 203.0.113.20 → 172.20.0.11 (excluyendo DNS)"
echo "- Redirección DNS prioritaria (puerto 53 → 172.20.0.10)"
echo "- NAT dinámico para consultas DNS salientes"

tail -f /dev/null
