ProyectoWeb-
============

Proyecto Web para la clase de Programacion Web CUCEI 2013-B

<div style="text-align: justify;">
  <p>
  Esta bien, la hora de la tercera entrega ha llegado y, a pesar de que he estado trabajando en el proyecto la más posible
  creo que ni siquiera he llegado a cubrir la mitad de los requisitos, además de que todavía falta por resolver un par de issues
  (principalmente el Calendario en Javascript y otro que no recuerdo que era).
  Pero bueno, lo que funciona "correctamente" hasta este punto es:
  </p>
  <ul>
    <li>
      Funciones de administrador
      <ul>
        <li>Alta, modificacion y visualización de alumnos</li>
        <li>Alta, modificacion y visualización de profesores</li>
        <li>Alta, modificacion y visualización de cursos</li>
        <li>Agregar asuetos al crear un curso</li>
        <li>Agregar asuetos despues de crear un curso</li>
      </ul>
    </li>
    <li>Autenticación básica en el login</li>
    <li>Recuperación de contraseñas</li>
  </ul>
  <p>
    Cada uno de estos tiene sus detalles. Por ejemplo:
  </p>
  <ul>
    <li>Al registrar un alumno, las carreras con datos correspondientes en la base de datos solo es una
        (Ingeniera en Computacion)</li>
    <li>La autenticación del login no maneja una sesión, simplemente verifica si existe alguna cuenta y
        redirecciona conforme segun el tipo de la cuenta (Admin, Profesor, Estudiante).</li>
    <li>La recuperacion de contraseñas no manda un correo, simplemente escribe sobre el html la notificación.</li>
  </ul>
  <p>
    Todos estos bugs y partes faltantes seran corregidos o agregados a medida que tenga chance. Intentaré apurarme lo mas
    posible.
  </p>
  <strong>Elementos faltantes:</strong>
  <ol>
    <li>Visualizacion de calificaciones del alumno</li>
    <li>Todo lo de cursos</li>
    <li>Todo lo de calificaciones</li>
    <li>Todo lo de asistencias</li>
    <li>Todo lo de listas</li>
    <li>Clonacion de cursos</li>
  </ol>
  <p>
    Basicamente todas las funciones de los profesores y de alumnos. ._.
  </p>
</div>
