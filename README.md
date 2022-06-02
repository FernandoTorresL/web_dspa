# Project: Portal Web DSPA 
## Projecto personal para IMSS, México

## Introduction

This project is a personal project operated by workers of the _Coordinación de Afiliación - División de Soporte a los Procesos de Afiliación_ office on Instituto Mexicano del Seguro Social (IMSS, DIR, CA, DSPA)

This project manage the request and applications to the access control's accounts on one of the most important database of the Institute. With this system, users can create, modify, search and send to another department all of these applications.

Before this system was implemented, this office only use a lot of sheets, paper, memos, cabinets and archive to manage all of this information. It takes weeks, sometimes months to accomplish a request from the origin to an answer to this request. Now, it takes minutes to present one request to another department and some more minutes to give an full answer to the origin. And of course, the search capabilities of a good database design made fast queries and reports that in the past last several weeks to finish.

### Technology used

This project was build with the use of: 

- PHP, Laravel
- XAMPP stack (Apache, MySQL/MariaDB, PHP)

This project has several previous releases with different technologies; some are:

- Ago 2018 version: [ca-dspa repo](https://github.com/FernandoTorresL/ca-dspa)
	First steps on Laravel.

- Oct 2016 version: [dspa repo](https://github.com/FernandoTorresL/dspa)
First approach. Initially with pure php, later using MVC model.


Here are some of the screencaptures of the latest version: 

![Imgur](https://i.imgur.com/bFp4HBl.png)
![Imgur](https://i.imgur.com/GX2zfaV.png)
![Imgur](https://i.imgur.com/QxCuBU3.png)

---

## Installation.

Make sure you had this previous installed software:
- XAMPP
- A Internet browser like Google Chrome
- Laravel framework
- Adobe Acrobat Reader plugin

Make sure you have access to MySQL/MariaDB server and can create databases and tables (review your permissions).

### Start server
Start the Apache server service and MYSQL service. This step can vary from MacOS and Windows or other systems.

### Clone this repo
You can use and change *<my_folder>* on this instruction to create a new folder 
```
git clone --single-branch -branch feature/release-0.4.0 git@github.com:FernandoTorresL/web_dspa.git <my_folder>
```

### Create your .env file (only .env.example on github)
```
vi .env
```
Using **_.env.example_** file, you can set your own access credentials to database and configuration values.

### Change to working directory and install a development version
```
cd <my_folder>
composer update
php artisan key:generate
php artisan storage:link
php artisan migrate:fresh
```

### Execute
Now, open a new terminal, and execute with
```
php artisan serve --host=<ip_host> --port=<your_port>
```

Finally, you can view the project on **localhost:<your_port>** or **<ip_host>:<your_port>** on a browser.

Remember that you must use the same values that you define on your **_.env_** file.

------

#### Follow me 
[fertorresmx.dev](https://fertorresmx.dev/)

#### :globe_with_meridians: [Twitter](https://twitter.com/FerTorresMx), [Instagram](https://www.instagram.com/fertorresmx/): @fertorresmx
