<?php
/* file con funzione per download immagine */
include("functions.php");

/* se il parametro è presente */
if (isset($_GET['id'])) {

    try {
        /* mi connetto al DB */
        $conn = @new mysqli('mariadb', 'Immagini', 'Immagini', 'DBOrdini', 4003);
    } catch (Exception $e) {
        /* errore nella connessione al DB */
        die("Errore nella connessione: " . $e->getMessage() . ".");
    }

    /* prendo immagine dal DB tramite l'IdProdotto */
    $image = downloadImage($conn, $_GET['id']);

    /* se l'immagine è stata trovata e selezionata */
    if ($image) {
        /* rendo la pagina una immagine */
        header("Content-Type: image/jpeg");
        /* stampo i byte della immagine */
        echo $image;
    } else {
        /* lascio la pagina una pagina html/php e stampo non trovata */
        echo "Immagine non trovata.";
    }
} else {
    /* parametro non presente */
    echo "ID prodotto non specificato.";
}
