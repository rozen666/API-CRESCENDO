<?php
$nombre_fichero = '\\10.130.7.4\TONIVISA_Documentos\XML\FACTURA\2023\09\FACTURA_FEH_204442.xml'
echo $nombre_fichero;
echo "<br><br><br>";

if (file_exists($nombre_fichero)) {
    echo "El fichero <b> $nombre_fichero existe </b>";
} else {
    echo "El fichero <b> $nombre_fichero </b> no existe";
}
?>