# infra-web — Nginx + PHP-FPM + MySQL + phpMyAdmin

## Estrutura
```
infra-docker/
├── docker-compose.yml
├── .env                  # credenciais e portas (não versionar)
├── php/Dockerfile        # imagem PHP 8.3-FPM (Alpine) customizada
├── php/php.ini
├── nginx/default.conf    # vhost com FastCGI -> php:9000
├── mysql/my.cnf
├── mysql/init/01-init.sql
├── www/                  # ARMAZENAMENTO DAS APLICAÇÕES PHP (bind no host)
│   ├── index.php         # painel: status + lista de apps
│   ├── phpinfo.php       # teste do PHP (remover em produção)
│   └── app-exemplo/      # app de exemplo lendo o MySQL
└── infra-docker.drawio   # diagrama
```

## Subir
```bash
nano .env                 # troque as senhas
docker compose build php
docker compose up -d
docker compose ps
```

## Acessos
| Serviço    | Endereço              | Login                         |
|------------|-----------------------|-------------------------------|
| Painel     | http://localhost:8080 | —                             |
| phpinfo    | http://localhost:8080/phpinfo.php | —                |
| App exemplo| http://localhost:8080/app-exemplo/ | —               |
| phpMyAdmin | http://localhost:8081 | app_user ou root (ver .env)   |
| MySQL      | só pela rede backend  | —                             |

## Comandos úteis
```bash
docker compose logs -f nginx php mysql
docker compose exec php php -m                 # extensões carregadas
docker compose exec php php -r 'print_r(PDO::getAvailableDrivers());'
docker compose exec mysql mysql -uapp_user -p app
docker compose exec nginx nginx -t && docker compose exec nginx nginx -s reload
docker network inspect infra-web_backend
docker compose down          # para (mantém dados)
docker compose down -v       # ⚠️ para e APAGA TODOS os volumes (mysql + php)
```

## Backup / Restore
```bash
docker compose exec mysql sh -c 'mysqldump -uroot -p"$MYSQL_ROOT_PASSWORD" --all-databases' > mysql_$(date +%F).sql
docker compose exec -T mysql sh -c 'mysql -uroot -p"$MYSQL_ROOT_PASSWORD"' < mysql_AAAA-MM-DD.sql
```

## Armazenamento das aplicações
- As aplicações ficam em **`./www`** no host, montado em `/var/www/html` nos containers nginx (somente leitura) e php (leitura e escrita).
- Os arquivos persistem no host mesmo com `docker compose down -v`. Faça backup dessa pasta junto com o dump do MySQL.
- Nova aplicação: crie `./www/<nome-da-app>/index.php` e acesse `http://localhost:8080/<nome-da-app>/`. Não é preciso reiniciar.
- Pastas com escrita (uploads, cache, logs): `docker compose exec php chown -R www-data:www-data /var/www/html/<app>/storage`

```bash
mkdir -p www/minha-app && echo '<?php echo "Olá";' > www/minha-app/index.php
curl -s http://localhost:8080/minha-app/
tar czf www_$(date +%F).tar.gz www/          # backup das aplicações
```

## Persistência do PHP
| Dado | Onde fica | Tipo | Caminho no container |
|---|---|---|---|
| Código das aplicações | `./www` no host | bind mount | `/var/www/html` |
| Uploads / cache / arquivos | `infra-web_php_storage` | volume | `/var/www/storage` |
| Sessões PHP | `infra-web_php_sessions` | volume | `/var/lib/php/sessions` |
| Logs de erro | `infra-web_php_logs` | volume | `/var/log/php` |
| Banco MySQL | `infra-web_mysql_data` | volume | `/var/lib/mysql` |

Os dados permanecem após `down`, `up`, `restart`, `build` e reboot do host. Somente `down -v` ou `docker volume rm` apagam os volumes.

```bash
docker volume ls | grep infra-web
docker volume inspect infra-web_php_storage          # Mountpoint no host
docker compose exec php tail -f /var/log/php/php_errors.log

# Backup de um volume
docker run --rm -v infra-web_php_storage:/dados -v "$PWD":/backup alpine \
  tar czf /backup/php_storage_$(date +%F).tar.gz -C /dados .

# Restore
docker run --rm -v infra-web_php_storage:/dados -v "$PWD":/backup alpine \
  sh -c "tar xzf /backup/php_storage_AAAA-MM-DD.tar.gz -C /dados"
```

Uso no código PHP:
```php
$destino = '/var/www/storage/uploads/' . basename($_FILES['arquivo']['name']);
@mkdir(dirname($destino), 0775, true);
move_uploaded_file($_FILES['arquivo']['tmp_name'], $destino);
```
#################################
#################################
***
#Gerar a imagem
```bash
sudo docker compose build --no-cache php
```
Gerar containeres
```bash
sudo docker compose up -d
```
Derrubar todos os containers
```bash
docker compose down
```
***