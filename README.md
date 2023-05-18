<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


# Indice
<a href="#indice"></a>

1. [Deployment](#deployment)
2. [Contribuyentes](#contribuyentes)

# Deployment
<a href="#deployment"></a>
1) Requerimientos 
    * PHP 8.1 o superior
    * Composer [Web oficial](https://getcomposer.org/download/)
    * MySQL [Web Oficial](https://www.mysql.com/downloads/)

2) Instalación
    * Descargue el repositorio en su máquina local
    * Instalar dependencias en el directorio **Backend**
     
        ```bash
        $ composer install
        ```

    * Cree una base de datos del proyecto (( **mercadocountry** ))
    
        ```bash
        CREATE DATABASE mercadocountry;
        ```

    * En el directorio **Backend** ejecute
        
        ```bash
        $ cp .env.example .env && php artisan key:generate && php artisan migrate
        ```

    * Si ha tenido problemas para realizar las migraciones, configure sus variables de entorno (( **.env** ))

        ```yaml
            DB_CONNECTION=mysql
            DB_HOST=your_host_database
            DB_PORT=3306
            DB_DATABASE=mercadocountry
            DB_USERNAME=your_user
            DB_PASSWORD=your_password
        ```

4) Ejecución del servidor
    * Ejecute en el directorio **Backend**

        ```bash
        $ php artisan serve
        ```

    * Ingresa a la <a href="http://localhost:8000" target="_blank">URL Proporcionada</a>

# Contribuyentes
<a href="#contribuyentes"></a>

[![linkedin-shield-alansanchez]][linkedin-alansanchez-url] [![portfolio]][portfolio-alansanchez] <br>
[![linkedin-shield-luisfelipe]][linkedin-luisfelipe-url]  <br>
[![linkedin-shield-lorenzorueda]][linkedin-lorenzorueda-url]  <br>
[![linkedin-shield-rafaellopez]][linkedin-rafaellopez-url]  <br>


<!-- Enlaces LinkedIn -->

[portfolio]: https://img.shields.io/badge/-Portfolio-orange?style=for-the-badge&logo=appveyor

[linkedin-shield-alansanchez]: https://img.shields.io/badge/-Alan_Sanchez-black.svg?style=for-the-badge&logo=linkedin&color=0A66C2
[linkedin-alansanchez-url]: https://linkedin.com/in/alansanchez96
[portfolio-alansanchez]: https://dev-alansan.netlify.app/

[linkedin-shield-luisfelipe]: https://img.shields.io/badge/-Luis_Felipe-black.svg?style=for-the-badge&logo=linkedin&color=0A66C2
[linkedin-luisfelipe-url]: https://www.linkedin.com/in/luis-felipe-fern%C3%A1ndez-betancur-474639267/

[linkedin-shield-lorenzorueda]: https://img.shields.io/badge/-Lorenzo_Rueda-black.svg?style=for-the-badge&logo=linkedin&color=0A66C2
[linkedin-lorenzorueda-url]: https://www.linkedin.com/in/lorenzo-rueda-582758263/

[linkedin-shield-rafaellopez]: https://img.shields.io/badge/-Rafael_Lopez-black.svg?style=for-the-badge&logo=linkedin&color=0A66C2
[linkedin-rafaellopez-url]: https://www.linkedin.com/in/rafael-lopez-942610247/

<br>
<br>

<p align="left"><a href="#indice">Volver al Indice</a></p>
