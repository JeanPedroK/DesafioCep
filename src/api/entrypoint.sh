#!/bin/sh
# Verifica se a pasta vendor não existe
if [ ! -d "vendor" ]; then
  echo "Pasta vendor não encontrada. Executando composer install..."
  composer install -o
else
  echo "Pasta vendor encontrada. Pulando composer install..."
fi

# Executa as migrações do Phinx
vendor/bin/phinx migrate

# Executa o comando padrão (ou seja, inicia o servidor, etc.)
exec /entrypoint supervisord