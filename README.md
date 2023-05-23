
# Innoscripta

****Instalation****

`docker-compose up -d`

****Run migrations****

`docker exec innoscripta-app-1 php artisan migrate`

****Dummy users data****

`docker exec innoscripta-app-1 php artisan db:seed --class=UserSeeder`


****Login Detail****

****email:****
`john@example.com`

****password:**** `password`