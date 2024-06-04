<?php
/* try&catch per l'esecuzione delle query */
try {
    include("../../api/functions.php");

    /* mi connetto al DB */
    $conn = new mysqli("mariadb", "root", "root", "DBOrdini", 4003);

    /* controllo la presenza di tutti i parametri necessari */
    if (isset($_POST["email"]) && isset($_POST["emailCheck"]) && isset($_POST["password"]) && isset($_POST["passwordCheck"]) && isset($_POST["ragioneSociale"]) && isset($_POST["pIva"]) && isset($_POST["prefisso"]) && isset($_POST["numeroTelefono"]) && isset($_POST["indirizzo"]) && isset($_POST["numeroCivico"]) && isset($_POST["comune"]) && isset($_POST["cap"]) && isset($_POST["provincia"]) && isset($_POST["stato"])) {
        /* salvo email */
        $email = $_POST["email"];

        /* controllo che sia un formato di email valido e che sia uguale alla email scritta per verifica */
        if (filter_var($email, FILTER_VALIDATE_EMAIL) && strval($email) === strval($_POST["emailCheck"])) {

            /* controllo che la mail non sia stata già utilizzata */
            if (intval(execQuery($conn, "SELECT COUNT(1) FROM Clienti WHERE Email='$email';")[0][0]) === 0) {
                /* salvo la password */
                $password = $_POST["password"];

                /* controllo che la password sia uguale a quella scritta per verifica */
                if (strval($password) === strval($_POST["passwordCheck"])) {
                    /* salvo la partita iva */
                    $partitaIva = $_POST["pIva"];

                    /* genero un salt casuale */
                    $salt = generateString(16);
                    /* genero l'hash della password usando SHA-256 con 10000 rounds e il salt generato */
                    $hash = explode("$", crypt("$email:$password", '$5$rounds=10000$' . $salt . '$'))[4];

                    /* controllo che la partita iva sia del formato corretto */
                    if (strlen($partitaIva) === 11) {

                        /* controllo che la partita iva non sia già stata utilizzata */
                        if (intval(execQuery($conn, "SELECT COUNT(1) FROM Clienti WHERE PartitaIva='$partitaIva';")[0][0]) === 0) {
                            /* salvo l'indirizzo */
                            $indirizzo = $_POST["indirizzo"];
                            /* salvo il numero civico */
                            $numeroCivico = $_POST["numeroCivico"];
                            /* salvo il comune */
                            $comune = $_POST["comune"];
                            /* salvo la provincia */
                            $provincia = $_POST["provincia"];
                            /* salvo il cap */
                            $cap = $_POST["cap"];
                            /* salvo lo stato */
                            $stato = $_POST["stato"];

                            /* controllo se esiste l'indirizzo salvando un array vuoto se non esiste oppure un vettore con le coordinate se esiste */
                            $coordinate = verificateAddress($indirizzo, $numeroCivico, $comune, $provincia, $cap, $stato);

                            /* se le cordinate sono state passate e quindi l'indirizzo esiste */
                            if (count($coordinate) === 2) {
                                /* salvo la ragione sociale */
                                $ragioneSociale = $_POST["ragioneSociale"];
                                /* salvo il prefisso telefonico */
                                $prefisso = $_POST["prefisso"];
                                /* salvo il numero di telefono */
                                $numeroTelefono = str_replace([".", " "], "", $_POST["numeroTelefono"]);

                                /* cerco la matricola dell'Agente con meno clienti */
                                if ($matricola = execQuery($conn, "SELECT a.Matricola FROM Agenti a LEFT JOIN Clienti c ON a.Matricola = c.MatricolaAgente GROUP BY a.Matricola ORDER BY COUNT(c.IdCliente) ASC LIMIT 1")[0][0]) {
                                    /* eseguo la query per inserire nel DB il nuovo cliente */
                                    $conn->query("INSERT INTO Clienti (`IdCliente`, `Email`, `Password`, `PasswordSalt`, `RagioneSociale`, `PartitaIva`, `Prefisso`, `NumeroTelefono`, `Indirizzo`, `NumeroCivico`, `Comune`, `Provincia`, `CAP`, `Stato`, `Lat`, `Lng`, `MatricolaAgente` ) 
                                                      VALUES (UUID(), '$email', '$hash', '$salt', '$ragioneSociale', '$partitaIva', '$prefisso', '$numeroTelefono', '$indirizzo', '$numeroCivico', '$comune', '$provincia', '$cap', '$stato', '$coordinate[0]', '$coordinate[1]', '$matricola' );");

                                    /* azzero le sessioni precedenti se presenti */
                                    session_unset();
                                    /* faccio partire la sessione per carrello e altre pagine in generale con l'id del cliente */
                                    session_start();
                                    /* salvo nella sessione l'id del cliente */
                                    $_SESSION["IdCliente"] = execQuery($conn, "SELECT IdCliente FROM Clienti WHERE `Email`='$email' AND `Password`='$hash' AND `PasswordSalt`='$salt' AND `RagioneSociale`='$ragioneSociale' AND `PartitaIva`='$partitaIva' AND `Prefisso`='$prefisso' AND `NumeroTelefono`='$numeroTelefono' AND `Indirizzo`='$indirizzo' AND `NumeroCivico`='$numeroCivico' AND `Comune`='$comune' AND `Provincia`='$provincia' AND `CAP`='$cap' AND `Stato`='$stato' AND `Lat`=$coordinate[0] AND `Lng`=$coordinate[1] AND `MatricolaAgente`='$matricola';")[0][0];

                                    /* stampo messaggio e video e reindirizzo alla Homepage */
                                    echo "Account registrato con successo! Sarai reindirizzato a breve...<br><script>setTimeout(() => {window.location.href='../../index.php';}, 1000);</script>";
                                } else {
                                    /* non ci sono Agenti nel DB */
                                    throw new Error("Agenti non presenti quindi non è possibile effettuare ordini");
                                }
                            } else {
                                /* indirizzo non trovato */
                                throw new Error("Indirizzo non trovato");
                            }
                        } else {
                            /* partita iva già presente nel DB */
                            throw new Error("la Partita Iva è già stata utilizzata");
                        }
                    } else {
                        /* partita iva del formato non corretto */
                        throw new Error("Partita Iva non corretta");
                    }
                } else {
                    /* password e password di controllo non uguali */
                    throw new Error("le Password non combaciano");
                }
            } else {
                /* email ed email di controllo non uguali */
                throw new Error("la Email è già stata utilizzata");
            }
        } else {
            /* email in un formato non valido */
            throw new Error("le Email sono in un formato errato o non combaciano");
        }
    } else {
        /* parametri mancanti oppure in un formato errato */
        throw new Error("parametri errati o non completi");
    }
} catch (Error $e) {
    /* stampo a video l'errore se generato con un bottone per tornare al form di registrazione (i parametri vanno persi) */
    die("Errore:" . $e->getMessage() . ", ritorna alla pagina di registrazione! <a href='../signup.php' ><br><button style='padding: 5px; margin: 5px; gap: 5px;'>Torna Indietro</button></a>");
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>OrderHub | Registrati</title>
    <link rel="stylesheet" href="../styleProfile.css">
</head>

<body></body>

</html>