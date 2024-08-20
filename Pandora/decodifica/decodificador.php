<?php
// Función para leer el archivo CSV y convertirlo en un array
function leerCSV($archivo)
{
    $datos = [];

    // Abrir el archivo CSV para lectura
    if (($gestor = fopen($archivo, "r")) !== FALSE) {

        // Leemos cada fila, y acumulamos resultados en $datos
        while (($fila = fgetcsv($gestor, 1000, ",")) !== FALSE) {
            $datos[] = $fila;
        }

        // Cerrar el archivo
        fclose($gestor);
    }

    return $datos;
}

// Esta es la principal función de este ejercicio
function decodificarNumero($sistemaCodificacion, $puntuacionCodificada)
{
    // Tenemos que localizar en qué base viene codificado, lo sabemos por la cantidad de caracteres que trae la primera variable
    $base = strlen($sistemaCodificacion);

    // Obtenemos la longitud de la segunda variable, para saber cuantas iteracciones debe hacer el bucle
    $longitud = strlen($puntuacionCodificada);

    // Inicializamos la puntuación a 0
    $valorDecimal = 0;

    // Procesamos cada carácter del número codificado
    for ($i = 0; $i < $longitud; $i++) {
        // Procesamos los caracteres, uno a uno
        $caracter = $puntuacionCodificada[$i];

        // localizamos su posición para saber en qué potencia de base nos encontramos
        $valor = strpos($sistemaCodificacion, $caracter);

        // Multiplicamos el valor asignado del caracter, y multiplicarlo por la potencia de base que corresponda
        $valorDecimal += $valor * pow($base, $longitud - $i - 1);
    }

    return $valorDecimal; // Retornamos el valor decimal
}

function ordenarPorPuntuacion($array)
{
    // Usamos usort con una función de comparación personalizada
    usort($array, function ($a, $b) {
        return $b['puntuacion'] <=> $a['puntuacion']; // Orden descendente
    });

    return $array; // Devolvemos el array ya ordenado
}

// Leer el archivo CSV y convertirlo en un array
$archivo_csv = 'puntuaciones.csv';
$puntuaciones = leerCSV($archivo_csv);

$array_puntuaciones = [];
$i = 0;
// Procesar cada número y realizar la conversión
foreach ($puntuaciones as $dato) {
    $jugador = $dato[0];
    $sistemaCodificacion = $dato[1];
    $puntuacionCodificada = $dato[2];

    // Decodificar el número. Este es el cerebro de esta aplicación
    $resultado = decodificarNumero($sistemaCodificacion, $puntuacionCodificada);
    $array_puntuaciones[$i]['jugador'] = $jugador;
    $array_puntuaciones[$i]['puntuacion'] = $resultado;
    $i++;
}
$jugadoresOrdenados = ordenarPorPuntuacion($array_puntuaciones);
foreach ($jugadoresOrdenados as $key => $value) {
    echo "Jugador: {$value['jugador']} tiene {$value['puntuacion']} puntos<br>";
}
exit;
