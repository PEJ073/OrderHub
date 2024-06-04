<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>OrderHub | Accedi</title>
    <link rel="stylesheet" href="./styleProfile.css">
</head>

<body>
    <div id="contentDiv">
        <div id="formDiv">
            <form action="./validation/signin.php" method="POST">
                <div class="formInternalDiv f-column">
                    <input type="email" name="email" id="emailInput" placeholder="Email" maxlength="255" required>
                    <input type="password" name="password" id="passwordInput" placeholder="Password" maxlength="255" required>
                    <div class="formInternalDiv f-row">
                        <div>
                            <input type="radio" name="utenza" value="cliente" id="agentRadioInput" checked /><label for="agentRadioInput">Cliente</label>
                        </div>
                        <div>
                            <input type="radio" name="utenza" value="agente" id="clientRadioInput" /><label for="clientRadioInput">Agente</label>
                        </div>
                    </div>
                </div>
                <div class=" formInternalDiv">
                    <input type="submit" value="Accedi">
                </div>
            </form>
            <div class="formInternalDiv">
                Non sei ancora un membro? <a href="./signup.php"><button>Registrati</button></a>
            </div><br>
            <div class="formInternalDiv">
                <a href="../index.php"><button>Torna Alla Home</button></a>
            </div>
        </div>
    </div>
</body>

</html>