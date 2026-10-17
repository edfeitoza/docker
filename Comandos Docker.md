***
***
INSTALAÇÃO DO DOCKER EM UM ROCKLINUX:
(opcional) remover pacotes que podem conflitar
***
*** 
```bash
sudo dnf remove docker docker-client docker-common docker-engine podman runc
```
```bash
sudo dnf check-update
sudo dnf install -y dnf-plugins-core
sudo dnf config-manager --add-repo https://download.docker.com/linux/rhel/docker-ce.repo
sudo dnf install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
sudo systemctl --now enable docker
sudo systemctl status docker
```
***
MANIPULANDO IMAGENS
***

PUXANDO UMA IMAGEM DO DOCKER-HUB, SERÁ PRECISO SABER O NOME A IMAGEM:
```bash
docker pull <nome da imagem>
docker pull hello-world
```
LISTA AS IMAGENS BAIXAS:
```bash
docker images
```
RENOMEANDO AS IMAGENS
```bash
docker tag <id> <novo nome>
```
REMOVENDO AS IMAGENS OU TAGs, A PARTIR DO NOME OU ID:
```bash
docker rmi <image/tag/id>
```
***
MANIPULANDO CONTAINER
***
INICIANDO CONTAINER, CASO NÃO EXISTA A IMAGEM SOLICITADA NO COMANDO, O DOCKER BAIXARÁ A ULTIMA VERSÃO DA IMAGEM SOLICITADA:
```bash
docker run hello-world
```
INICIANDO O CONTAINER JÁ BAIXADA PELO ID:
```bash
docker run <id>
```
INICIANDO O CONTAINER A PARTIR DE UMA PORTA ESPECIFICA:
```bash
docker run -p <porta local>:<porta container> <id da imagem>
```
STATUS DOS CONTAINERS ATIVOS/RODANDO:
```bash
docker ps
```
LISTANDO TODOS OS CONTAINERs INICIADOS/PARADOS:
```bash
docker ps -a
```
INICIANDO UM CONTAINER JÁ CRIADO ANTERIORMENTE:
```bash
docker start <id>
```
PARANDO UM CONTAINER
```bash
docker stop <id>
```
RESTARTANDO UM CONTAINER
```bash
docker restart <id>
```
RENOMEANDO O CONTAINER
```bash
docker rename <id container/nome> <novo nome> 
```
INSPECIONANDO UM DOCKER, CHECANDO A VARIAVEL DE AMBIENTE
```bash
docker inspect <id container/nome> 
```
CRIANDO UM APELIDO AO CONTAINER:
```bash
docker run --name <apelido do container> <nome da imagem>
```
CHECANDO RECURSOS CONSUMIDOS PELOS CONTAINER ATIVOS:
```bash
docker stats
```
RECONFIGURANDO A MEMORIA DE UM CONTAINER QUE ESTEJA RODANDO, É PRECISO ACERTAR O SWAP
```bash
docker update --memory <qtd memoria>g --memory-swap <qtd memoria>g <id container/nome>
```
ANALISANDO LOGs DO CONTAINER:
```bash
docker logs <id container/nome>
docker logs -f <id container/nome>
docker logs --tail 10 <id container/nome>
docker logs --since 1m <id container/nome>
docker logs -f --since 1m <id container/nome>
docker logs -f --tail 10 <id container/nome>
```
INICIANDO UM CONTAINER TEMPORARIO, É APAGADO AO DAR stop:
```bash
docker run --rm -d <id container/nome>
```
INICIANDO UM CONTAINER EM MODO DEAMON:
```bash
docker run -d <id container>
docker run -dt --name ubuntu-server ubuntu
docker run -d --name web-server -p 80:80 nginx
```
INICIA A IMAGEM INTERATIVA, O SHELL ATUAL SE TRANSFORMA NO SHELL DO CONTAINER
```bash
docker run -it <id container/nome> sh
docker run -it <id container/nome> bash

docker run <flags> <imagem> <comando>
            -p      id imagem sh/bash
            -d                 ls /
            -it                env
            -rm                whoami
```
O PROMPT ABAIXO DETERMINA QUE ESTAMOS EM UM CONTAINER:
root@cb563106fcc1:/#

REMOVENDO UM CONTAINER DESDE QUE ESTEJA PARADO:
```bash
docker rm <nome do docker/id>
```
REALIZAR FAXINA NO AMBIENTE, USAR COM CUIDADO, APAGA CONTAINERs PARADOS E INTERFACE DE REDE:
```bash
docker system prune <all>
```
***
MANIPULANDO VOLUMES  E BIND MOUNTs
***
CRIANDO UM VOLUME
```bash
docker volume create <nome do volume>
```
LISTANDO VOLUMES CRIADOS
```bash
docker volume ls
```
***
EXEMPLO DE USO DOS VOLUMES
docker run --name nginx-web -p 3001:80 -d -v harddisk:/usr/share/nginx/html/ f9ea18bfa4fa
-v flag relacionada ao volume 
harddisk: nome do volume
/usr/share/nginx/html/ caminho indicado pela documentacao da imagem
***
INSPECIONANDO VOLUMES
```bash
docker volume inspect <nome do volume> 
```
REMOVENDO UM VOLUME CRIADO, só apaga se o container estiver parado.
```bash
docker volume rm <nome volume>
```
***
BIND MOUNT
***
```bash
docker run -d -v <caminho do sistema operacional>:<caminho da documentação do docker> <id da imagem>
```
***
MANIPULANDO REDES
***
CRIANDO UMA REDE
```bash
docker network create <nome da rede>
```
LISTANDO REDES CRIADAS
```bash
docker network ls
```
AGREGANDO CONTAINER EM SUAS RESPECTIVAS REDES
```bash
docker network connect <id da rede> <id container/nome>
```
CHECANDO CONTAINER ANINHADO A NETWORK
```bash
docker network inspect <id network/nome>
```
ADICIONANDO NETWORK A UM CONTAINER NO MOMENTO DE SUA CRIAÇÃO:
```bash
docker run --name <nome container> -d -p <porta de acesso XX:XX> -e POSTGRES_PASSWORD=<senha> -e POSTGRES_USER=<usuario> -e POSTGRES_DB=<nome da base de dados> -v <>bind mount>:<padrão do container> --network <id da network/nome> <imagem>
```
```bash
docker run -d --name node-tst --rm -p 4000:3000 -v ${PWD}:/app c850028075e3
```
[!IMPORTANTE]
>cim um bind mount, é possivel realizar ajustes no docker em momento de execução.
>cria um bind mount a partir de uma variavel de ambiente do Path atual ${PWD}
>-v ${PWD}:/app
>apaga o container automaticamente assim que parar
>--rm
 ***
TIPOS DE REDES
***
BRIDGE: compartilha conexão com a maquina fisica
HOST: compartilha conexão entre containers
NONE: sem acesso a rede
***
BRIDGE 
```bash
docker --network local-lan
```
HOST
```bash
docker --network host
```
NONE
```bash
docker --network none
```
REMOVENDO UMA NETWORK
```bash
docker network rm <id da network/nome>
```
CONECTAR CONTAINER A UMA REDE EXISTENTE
```bash
docker network connect <id da network/nome> <id container/nome>
```
DESCONECTAR CONTAINER A UMA REDE EXISTENTE
```bash
docker network disconnect <id da network/nome> <id container/nome>
```
***
CRIANDO UM AMBIENTE DOCKER - WORDPRESS/MYSQL - EXEMPLO DE USO E APLICABILIDADE
***
CRIANDO A NETWORK DO AMBIENTE DOCKER WORDPRESS
```bash
docker network create lan-wordpress
```
[!ATENCAO]
>CRIANDO O DOCKER WORDPRESS\
>VARIAVEIS DE AMBIENTES NECESSARIAS:\
>-e WORDPRESS_DB_HOST=mysql\
>-e WORDPRESS_DB_USER=wuser\
>-e WORDPRESS_DB_PASSWORD=1234\
>-e WORDPRESS_DB_NAME=wordb\
>wordpress:/var/www/html\

```bash
docker run --name wordpress --network lan-wordpress -p 4000:80 -e WORDPRESS_DB_HOST=mysql -e WORDPRESS_DB_USER=wuser -e WORDPRESS_DB_PASSWORD=1234 -e WORDPRESS_DB_NAME=wordb -v /container/wordpress-my:/var/www/html f32ffa85064d
```
[!ATENCAO]
>CRIANDO O DOCKER MYSQL\
>VARIAVEIS DE AMBIENTES NECESSARIAS:\
>-e MYSQL_DATABASE=wordb\
>-e MYSQL_USER=wuser\
>-e MYSQL_PASSWORD=1234\
>-e MYSQL_ROOT_PASSWORD=102030\ 

```bash
docker run --name mysql --network lan-wordpress -e MYSQL_DATABASE=wordb -e MYSQL_USER=wuser -e MYSQL_PASSWORD=1234 -e MYSQL_ROOT_PASSWORD=102030 -v /container/mysql-wd:/var/lib/mysql 9d48c42f8341
```
***
CONSTRUINDO O DOCKERFILE
***
CRIANDO UMA IMAGEM UTILIZANDO O DOCKERFILE
```bash
docker build -t dockerfile:<versao-opcional> <caminho do arquivo>
```
CRIANDO UMA IMAGEM UTILIZANDO O DOCKERFILE COM NOME DIFERENTE DO PADRÃO
```bash
docker build -f <novo nome da imagem:versao> <caminho do arquivo>
```
***
DOCKFILE
```bash
FROM node:26-slim
WORKDIR /app
COPY . .
RUN npm install
VOLUME /app/node_modules
EXPOSE 3000
CMD ["node", "app.js"]
```
***
[!ATENCAO]
>CONTEUDO BASICO DO dockerfle
>imagem a ser utilizada no projeto
>FROM <imagem>:<versao>
>diretorio de trabalho do container
>WORKDIR <diretorio>
>copia os arquivos do Workbook para a imagem e assim fazer parte do container
>COPY
>executa comados do sistema operacional, como por exemplo, instalar dependencias ou programas
>RUN <nome> <comando>
>cria um volume remontando no volume já indicado na linha de comando do docker run
>VOLUME <path do volume>
>configurar a porta de comunicação interna do container
>EXPOSE
>comando de execucao dentro do container
>CMD ["comando", "arquivo"]
[!ATENCAO]
>O CONTEUDO DO ARQUIVO .dockerignore SERVE PARA IGNORAR PACOTES DE INSTALAÇÃO DO SO LOCAL NA build DA IMAGEM A SER CRIADA
***
OFF-TOPPIC
***
INICIANDO PROJETO EM NODE
```bash
npm init -y
```
***
OFF-TOPPIC
***
TRABALHANDO COM GIT VIA LINHA DE COMANDO
***
INICIANDO UM REPOSITORIO DO GIT
```bash
sudo git init
```
CLONANDO O CONTEUDO LOCAL COM O GIT
```bash
sudo git remote add origin https://github.com/<link gerado pelo git>.git
```
ADICIONANDO TODO O CONTEUDO DA PASTA LOCAL
```bash
sudo git add .
```
REALIZANDO O PRIMEIRO COMMIT NO GIT
```bash
sudo git commit -m "criacao do repositorio"
```
CLONANDO UM REPOSITORIO GIT
```bash
sudo git clone <patha do git>.git
```
