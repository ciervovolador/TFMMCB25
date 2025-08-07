#!/bin/bash

# Variables de IP
EXT_IP="203.0.113.20"    # IP pública del router
INT_IP="172.20.0.254"    # IP privada del router

DNS_IP="172.20.0.10"
APACHE_IP="172.20.0.11"
GRAFANA_IP="172.20.0.15"

# Autodetectar interfaces según IP asignada
EXT_IF=$(ip -o -4 addr show | grep "$EXT_IP" | awk '{print $2}')
INT_IF=$(ip -o -4 addr show | grep "$INT_IP" | awk '{print $2}')

echo "[+] Interfaz pública detectada: $EXT_IF"
echo "[+] Interfaz privada detectada: $INT_IF"

# Validación
if [[ -z "$EXT_IF" || -z "$INT_IF" ]]; then
  echo "[!] Error: no se pudieron detectar las interfaces. Abortando..."
  exit 1
fi

# Habilitar forwarding
sysctl -w net.ipv4.ip_forward=1

# Limpiar reglas existentes
iptables -F
iptables -t nat -F

# 1. NAT DNS
iptables -t nat -A PREROUTING -i "$EXT_IF" -p udp --dport 53 -j DNAT --to "$DNS_IP"
iptables -t nat -A PREROUTING -i "$EXT_IF" -p tcp --dport 53 -j DNAT --to "$DNS_IP"

# 2. NAT APACHE (todo excepto puerto 53)
iptables -t nat -A PREROUTING -i "$EXT_IF" -d "$EXT_IP" -p tcp -m multiport ! --dports 53 -j DNAT --to "$APACHE_IP"
iptables -t nat -A PREROUTING -i "$EXT_IF" -d "$EXT_IP" -p udp -m multiport ! --dports 53 -j DNAT --to "$APACHE_IP"

# 3. NAT GRAFANA
iptables -t nat -A PREROUTING -i "$EXT_IF" -p tcp --dport 3000 -j DNAT --to "$GRAFANA_IP"

# 4. FORWARDING RULES
iptables -A FORWARD -i "$EXT_IF" -o "$INT_IF" -p udp --dport 53 -d "$DNS_IP" -j ACCEPT
iptables -A FORWARD -i "$EXT_IF" -o "$INT_IF" -p tcp --dport 53 -d "$DNS_IP" -j ACCEPT
iptables -A FORWARD -i "$EXT_IF" -o "$INT_IF" -d "$APACHE_IP" -j ACCEPT
iptables -A FORWARD -i "$EXT_IF" -o "$INT_IF" -p tcp --dport 3000 -d "$GRAFANA_IP" -j ACCEPT

# 5. MASQUERADE
iptables -t nat -A POSTROUTING -o "$INT_IF" -j MASQUERADE
iptables -t nat -A POSTROUTING -o "$EXT_IF" -j MASQUERADE

# 6. DNS saliente
iptables -t nat -A POSTROUTING -o "$EXT_IF" -p udp --dport 53 -j MASQUERADE
iptables -t nat -A POSTROUTING -o "$EXT_IF" -p tcp --dport 53 -j MASQUERADE

echo "[✔] Reglas NAT y forwarding aplicadas correctamente"

tail -f /dev/null
