#!/bin/bash

# Gera o arquivo gcs-key.json se a variável de ambiente estiver definida
if [ -n "$GCS_KEY_JSON" ]; then
  echo "$GCS_KEY_JSON" > /var/www/html/gcs-key.json
  echo "[entrypoint] gcs-key.json criado com sucesso."
else
  echo "[entrypoint] ⚠️ GCS_KEY_JSON não definido. Uploads não funcionarão!"
fi

# Executa o comando original (ex: apache2-foreground)
exec "$@"
