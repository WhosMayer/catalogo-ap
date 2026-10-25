# Catálogo-ap

Aplicación web en PHP y MySQL para el registro de usuarios y la consulta de productos en promoción, con integración y entrega continua mediante Git y Jenkins.

## Integrantes

| Nombre | Rol |
| --- | --- |
| Mayer Gómez | Líder del grupo |
| Santiago Salamanca | Integrante |
| Milton Muñoz | Integrante |

## Funcionalidades

- Registro de usuarios con validación por campo. Si hay errores, el formulario conserva la información ingresada y muestra dónde corregirla.
- Catálogo de 24 ciudades de Colombia, Italia y Francia. Al seleccionar o escribir una ciudad, se completa su país. La relación también se valida en el servidor.
- Inicio y cierre de sesión.
- Consulta de productos en promoción y botones «Ver detalles» con descripción, categoría, precios, vigencia y referencia del producto.

## Instalación

Requisitos: XAMPP con Apache, MySQL y PHP, además de Git para trabajar con el repositorio.

1. Clonar el repositorio:
   ```bash
   git clone https://github.com/WhosMayer/catalogo-ap.git
   ```
2. Ubicar el proyecto en `C:/xampp/htdocs/catalogo-ap`.
3. Iniciar Apache y MySQL desde XAMPP.
4. Importar `sql/database.sql` una sola vez. Repetir la importación puede duplicar los productos de ejemplo.
5. Abrir [Catálogo-ap](http://localhost/catalogo-ap/), registrarse e iniciar sesión.

La conexión predeterminada utiliza el servidor `127.0.0.1`, puerto `3306`, base de datos `catalogo_db`, usuario `root` y contraseña vacía. Puede configurarse con las variables `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` y `DB_PASSWORD` del proceso de Apache.

Los productos se muestran únicamente cuando están activos y dentro de su periodo de promoción.

## Integración con Jenkins

- Repositorio: [WhosMayer/catalogo-ap](https://github.com/WhosMayer/catalogo-ap).
- Rama: `main`.
- Tipo de tarea: **Pipeline script from SCM**, con Git como origen y `Jenkinsfile` como archivo de configuración.
- Tarea local: [catalogo-ap](http://localhost:8080/job/catalogo-ap/).

Jenkins consulta el repositorio cada dos minutos. Cuando detecta cambios, descarga el código, revisa la sintaxis PHP, ejecuta las pruebas de registro, genera un ZIP y publica la aplicación en XAMPP. Si fallan las comprobaciones, no realiza la publicación.

Parámetros de la tarea:

| Parámetro | Valor |
| --- | --- |
| `PHP_EXE` | `C:/xampp/php/php.exe` |
| `DEPLOY_DIR` | `C:/xampp/htdocs/catalogo-ap` |

El historial de ejecuciones, los cambios y los resultados se consultan en Jenkins. El archivo publicado `version.txt` identifica la ejecución y el commit correspondientes a la aplicación. Se conservan hasta 20 ejecuciones.

## Pruebas

Ejecutar desde la carpeta del proyecto:

```powershell
C:/xampp/php/php.exe ci/check.php
```

Para comprobar la aplicación:

1. Enviar el registro con un correo inválido y contraseñas distintas; verificar que los datos permanezcan y se señalen ambos errores.
2. Corregir los campos y completar el registro.
3. Seleccionar Roma, Bogotá y París; comprobar Italia, Colombia y Francia respectivamente.
4. Probar una ciudad desconocida y un documento o correo ya registrado; comprobar los mensajes de validación.
5. Iniciar sesión, abrir los detalles de distintos productos y cerrar sesión.
6. Guardar un cambio en `main` y comprobar su ejecución en Jenkins y publicación en la aplicación.
