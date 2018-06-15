# Portal de actividades al aire libre
Proyecto integrado 2018 para I.E.S. Polígono Sur.


### Prerequisitos

     $sudo apt -get update
   
 - Apache o servidor web
 
       $sudo apt -get install apache2
     
 - PHP 7.0+
 
       $sudo apt install -y php7.0 php7.0-cli php7.0-common php7.0-mbstring php7.0-intl php7.0-xml php7.0-mysql php7.0-mcrypt
 
 - Mysql database
 
       $sudo apt-get install mysql-server

### Instalación

- Hacer git clone y desplegar en servidor web

      $cd /var/www/html
      $sudo git clone https://github.com/javjimmir/PI_2018.git
  
- Ejecutar script en la base de datos

      $cd PI_2018/
      $mysql -u root -p < bd.sql
      
- Cambiamos el propietario y los permisos de las siguientes carpetas para la gestión de imagenes

      $cd img/

      $sudo chmod 775 empresa/
      $sudo chown www-data:www-data empresa/

      $sudo chmod 775 oferta/
      $sudo chown www-data:www-data oferta/

      $sudo chmod 775 usuario/
      $sudo chown www-data:www-data usuario/

- Configuración del archivo connection.php

      $cd PI_2018/php/
      $sudo vi connection.php   
      
      $conexion = new mysqli('localhost', 'root', 'Aqui tu pass', 'actividades');

- Acceso a la web

      http://localhost/html/PI_2018/PI_2018/

## Autores

* **Javier Jimenez** - [javjimmir](https://github.com/javjimmir)
* **Juan Delgado** - [JuandeLS3](https://github.com/JuandeLS3)
* **Fran Alcón** - [FranAlcon96](https://github.com/FranAlcon96)
* **Fredy Jerbi** - [Freddiew20](https://github.com/Freddiew20)
* **Cristian De Los Santos** - [cristianciclo](https://github.com/cristianciclo)
