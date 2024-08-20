Este repositorio contiene dos carpetas, la carpeta "decodifica" que es el código para decodificar las puntuaciones de los jugadores, primer ejercicio.
Y la carpeta "citas_online" que es la aplicación de citas para la "clinica"
Con respecto a este último ejercicio, he creado un archivo llamado database.sql, y para poder hacer pruebas he anulado la ejecución de dos cosas, que sí tienen que estar activas la primera vez que se ejecuta.
Se trata de la línea 2 de este archivo, que lo que hace es crear una base de datos llamada "clinica"
Y después, es una sección entre las líneas 41 y 69, que lo que hace es crear un procedimiento para tener "preparados" los horarios de funcionamiento de la clínica.
El prodedimiento InsertarHorarios() se llama al final, y claro, la primera vez hay que tener creado este procedimiento. Pero las veces siguientes, es suficiente con llamarlo para que se ejecute.
Espero haberme explicado bien.

PD. como he hecho estos ejercicios en "local", en el momento de enviar el mail de confirmación al paciente da error porque yo no he configurado un servidor para el envío del email, pero entiendo que al ser una aplicación de prueba,
se entiende que sí se habría configurado el envío de email desde un servidor específico.
