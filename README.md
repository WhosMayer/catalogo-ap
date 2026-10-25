# Catálogo-ap — práctica Git + Jenkins

## Cambios implementados
- Registro con valores conservados tras errores, resumen con enlaces, mensaje junto a cada campo y foco en el primer error. Las contraseñas se conservan solo en la respuesta del formulario, nunca en logs ni archivos; usar HTTPS fuera del equipo local.
- Catálogo de 24 ciudades de Colombia, Italia y Francia. Acepta selección o escritura con diferencias de mayúsculas y tildes. El país se calcula también en PHP: modificar el país enviado no altera el guardado.
- Cada botón Ver detalles despliega los datos de su producto sin depender de JavaScript ni de Bootstrap.
- Cambio visible en inicio: insignia «Versión 1.1 · Registro mejorado y detalles de productos».
- Pipeline con checkout del repositorio configurado, revisión PHP, pruebas, artefacto y publicación en XAMPP. Solo publica después de aprobar las pruebas.

## Ejecutar en XAMPP
1. Copiar esta carpeta a `C:/xampp/htdocs/catalogo-ap` y arrancar Apache y MySQL.
2. Importar `sql/database.sql` en MySQL (una sola vez; los INSERT originales duplican productos al repetirse).
3. Abrir http://localhost/catalogo-ap/ y crear una cuenta.
4. La conexión usa por defecto 127.0.0.1, base catalogo_db, usuario root y contraseña vacía, como el proyecto original. Se puede configurar mediante variables del proceso Apache: DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASSWORD. No incluir claves en Git.
5. Las promociones originales tienen fechas de 2026: si ya expiraron, actualizar la vigencia en la base de datos para la demostración.

## Grupo de 3 o 4 integrantes
Completar con nombres reales antes de entregar:

| Rol | Responsable | Trabajo y evidencia |
| --- | --- | --- |
| Líder / integración | Por asignar | Distribuir tareas, revisar cambios, integrar ramas, configurar Jenkins y registrar builds |
| Registro | Por asignar | Persistencia de campos, errores y ciudades; pruebas de inscripción |
| Catálogo | Por asignar | Detalles, cambio visible y pruebas de productos |
| Pruebas / entrega (opcional) | Por asignar | Verificación, capturas y documentación |

Con 3 integrantes, el líder asume también pruebas y entrega. Cada integrante debe realizar sus propios commits reales; el historial preparado aquí no sustituye la colaboración del grupo.

## Conectar Git y Jenkins
Requisitos: Jenkins con plugins Pipeline, Git y PowerShell; agente Windows con etiqueta `windows`, Git y PHP (pdo_mysql), XAMPP. El usuario del servicio Jenkins necesita permiso de escritura en la carpeta de publicación.

El ZIP original incluye historial Git y el remoto https://github.com/jhonrot/catalogo-ap.git. Confirmar que pertenece al grupo antes de publicar. Se conservó ese historial y se añadieron dos commits de implementación; no se ha realizado push. El archivo catalogo-ap-historial.bundle permite recuperar el historial con git clone catalogo-ap-historial.bundle catalogo-ap.

1. Crear un repositorio vacío en su proveedor Git y asociarlo desde esta carpeta:
   ```powershell
   git remote set-url origin URL_REAL_DEL_REPOSITORIO
   git push -u origin main
   ```
2. Crear en Jenkins un trabajo **Pipeline**. Seleccionar **Pipeline script from SCM → Git**, URL real del repositorio, credenciales si es privado, rama `*/main` y Script Path `Jenkinsfile`.
3. Ejecutar **Build Now**. Revisar los parámetros PHP_EXE y DEPLOY_DIR antes de ejecutar. La publicación sobrescribe archivos de la aplicación en esa carpeta: usar una carpeta dedicada a esta práctica.
4. Desde el primer build, el pipeline consulta Git cada 2 minutos. Solo genera otro build cuando encuentra cambios. Para la demostración, editar el texto de versión de index.php, hacer commit y push; esperar la consulta o pulsar Build Now.
5. Abrir la aplicación en XAMPP y verificar la insignia cambiada. Abrir http://localhost/catalogo-ap/version.txt para asociar el build y commit publicados.
6. En Jenkins revisar **Build History**, **Changes**, **Console Output** y los artefactos de cada ejecución. Guardar capturas del primer build y del build del cambio visible. Jenkins guarda hasta 20 ejecuciones.

## Ramas y revisión del líder
Cada integrante crea una rama desde main (`git switch -c feature/registro`, por ejemplo), realiza su implementación, pruebas, commit y push. El líder revisa y une las ramas mediante pull requests o merge. Ejecutar `git log --oneline --graph --all` para evidenciar la integración. Evitar que dos personas modifiquen el mismo archivo sin coordinarse.

## Verificación
```powershell
C:/xampp/php/php.exe ci/check.php
```
Pruebas manuales de aceptación:
- Introducir datos completos, correo incorrecto y confirmación distinta: al enviar deben mantenerse todos los datos y aparecer errores junto al correo y confirmación. Corregir y registrarse.
- Repetir documento/correo existentes: el error debe indicar el campo; los datos permanecen.
- Seleccionar Roma, Bogotá y París: deben completar Italia, Colombia y Francia. Escribir una ciudad desconocida: debe rechazarse. Borrar la ciudad: se limpia el país.
- Iniciar sesión y abrir detalles de dos productos: nombre, descripción, categoría, precios, vigencia y referencia deben corresponder a cada tarjeta. Cerrar sesión.
- Hacer push de un cambio de texto y comprobar nuevo build y texto publicado.

## Estado de entrega
El código y el repositorio local están preparados. La ejecución real en Jenkins, el push al repositorio remoto y las capturas de su historial requieren la URL del repositorio, acceso al Jenkins del grupo y servicios disponibles. No se incluyen capturas ni builds ficticios.

