<?php
function printNavBar() {
    echo "<img src='../media/LogoD.png' alt='OrderHub' height='50px'>
        <a href='./'>
            <div class='navBarInternalDiv'>
                <img src='../media/icons/house.svg' alt='Home' height='50px'>
                <p>Home</p>
            </div>
        </a>
        <a href='./catalog.php'>
            <div class='navBarInternalDiv'>
                <img src='../media/icons/book.svg' alt='Catalogo' height='50px'>
                <p>Catalogo</p>
            </div>
        </a>
        <a href='./cart.php'>
            <div class='navBarInternalDiv'>
                <img src='../media/icons/cart.svg' alt='Carrello' height='50px'>
                <p>Carrello</p>
            </div>
        </a>";

    if (isset($_SESSION["IdCliente"])) {
        echo
        "<a href='./profile/profile.php'>
            <div class='navBarInternalDiv'>
                <img src='../media/icons/user.svg' alt='Profilo' height='50px'>
                <p>Profilo</p>
            </div>
        </a>";
    } else if (isset($_SESSION["Matricola"])) {
        echo
        "<a href='./profile/profile.php'>
            <div class='navBarInternalDiv'>
                <img src='../media/icons/usershield.svg' alt='Profilo' height='50px'>
                <p>Profilo</p>
            </div>
        </a>";
    } else {
        echo
        "<a href='./profile/signin.php'>
            <div class='navBarInternalDiv'>
                <img src='../media/icons/righttobracket.svg' alt='Accedi' height='50px'>
                <p>Accedi</p>
            </div>
        </a>";
    }
}

function execQuery(mysqli $conn, string $query, bool $assoc = false) {
    try {
        $result = $conn->query($query);

        if ($assoc) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return $result->fetch_all(MYSQLI_NUM);
        }
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage() . ".";
    }

    return null;
}

function downloadImage($conn, $IdProduct) {
    $image = null;

    try {
        $sql = $conn->prepare("SELECT Immagine FROM Prodotti WHERE IdProdotto = ?");
        $sql->bind_param("s", $IdProduct);
        $sql->execute();
        $sql->bind_result($image);
        $sql->fetch();

        return $image;
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}

function uploadImage($conn, $file, $IdProduct) {
    try {
        $null = null;

        $image = file_get_contents($file);
        $sql = $conn->prepare("INSERT INTO Prodotti (Immagine) VALUES (?) WHERE IdProdotto=?");
        $sql->bind_param("sb", $IdProduct, $null);
        $sql->send_long_data(1, $image);
        $sql->execute();

        echo "Immagine inserita correttamente!";
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}

function generateString($lunghezza) {
    $caratteri = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $stringaRandom = '';

    for ($i = 0; $i < $lunghezza; $i++) {
        $stringaRandom .= $caratteri[rand(0, strlen($caratteri) - 1)];
    }

    return $stringaRandom;
}

function verificateAddress($address, $addressNumber, $town, $county, $postalCode, $state) {
    $indirizzoCompleto = urlencode("$address $addressNumber, $town, $county, $postalCode, $state");

    // Costruisci l'URL per la richiesta
    $url = "https://nominatim.openstreetmap.org/search?format=json&q=$indirizzoCompleto";
    $options = [
        "http" => [
            "header" => "User-Agent: OrderHub\n"
        ]
    ];

    $context = stream_context_create($options);
    // Esegui la richiesta HTTP
    $response = file_get_contents($url, false, $context);

    $data = json_decode($response, true);

    // Analizza la risposta
    if (!empty($data)) {
        $formattedAddress = $data[0]['display_name'];
        $lat = $data[0]['lat'];
        $lng = $data[0]['lon'];

        return [$lat, $lng];
    } else {
        return array();
    }
}
