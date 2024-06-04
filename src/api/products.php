<?php
/* rendo la pagina un JSON */
header('Content-Type: application/json; charset=utf-8');

/* funzione per eseguire query */
include("functions.php");

try {
    /* mi connetto al DB */
    $conn = @new mysqli("mariadb", "root", "root", "DBOrdini", 4003);
} catch (Exception $e) {
    /* errore nella connessione al DB */
    die("Errore nella connessione: " . $e->getMessage() . ".");
}

/* vettore con le condizione della query per prendere i prodotti */
$conditions = array();
/* vettore con le condizioni della query2 per prendere il valore massimo e minimo di prezzo e massimo quantita */
$conditions2 = array();
/* ordine predefinito di stampa dei prodotti, Nome Alfabetico */
$order = "Nome ASC";
/* categoria predefinita (0=tutte le categorie) */
$category = 0;

/* creo la query per prendere i prodotti */
$query = "SELECT IdProdotto, Prodotti.Nome, Prodotti.Descrizione, PrezzoUnitario, QuantitaRimasta, Prodotti.IdCategoria FROM Prodotti ";
/* creo la query per prendere il valore massimo e minimo di prezzo e massimo di quantità */
$query2 =  "SELECT MIN(PrezzoUnitario) min, MAX(PrezzoUnitario) max, MAX(QuantitaRimasta) dispMax FROM Prodotti";
/* creo la query per prendere le categorie dal DB */
$query4 = "SELECT IdCategoria, Nome FROM Categorie ORDER BY Nome ASC;";
/* creo la query per prendere il numero di prodotti nel DB */
$queryNum = "SELECT COUNT(*) FROM Prodotti";

/* se la categoria è stata passata come parametro, se è un numero e non è zero (che indica tutte le categorie) */
if (isset($_GET["categoria"]) && gettype(intval($_GET["categoria"])) === "integer" && intval($_GET["categoria"]) >= 1) {
    /* salvo condizione per query e query2 */
    array_push($conditions, "IdCategoria = " . $_GET["categoria"]);
    array_push($conditions2, "IdCategoria = " . $_GET["categoria"]);

    /* salvo la categoria attuale (quella passata) */
    $category = intval($_GET["categoria"]);
}
/* se il prezzo minimo è stato passato ed è un numero */
if (isset($_GET["prezzomin"]) && gettype(floatval($_GET["prezzomin"])) === "double") {
    /* salvo condizione per query */
    array_push($conditions, "PrezzoUnitario >= " . $_GET["prezzomin"]);
}
/* se il prezzo massimo è stato passato ed è un numero */
if (isset($_GET["prezzomax"]) && gettype(floatval($_GET["prezzomax"])) === "double") {
    /* salvo condizione per query */
    array_push($conditions, "PrezzoUnitario <= " . $_GET["prezzomax"]);
}
/* se la quantità massimo è stata passata ed è un numero */
if (isset($_GET["quantitamin"]) && gettype(intval($_GET["quantitamin"])) === "integer") {
    /* salvo condizione per query e query2 */
    array_push($conditions, "QuantitaRimasta >= " . $_GET["quantitamin"]);
    array_push($conditions2, "QuantitaRimasta >= " . $_GET["quantitamin"]);
}
/* se è stato passato il sorting */
if (isset($_GET["ordine"])) {
    /* traduco il numero nell'ordine relativo (in modo che sia in linguaggio SQL) */
    switch ($_GET["ordine"]) {
        case 2:
            /* Nome Antialfabetico */
            $order = "Nome DESC";
            break;
        case 3:
            /* Prezzo Crescente */
            $order = "PrezzoUnitario ASC";
            break;
        case 4:
            /* Prezzo Decrescente */
            $order = "PrezzoUnitario DESC";
            break;
        case 5:
            /* QuantitaRimasta Crescente */
            $order = "QuantitaRimasta ASC";
            break;
        case 6:
            /* QuantitaRimasta Decrescente */
            $order = "QuantitaRimasta DESC";
            break;
    }
}

/* se ci sono condizioni per la query */
if (count($conditions) > 0) {
    /* salvo nella query e nella queryNum le condizioni in SQL (servono le stesse condizioni per entrambe) */
    $query .= " WHERE " . implode(" AND ", $conditions);
    $queryNum .= " WHERE " . implode(" AND ", $conditions) . ";";
}
/* se ci sono condizioni per la query2 */
if (count($conditions2) > 0) {
    /* salvo nella query2 le condizioni tradotte in SQL */
    $query2 .= " WHERE " . implode(" AND ", $conditions2);
}

/* salvo il numero di prodotti in base alle condizioni */
$numProd = execQuery($conn, $queryNum)[0][0];
/* numero di prodotti da mostrare per volta (per pagina) */
$numProdPerPag = 9;
/* offset per selezionare la pagina da mostrare  */
$offset = 0;
/* vettore con i numeri di pagine vicini a quella corrente (ne contiene da 1 a 5 (se esistono più di 5 pagine, la 1 e 5 sono sempre presenti agli estremi del vettore)) */
$pages = array();
/* numero di pagine formate da i prodotti */
$numPagProd = intval(ceil($numProd / $numProdPerPag));

/* riempo il vettore da pagina 1 fino ad un max di pagina 5 (il vettore ha massimo 5 elementi) */
for ($i = 0; $i < $numPagProd && $i < 5; $i++) {
    /* salvo i numeri di pagina */
    array_push($pages, intval($i + 1));
}

/* se è presente più di una pagina */
if (count($pages) > 1) {
    /* salvo nell'ultima cella l'ultima pagina */
    $pages[count($pages) - 1] = $numPagProd;
}

/* se la pagina è stata passata come parametro, è un numero ed è maggiore di 1 (la prima pagina) */
if (isset($_GET["pagina"]) && gettype(intval($_GET["pagina"])) === "integer" && intval($_GET["pagina"]) > 1) {
    /* salvo numero di pagina richiesta */
    $pagina = intval($_GET["pagina"]);

    /* offset ha un numero in meno della pagina per questioni di linguaggio SQL*/
    /* pagina=1 offset=0, pagina=2 offset=1, ... */
    $offset = $pagina - 1;
    /* se la pagina richiesta è maggiore della numero 3 e minore o uguale all'ultima pagina e se ci sono più di 5 pagine */
    if ($pagina > 3 && $numPagProd > 5 && $pagina <= $numPagProd) {
        /* se la differenza tra il numero totale di pagine e la pagina corrente è inferiore a 2 */
        if (($numPagProd - $pagina) < 2) {
            /* modifica il numero di pagina per garantire che ci siano sempre almeno 2 pagine di buffer tra la pagina corrente e l'ultima pagina. */
            $pagina -= 2 - abs($numPagProd - $pagina);
        }
        /* incrementa le pagina tra la prima e l'ultima del numero di pagina per portarle ai valori corretti */
        for ($i = 1; $i < count($pages) - 1; $i++) {
            /* -3 perchè si shiftano i valori dalla pagina numero 4 */
            $pages[$i] += $pagina - 3;
        }
    }
}

/* salvo i prodotti con le condizioni passate */
$prodotti = execQuery($conn, $query . " ORDER BY Prodotti.$order LIMIT $numProdPerPag OFFSET " . ($offset * $numProdPerPag) . ";", true);
/* salvo i parametri di massimo e di minimo di prezzo e di massimo di quantità */
$stats = execQuery($conn, $query2, true);
/* salvo le categorie del DB */
$categories = execQuery($conn, $query4, true);

/* salvo prezzo minimo */
$min = $stats[0]["min"];
/* salvo prezzo massimo */
$max = $stats[0]["max"];
/* salvo quantità massimo */
$dispMax = $stats[0]["dispMax"];

/* creo array per ritornare il JSON */
$returnArray = array();

/* salvo i parametri rendendo i vettori dei JSON */
array_push($returnArray, json_encode($prodotti));
array_push($returnArray, json_encode($categories));
array_push($returnArray, $pages);
array_push($returnArray, intval($min));
array_push($returnArray, intval($max));
array_push($returnArray, intval($dispMax));
array_push($returnArray, intval($category));

/* stampo l'oggetto JSON */
echo json_encode($returnArray);
