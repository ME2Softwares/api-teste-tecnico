
# API PARA TESTE TÉCNICO

## Subir a API
Para testar o projeto, siga os passos descritos abaixo

### Passo a passo
Clone Repositório
```sh
git clone https://github.com/ME2Softwares/api-teste-tecnico
```

```sh
cd api-teste-tecnico
```

Crie o Arquivo .env
```sh
cp .env.example .env
```


Atualize as variáveis de ambiente do arquivo .env
```dosini
APP_NAME=EspecializaTi
APP_URL=http://localhost:8180

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=nome_que_desejar_db
DB_USERNAME=root
DB_PASSWORD=root
```


Suba os containers do projeto
```sh
docker-compose up -d
```


Acessar o container do projeto
```sh
docker-compose exec me2-lumen-app bash
```


Instalar as dependências do projeto
```sh
composer install
```


Acesse o projeto
[http://localhost:8081](http://localhost:8081)
