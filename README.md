# Práctica de Desarrollo de Aplicaciones en Entorno Servidor

## Consideraciones Iniciales:

- [x] Crear una tabla para administradores.
- [x] Agregar un campo de contraseña (`password`) en la tabla de solicitantes.
- [x] Calcular la diferencia entre la fecha de nacimiento y la fecha de alta para obtener el tiempo transcurrido.

## Consideraciones sobre Cursos:

- Los cursos tienen un número de plazas determinado y no se pueden admitir más solicitantes de lo permitido.
- Se establece un baremo para asignar puntos a los solicitantes según ciertos criterios.
   1. Mayor cantidad de puntos.
   2. Menor número de cursos realizados.
   3. Cumplir ciertas condiciones específicas.

## Usuarios y Funcionalidades Asociadas:

### 1. Usuarios sin Registro:
   - Visualizar cursos abiertos.

### 2. Usuarios Básicos (Solicitantes):
   - Registro/Login.
   - Visualizar cursos abiertos.
   - Suscripción a cursos abiertos.

### 3. Administrador:
   - No realiza registro (administradores se crean en phpMyAdmin) pero sí login.
   - Listar todos los cursos.
   - Abrir/Cerrar cursos mediante checkbox.
   - Asignar vacantes a los solicitantes.
   - Eliminar/Agregar cursos.

## Almacenamiento de Datos Adicional:

- Se debe guardar la información de los cursos con los puntos asignados en un archivo de texto (`txt`).

**Nota:** La parte relacionada con el correo electrónico (axigen) no se aborda en esta práctica.

