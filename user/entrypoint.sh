
#!/bin/bash

# Establecer ruta por defecto hacia 172.20.0.254
ip route del default || true
ip route add default via 172.20.0.254

# Lanzar VNC y SSH
vncserver :1
/usr/sbin/sshd -D
