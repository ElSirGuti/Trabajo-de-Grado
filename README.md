# Sistema de Gestión de Mantenimiento Proactivo para la empresa R.S.C. Services C.A. basado en Inteligencia Artificial

Este sistema web está diseñado para facilitar el mantenimiento proactivo de equipos industriales, permitiendo la gestión de activos, la generación de informes y el análisis de fallas mediante inteligencia artificial.

## Funcionalidades Principales

* **Gestión de Equipos:** Registro detallado de todos los equipos.
* **Informes de Inspección:** Creación y gestión de informes de inspección con datos de clientes.
* **Análisis de Fallas con IA:** Chatbot con `Llama 3.2:1b` para análisis de modos y efectos de fallas y determinación de causas raíz.
* **Gestión de Usuarios:** Panel para administración de usuarios (crear, editar, eliminar).
* **Login de Usuarios:** Sistema de autenticación para acceso seguro.

## Tecnologías Utilizadas

* **Backend:** PHP
* **Frontend:** HTML, CSS, JavaScript, Tailwind CSS
* **Base de Datos:** MySQL
* **IA:** Hugging face, Llama 3.2:1b

## Instalación (WampServer)

1.  **Instalar WampServer:**
    * Descarga e instala WampServer desde [http://www.wampserver.com/en/](http://www.wampserver.com/en/).
    * Asegúrate de instalar los paquetes redistribuibles de Visual C++ necesarios, como lo indica WampServer.
2.  **Clonar el repositorio:**
    * `git clone https://github.com/ElSirGuti/Trabajo-de-Grado.git`
3.  **Copiar la carpeta del proyecto a `www`:**
    * Mueve la carpeta del proyecto (`Trabajo-de-Grado`) a la carpeta `www` de WampServer (por ejemplo, `C:\wamp64\www`) y asegúrate de cambiarle el nombre de la carpeta a `Mantenimiento`.
4.  **Configurar la base de datos:**
    * Iniciar WampServer y acceder a phpMyAdmin (`localhost/phpmyadmin`).
    * Crear una nueva base de datos con el nombre configurado en tu proyecto.
    * Importar el archivo SQL `rsc_mantenimiento.sql` ubicado en la carpeta `database` para crear las tablas y datos.
    * Asegúrate de que las credenciales de la base de datos en los archivos de configuración de tu proyecto coincidan con las de MySQL en WampServer.
5.  **Acceder al proyecto en el navegador:**
    * Abre tu navegador y escribe `localhost/Mantenimiento`.
6. **Iniciar Sesión:**
    * Inicia sesión con el correo `elpepe@gmail.com` y la contraseña `Agt190604!`

## Instalación (Modelo de Llama 3.2:1b)
1. **Instalar Ollama:**
* Sigue este [tutorial](https://youtu.be/92_yb31Bqzk?si=pEl7-EdD4xHAdkYZ)

## Contacto

Andrés Gutiérrez - contacto.elsirguti@gmail.com - https://github.com/ElSirGuti