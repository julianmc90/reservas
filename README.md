# Reservas
Reservas de teatro hecho en laravel 5.7 

## Requisitos
* Php 7.1+
* Composer

## Correr los siguientes comandos

Clonar este repositorio con Git o descargarlo 
```
https://github.com/julianmc90/reservas.git
```
Instalar dependencias con composer
```
composer install
```
Generar un nuevo hash de seguridad
```
php artisan key:generate  
```
Limipiar cache
```
php artisan cache:clear
```
Para crear las tablas necesarias 
```
php artisan migrate
```
Para insertar algunos registros por defecto
```
php artisan db:seed
```
Correr la applicación y dirigirse a la ruta que se indica en la consola:
```
php artisan serve
```
 