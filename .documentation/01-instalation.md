# INSTALACIÓN

## Contenido

- [Recursos utilizados en este desarrollo 🌐](#recursos-utilizados-en-este-desarrollo-)
  - [Templates para panel admin, y ecommerce](#templates-para-panel-admin-y-ecommerce)
- [Tecnologías utilizadas](#tecnologías-utilizadas)
- [Pre-requisitos de instalación en servidor linux / Windows 📃](#pre-requisitos-de-instalación-en-servidor-linux--windows-📃)
- [Instalación 🔧](#instalación-🔧)
  - [Crear base de datos](#crear-base-de-datos)
  - [Instalar dependencias del vendor](#instalar-dependencias-del-vendor)
  - [Archivo .env](#archivo-env)
  - [Crear la llave del proyecto](#crear-la-llave-del-proyecto)
  - [Llenado de variables de entorno .env](#llenado-de-variables-de-entorno-env)
  - [Storage link](#storage-link)
  - [Ejecutando las migraciones](#ejecutando-las-migraciones)
- [Configuración de instalación restante (opcional)](#configuración-de-instalación-restante-opcional)
  - [Correo (opcional)](#correo-opcional)
  - [Google Socialite (opcional)](#google-socialite-opcional)
  - [Notificaciones push (opcional)](#notificaciones-push-opcional)
- [Colas de trabajo / Crones ⚙️](#colas-de-trabajo--crones-⚙️)
  - [Linux](#linux)
  - [Windows](#windows)
- [Acceder a la web](#acceder-a-la-web)
- [Enlazar algún ERP de Odoo](#enlazar-algún-erp-de-odoo)
- [Enlazar ERP de PCH CONNECT como cliente](#enlazar-erp-de-pch-connect-como-cliente)



## Recursos utilizados en este desarrollo 🌐

### Templates para panel admin, y ecommerce
* **Admin:** (Metronic v8) Descargalo en: https://drive.google.com/file/d/1-NaTqUfvg2gU6s2ccUd19mqtkdLNhzD8/view?usp=sharing
* **Ecommerce** (Wolmart) Descargalo en: https://drive.google.com/file/d/1-SSEIkyNflYPw-_c7eGvFR1elvXkrrk7/view?usp=sharing

### Tecnologias utilizadas
* Laravel v9: https://laravel.com/docs/9.x/releases (Framework principal)

* Livewire v2: https://laravel-livewire.com (Microframework para dar reactividad al backend)

* Alpine: https://alpinejs.dev/ (Libreria lijera para dar reactividad al frontend)

## Pre-requisitos de instalación en servidor linux / Windows 📃​
Que necesitas para instalar el software y como instalarlas

```
1.- PHP v8.1+

2.- Servidor Apache o Ngnix

3.- Mysql

4.-Asegurate que el archivo php.ini de tu versión de php este la variable memory_limit como minimo en 512M

5.- Extensiones de PHP linux
sudo apt-get install php-xml
sudo apt-get install php-mbstring
sudo apt-get install php-iconv
sudo apt-get install php-intl
sudo apt-get install php-curl
sudo apt-get install php-mysql
sudo apt-get install php-gd
```

## Instalación 🔧

1.- Crear base de datos
```bash
mysql -u root
```

```bash
create database your_database
```

2.- Instalar depencias del vendor
```bash
composer install
```

3-(Windows).- Archivo .env
```bash
cp .env.example .env
```

3-(linux).- Archivo .env
```bash
copy .env.example .env
```

4.- Crea la llave del proyecto
```bash
php artisan key:generate
```

5.- Llenado de variables de entorno .env
```php
APP_NAME, APP_ENV, APP_DEBUG, APP_URL, DB_DATABASE, DB_USERNAME, DB_PASSWORD
```

6.- Storage link
```bash
php artisan storage:link
```

7.- Ejecutando las migraciones
```bash
php artisan migrate:fresh --seed
```

### Configuración de instalación restante (​opciónal)

**Correo (opcional)**
1.- Deberás de configurar las variables de entorno MAIL con tus datos de acceso de tu dominio o datos de prueba con mailtrap o el que prefieras. Esto para el funcionamiento de envíos de correo.

**Google Socialite (opcional)**
1.- Habilitar la API de google analytics en [Console Cloud Google](https://console.cloud.google.com/)
2.- Deberás de obtener tus credenciales y asignar los valores en las variables de GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET del archivo .env
3.- Activa la variable GOOGLE_CLIENT_STATUS en true para habilitar el inicio de sesión con google del archivo .env

**Notificaciones push (opcional)**
1.- Habilitar la API de pusher.com (https://dashboard.pusher.com/)
2.- Deberás de obtener tus credenciales y remplazar las variables de PUSHER_APP_ID, PUSHER_APP_KEY, PUSHER_APP_SECRET del documento .env


## Colas de trabajo / Crones ⚙️​

### Linux
**El sistema cuenta con varias colas de trabajo en app/Console/Kernel.php**
Para ejecutar estas colas de trabajo se necesitara solamente un cron, normalmente seria este:
```bash
* * * * * /usr/bin/php8.1 /var/www/turutadelproyecto/artisan schedule:run >> /dev/null 2>&1
```

### Windows
**El sistema cuenta con varias colas de trabajo en app/Console/Kernel.php**
Para ejecutar estas colas de trabajo se necesitara solamente un cron, deberás de estar en la ruta del proyecto, y ejecutar:
```bash
php artisan schedule:work
```

## Acceder a la web
**Ruta admin:** https://localhost/admin

**Acceder al e-commerce:** https://localhost

**Correo admin default:** vadeto.manager@gmail.com

**Contraseña admin default:** vadeto2020

## Enlazar algún ERP de Odoo
Actualmente el sistema esta preparado para sincronizarse con algún ERP de Odoo, se gestiona mediante una bandera que existe en el archivo .env
```php
#ERP
ODOO_STATUS=true
ODOO_URL=https://midominio.mx/

Si ODOO_STATUS esta activo, se sincronizaran todos los productos, marcas, categorias, precios, clientes, Stock y se empezarán a subir las direcciones creadas a partir de la activación del ERP cuando un cliente de de alta una dirección. 

Al ERP al que se sincronizará es el que se especifique en la variable ODOO_URL adjuntando su endpoint base
```

