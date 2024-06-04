<?php
/* try&catch per l'esecuzione delle query */
try {
    include("../../api/functions.php");

    /* mi connetto al DB */
    $conn = new mysqli("mariadb", "root", "root", "DBOrdini", 4003);

    /* controllo la presenza di tutti i parametri necessari */
    if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["utenza"])) {
        /* salvo email */
        $email = $_POST["email"];
        /* salvo utenza */
        $utenza = $_POST["utenza"];

        /* controllo la validità dell'utenza passata */
        if (strval($utenza) === "cliente" || strval($utenza) === "agente") {
            /* salvo la tabella in base all'utenza */
            $table;
            /* salvo il nome da stampare in caso di successo */
            $name;
            /* salvo l'identificatore univoco in base all'utenza */
            $identifier;


            /* utente Cliente */
            if (strval($utenza) === "cliente") {
                /* chiave primaria Clienti */
                $identifier = "IdCliente";
                /* Ragione Sociale del Cliente */
                $name = "RagioneSociale";
                /* tabella Clienti */
                $table = "Clienti";
            } else {
                /* chiave primaria Agenti */
                $identifier = "Matricola";
                /* nome dell'Agente */
                $name = "Nome";
                /* tabella Agenti */
                $table = "Agenti";
            }

            /* controllo che sia un formato di email valido */
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                /* controllo che la mail (e l'account) sia presente nel DB */
                if (intval(execQuery($conn, "SELECT COUNT(1) FROM $table WHERE Email='$email';")[0][0]) === 1) {
                    /* salvo la password */
                    $password = $_POST["password"];

                    /* eseguo la query per prendere l'IdCliente/Matricola, il Nome/Ragione Sociale , la Password (SHA-256) e il Salt associati all'email */
                    $data = execQuery($conn, "SELECT `$identifier`, `$name`, `Password`, `PasswordSalt` FROM $table WHERE `Email`='$email'", true)[0];

                    /* salvo salt da DB */
                    $salt = $data["PasswordSalt"];
                    /* genero l'hash della password usando SHA-256 con 10000 rounds e il salt sul DB */
                    $hash = explode("$", crypt("$email:$password", '$5$rounds=10000$' . $salt . '$'))[4];

                    /* confronto che la password del DB e quella passata siano identiche */
                    if (strval($hash) === strval($data["Password"])) {
                        /* azzero le sessioni precedenti se presenti */
                        session_unset();
                        /* faccio partire la sessione per carrello e altre pagine in generale con l'id/matricola */
                        session_start();

                        /* salvo nella sessione l'id del cliente */
                        $_SESSION[$identifier] = $data[$identifier];

                        /* stampo messaggio e video e reindirizzo alla Homepage */
                        echo "Ciao $data[$name], hai appena effettuato l'accesso! Sarai reindirizzato a breve...<br><script>setTimeout(() => {window.location.href='../../index.php';}, 1000);</script>";
                    } else {
                        /* password passata non corrispondente a quella sul DB */
                        throw new Error("password non corretta");
                    }
                } else {
                    /* email (e relativo account) non presente nel DB */
                    throw new Error("la Email non è associata a nessun account");
                }
            } else {
                /* email in un formato non valido */
                throw new Error("la Email è in un formato errato");
            }
        } else {
            /* tipologia di utente non presente nel DB */
            throw new Error("tipologia di utenza non riconosciuta");
        }
    } else {
        /* parametri mancanti oppure in un formato errato */
        throw new Error("parametri errati o non completi");
    }
} catch (Error $e) {
    /* stampo a video l'errore se generato con un bottone per tornare al form di accesso (i parametri vanno persi) */
    die("Errore:" . $e->getMessage() . ", ritorna alla pagina di registrazione! <a href='../signin.php' ><br><button style='padding: 5px; margin: 5px; gap: 5px;'>Torna Indietro</button></a>");
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