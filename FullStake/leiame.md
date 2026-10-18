CONTAINER - PostgreSQL

BAIXANDO A IMAGEM POSTSQL
```bash
docker pull postgres:18.4
```
CRIANDO O DOCKER POSTGRES
```bash
docker run -d --name postgres -p 5432:5432 -e POSTGRES_PASSWORD=123456 -v postgres-data:/var/lib/18/postgresql
```
BAIXANDO A IMAGEM DO PGADMIN
```bash
docker pull dpage/pgadmin4
```
CRIANDO O DOCKER PGADMIN
```bash
docker run -d --name pgadmin -p 4000:80 -e PGADMIN_DEFAULT_EMAIL=admin@email.com -e PGADMIN_DEFAULT_PASSWORD=123456 
```
CRIANDO AS REDES DOS CONTAINERs
```bash
docker network create rede-app
```
ADICIONANDO OS CONTAINERs NA REDE CRIADA
```bash
docker network connect rede-app <id container>
```
CRIANDO OS CONTAINERs

UTILIZANDO O DOCKERFILE

COMANDOS CONTAINERs
```bash
docker build -t api .
```
```bash
docker run --name api -p 3000:3000 -v ${PWD}:/app api
```
INSTALANDO O PRISMA
```bash
npm install prisma @types/pg --save-dev
```
```bash
npm install @prisma/client @prisma/adapter-pg pg dotenv
```
INICIALIZANDO O prisma

npx prisma init

