# infra-web — Nginx + PHP-FPM (pdo_mysql + pdo_oci) + MySQL + phpMyAdmin

## Estrutura
```
infra-docker/
├── docker-compose.yml
├── .env                  # credenciais e portas (não versionar)
├── php/Dockerfile        # imagem PHP 8.3-FPM customizada
├── php/php.ini
├── nginx/default.conf    # vhost com FastCGI -> php:9000
├── mysql/my.cnf
├── mysql/init/01-init.sql
├── src/public/index.php  # página de teste da conexão
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
| App PHP    | http://localhost:8080 | —                             |
| phpMyAdmin | http://localhost:8081 | app_user ou root (ver .env)   |
| MySQL      | só pela rede backend  | —                             |

## Comandos úteis
```bash
docker compose logs -f nginx php mysql
docker compose exec php php -m                 # extensões carregadas
docker compose exec php php -r 'print_r(PDO::getAvailableDrivers());'
docker compose exec php sh -c 'ldd $(php -r "echo ini_get(\"extension_dir\");")/pdo_oci.so | grep -i "not found"'
docker compose exec mysql mysql -uapp_user -p app
docker compose exec nginx nginx -t && docker compose exec nginx nginx -s reload
docker network inspect infra-web_backend
docker compose down          # para (mantém dados)
docker compose down -v       # para e APAGA o volume mysql_data
```

## Backup / Restore
```bash
docker compose exec mysql sh -c 'mysqldump -uroot -p"$MYSQL_ROOT_PASSWORD" --all-databases' > mysql_$(date +%F).sql
docker compose exec -T mysql sh -c 'mysql -uroot -p"$MYSQL_ROOT_PASSWORD"' < mysql_AAAA-MM-DD.sql
```

## Oracle (pdo_oci)
- A imagem PHP usa base **Debian bookworm** porque o Oracle Instant Client exige glibc.
- O Instant Client (basic + sdk) é baixado de download.oracle.com no build, para x64 ou arm64.
- O banco Oracle é **externo** à infra. Configure `ORACLE_DSN`, `ORACLE_USER` e `ORACLE_PASSWORD` no `.env`.
- O php acessa o Oracle pela rede `frontend`, porque a `backend` é interna e não tem saída.

```php
$pdo = new PDO('oci:dbname=//10.0.0.50:1521/ORCLPDB1;charset=AL32UTF8', 'usuario', 'senha');
```
