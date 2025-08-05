#!/bin/bash

# Configurar ruta por defecto hacia el router (red pública)
ip route del default  # Elimina la ruta por defecto existente (10.20.30.1)
ip route add default via 203.0.113.20 dev eth0

# Configurar DNS para usar el router (que reenviará al DNS interno 172.20.0.10)
echo "nameserver 203.0.113.20" > /etc/resolv.conf

# Mantener el contenedor en ejecución (si es necesario)
tail -f /dev/null

