Introducción
En el proyecto inicial tienen que describir qué es lo que quieren realizar y explicar cuál es la temática del mismo. Además deberán entregar un diagrama entidad-relación junto con el código necesario para crear la BD y llenarla utilizando el framework Laravel.
Lo que se realizará es una aplicación web para la reserva de turnos en un predio de canchas. El predio puede disponer de varias canchas (asociada a una categoría c/u), cada cancha tendrá su propia franja horaria en las que el usuario podrá reservar no sólo un turno, sino que varios al mismo tiempo. Una vez reservado los turnos el usuario debería obtener un detalle de su reserva con el monto a pagar, fecha y horario.


![entidad-relacion](https://github.com/iaw-2023/TechTitans/blob/0f2574a1c1ca8dc2e6a6150f20956f0485f4f068/entidad-relacion.png)


- Respecto al [Proyecto Framework PHP - Laravel] deben describir brevemente:
    - qué entidades se podrán actualizar
Cancha
Turno 
Categoría
    - qué reportes se podrán generar o visualizar
Cuantos turnos se reservaron en un rango de fechas, según la cancha.
    - qué entidades se podrán obtener por API
Turnos
Cancha
Categoría
Se podrán obtener aplicando filtros
    - qué entidades se podrán modificar por API
Turnos
Cancha
Categoria

- Respecto al [Proyecto Javascript - React/Vue] , deben describir:
    - qué información podrá ver el usuario,
Turnos disponibles de cada cancha, su valor, tipo de superficie y si tiene techo o no.
Reservas realizadas
    - qué acciones podrá realizar, ya sea la primera vez que ingrese a la aplicación como futuros accesos a la misma
Podrá reservar uno o más turnos, modificar y cancelar turnos


- Deben entregar el en repositorio de Laravel creado a partir del template provisto para tal fin:
    - Las migration necesarias para crear la base de datos en PostgreSQL o MySQL
    - Los seeders para llenar la base de datos completa con datos de prueba, incluyendo elementos para todas las entidades
