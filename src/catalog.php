<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>OrderHub | Catalogo</title>
    <link rel="stylesheet" href="./styleSrc.css">
    <link rel="shortcut icon" href="../media/icons/favicon.ico" type="image/x-icon" />
    <script>
        /* vettore con i prodotti correnti */
        let products;
        /* vettore con le categorie di prodotti presenti nel DB */
        let categories;
        /* vettore con il numero delle pagine da mostrare nel paginationDiv */
        let pages;
        /* oggetto con le condizioni per le richieste al DB */
        let conditions = {};

        /* richiama le funzioni per creare l'impostazione della pagina e riempire con i prodotti corretti */
        function load() {
            /* richiede e stampa i prodotti */
            requestProducts();
            /* stampa i componenti per poter filtrare i prodotti */
            fillFilters();
        }

        /* effettua una chiamata con AJAX per i prodotti corretti in base ai filtri se presenti, (catChange indica se la categoria è cambiata, in quel caso si resettano i filtri) */
        function requestProducts(catChange = false) {
            /* variabile con richiesta AJAX */
            let serverRequest = new XMLHttpRequest();
            /* variabile per i parametri da passare alla pagina in PHP per ottenere i prodotti */
            let paramsString = "?";

            /* funzione da eseguire alla risposta */
            serverRequest.onreadystatechange = () => {
                /* controllo se la richiesta è andata a buon fine */
                if (serverRequest.readyState == 4 && serverRequest.status == 200) {
                    /* salvo la risposta del SERVER */
                    let dati = JSON.parse(serverRequest.responseText);
                    products = JSON.parse(dati[0]);
                    categories = [{
                            IdCategoria: "0",
                            Nome: "Tutte"
                        },
                        ...JSON.parse(dati[1]),
                    ];
                    pages = dati[2];

                    /* nei seguenti 4 if ternari, i filtri vengono azzerati/salvati dal DB se la categoria cambia oppure se non sono presenti tra le condizioni */
                    {
                        catChange || !conditions["pagina"] ? conditions = {
                            ...conditions,
                            pagina: 1
                        } : "";
                    };
                    {
                        catChange || !conditions["prezzomin"] ? conditions = {
                            ...conditions,
                            prezzomin: parseFloat(dati[3])
                        } : "";
                    };
                    {
                        catChange || !conditions["prezzomax"] ? conditions = {
                            ...conditions,
                            prezzomax: parseFloat(dati[4]),
                        } : "";
                    };
                    {
                        catChange || !conditions["quantitamin"] ? conditions = {
                            ...conditions,
                            quantitamin: 1
                        } : "";
                    };
                    /* salvati ogni iterazione */
                    conditions = {
                        ...conditions,
                        quantitamax: parseInt(dati[5]),
                    };
                    conditions = {
                        ...conditions,
                        categoria: parseInt(dati[6])
                    };
                }
            }

            /* creo stringa parametri */
            for (let index in conditions) {
                /* la quantità max non va passata */
                if (index != "quantitamax") {
                    /* se la categoria cambia si passa solo il nuovo IdCategoria */
                    if (catChange) {
                        if (index === "categoria") {
                            paramsString += index + "=" + conditions[index];
                        }
                    } else {
                        /* se nn cambia cateogoria, si passano tutti e si aggiunge la '&' alla stringa se è il primo parametro */
                        if (paramsString.slice(-1) !== "?")
                            paramsString += "&";
                        paramsString += index + "=" + conditions[index];
                    }
                }
            }

            /* richiesta al SERVER */
            serverRequest.open("GET", `./api/products.php${paramsString || ""}`, false);
            serverRequest.send();

            /* si riempe con i prodotti aggiornati */
            fillProducts();
            /* se cambia la categoria si ricreano i filtri */
            if (catChange) {
                fillFilters();
            }
            /* si aggiornano le pagine nella pagination */
            createPagination();
        }

        /* riempimento del productsDiv con i prodotti (card) */
        function fillProducts() {
            /* svuotamento del div preesistente */
            document.getElementById("productsDiv").innerHTML = "";

            /* salvo i valori di prezzo minimo e massimo */
            let min = conditions["prezzomin"]
            let max = conditions["prezzomax"]

            for (index in products) {
                /* div contenente prodotto */
                let card = document.createElement("div");
                /* nome prodotto */
                let h3 = document.createElement("h3");
                /* div con immagine */
                let imgDiv = document.createElement("div");
                /* immagine */
                let img = document.createElement("img");
                /* percorso immagine */
                let imgSrc = `./api/image.php?id=${products[index]["IdProdotto"]}`;
                /* descrizione prodotto */
                let pDescription = document.createElement("p");
                /* prezzo */
                let pPrice = document.createElement("p");
                /* select ordinamento */
                let select = document.createElement("select");
                /* bottone per aggiungere al carrello */
                let addToCartButton = document.createElement("input");
                /* label per quantita rimasta */
                let label = document.createElement("label");

                /* classe css */
                card.classList.add("card");

                /* nome */
                h3.innerText = products[index]["Nome"];

                /* calsse css e evento che manda a tutto schermo l'immagine cliccata */
                imgDiv.classList.add("imgCardDiv");
                imgDiv.addEventListener("click", () => {

                });

                /* src e alt dell'immagine */
                img.setAttribute("src", imgSrc);
                img.setAttribute("alt", products[index]["IdProdotto"]);

                /* descrizione */
                pDescription.innerText = products[index]["Descrizione"];
                /* prezzo unitario */
                pPrice.innerText = `€${products[index]["PrezzoUnitario"]}/pz`;

                /* id select per gestire evento */
                select.setAttribute("id", "addToCartSelect" + products[index]["IdProdotto"]);

                /* riempo la select da 1 a max 30 possibili prodotti da aggiungere al carrello */
                for (let i = 1; i <= products[index]["QuantitaRimasta"] && i <= 30; i++) {
                    /* option */
                    let option = document.createElement("option");

                    /* salvo valore */
                    option.setAttribute("value", i);
                    option.innerText = i;

                    /* aggiungo alla select */
                    select.append(option);
                }

                /* istanzio bottone ed evento che scatena */
                addToCartButton.setAttribute("type", "button");
                addToCartButton.setAttribute("value", "Aggiungi al Carrello");
                addToCartButton.addEventListener("click", (e) => {

                });

                /* salvo rosso se meno di 10 rimasti */
                if (products[index]["QuantitaRimasta"] < 10) {
                    label.setAttribute("style", "color: red;");

                    /* se uno metto parola al singolare */
                    {
                        parseInt(products[index]["QuantitaRimasta"]) === 1 ?
                            label.innerText = `Affrettati solamente 1 rimasto` :
                            label.innerText = `Affrettati solamente ${products[index]["QuantitaRimasta"]} rimasti`
                    };
                } else {
                    /* altrimenti nero */
                    label.innerText = `${products[index]["QuantitaRimasta"]} rimasti`;
                }

                /* immagine dentro divImmagine */
                imgDiv.append(img);
                /* metto tutto in ordine dentro la card */
                card.append(h3);
                card.append(imgDiv);
                card.append(pDescription);
                card.append(pPrice);
                card.append(select);
                card.append(addToCartButton);
                card.append(label);
                /* aggiungo al div con i prodotti */
                document.getElementById("productsDiv").append(card);
            }
        }

        /* richiama le funzioni per riempire i filtri */
        function fillFilters() {
            /* crea i radioButton per le categorie */
            createRadioCategory();
            /* crea gli slider per il prezzo */
            createSlider();
            /* crea il select per la quantità */
            createSelectQuantity();
            /* crea il select per l'ordinamento */
            createSelectSorting();
            /* crea la pagination */
            createPagination();
        }

        /* crea i radioButton per le categorie */
        function createRadioCategory() {
            /* svuoto div preesistente */
            document.getElementById("categoryDiv").innerHTML = "";

            /* creo titolo */
            let h2 = document.createElement("h2").innerText = "Seleziona Categoria:";

            /* salvo nel div */
            document.getElementById("categoryDiv").append(h2);

            /* salvo tutte le categorie */
            for (let i = 0; i < categories.length; i++) {
                /* creo la label con il nome */
                let label = document.createElement("label");
                /* input radio */
                let input = document.createElement("input");
                /* div contenente label e input */
                let div = document.createElement("div");

                /* metto il for che punta all'input */
                label.setAttribute("for", "category" + categories[i]["IdCategoria"]);
                /* salvo nome categoria nella label */
                label.innerText = categories[i]["Nome"];

                /* tipo di input */
                input.setAttribute("type", "radio");
                /* name button group */
                input.setAttribute("name", "radioCategories");
                /* id per il for */
                input.setAttribute("id", "category" + categories[i]["IdCategoria"]);
                /* valore */
                input.setAttribute("value", categories[i]["IdCategoria"]);
                /* evento per cambiare categoria */
                input.addEventListener("click", (e) => {
                    /* salvo categoria nuova */
                    conditions = {
                        ...conditions,
                        categoria: parseInt(e.target.value)
                    };
                    /* ricarico prodotti */
                    requestProducts(true);
                });
                /* se è la categoria selezionata, seleziono il radio button */
                if (parseInt(categories[i]["IdCategoria"]) === parseInt(conditions["categoria"])) {
                    input.setAttribute("checked", "checked");
                }

                /* salvo nel div tutto */
                div.append(input);
                div.append(label);
                document.getElementById("categoryDiv").append(div);
            }
        }

        /* crea gli slider per il prezzo */
        function createSlider() {
            /* svuoto div preesistente */
            document.getElementById("priceDiv").innerHTML = "";

            /* tempo di attesa prima della richiesta AJAX quando si cambia valore slider */
            let sleepTime = 200;

            /* salvo variabili per min e max */
            let min = conditions["prezzomin"];
            let max = conditions["prezzomax"];

            /* label titolo */
            let label = document.createElement("label");
            /* creo le label min e max */
            let labelMin = document.createElement("label");
            let labelMax = document.createElement("label");
            /* creo input min e max */
            let inputMin = document.createElement("input");
            let inputMax = document.createElement("input");

            /* attributo for che punta al div */
            label.setAttribute("for", "priceDiv");
            /* attributo for che puntano agli input */
            labelMin.setAttribute("for", "inputMin");
            labelMax.setAttribute("for", "inputMax");
            /* valori delle label con punteggiatura */
            label.innerText = "Range di Prezzo:";
            labelMin.innerHTML = "Max: " + min.toLocaleString();
            labelMax.innerHTML = "Max: " + max.toLocaleString();

            /* id per le label */
            inputMin.setAttribute("id", "inputMin");
            inputMax.setAttribute("id", "inputMax");
            /* tipo di input (slider) */
            inputMin.setAttribute("type", "range");
            inputMax.setAttribute("type", "range");
            /* step per essere precisi */
            inputMin.setAttribute("step", 0.01);
            inputMax.setAttribute("step", 0.01);
            /* valore min e max */
            inputMin.setAttribute("min", min);
            inputMin.setAttribute("max", max);
            inputMax.setAttribute("min", min);
            inputMax.setAttribute("max", max);
            /* valore di default (min) */
            inputMin.setAttribute("value", min);
            /* valore di default (max) */
            inputMax.setAttribute("value", max);

            /* quando cambia il valore */
            inputMin.addEventListener("input", (e) => {
                /* se il minimo va oltre il massimo allora quest'ultimo viene cambiato anche */
                if (parseFloat(inputMax.value) < parseFloat(e.target.value)) {
                    inputMax.value = e.target.value;
                }

                /* aggiorno entrambe le label con punteggiatura */
                labelMax.innerHTML = "Max: " + parseFloat(inputMax.value).toLocaleString();
                max = inputMax.value;
                labelMin.innerHTML = "Min: " + parseFloat(e.target.value).toLocaleString();
                min = e.target.value;

                /* aggiorno entrambi i valori nelle condizioni */
                conditions = {
                    ...conditions,
                    prezzomin: e.target.value,
                    prezzomax: inputMax.value
                }
                /* richiamo AJAX dopo un time di sleep */
                setTimeout(() => {
                    requestProducts()
                }, sleepTime);

            });
            /* quando cambia il valore */
            inputMax.addEventListener("input", (e) => {
                /* se il massimo va oltre il minimo allora quest'ultimo viene cambiato anche */
                if (parseFloat(inputMin.value) > parseFloat(e.target.value)) {
                    inputMin.value = e.target.value;
                }

                /* aggiorno entrambe le label con punteggiatura */
                labelMin.innerHTML = "Min: " + parseFloat(inputMin.value).toLocaleString();
                min = inputMin.value;
                labelMax.innerHTML = "Max: " + parseFloat(e.target.value).toLocaleString();
                max = e.target.value;

                /* aggiorno entrambi i valori nelle condizioni */
                conditions = {
                    ...conditions,
                    prezzomin: inputMin.value,
                    prezzomax: e.target.value
                }
                /* richiamo AJAX dopo un time di sleep */
                setTimeout(() => {
                    requestProducts()
                }, sleepTime);
            });

            /* salvo gli elementi nel div con dei br per andare a capo */
            document.getElementById("priceDiv").appendChild(label);
            document.getElementById("priceDiv").appendChild(labelMin);
            document.getElementById("priceDiv").appendChild(inputMin);
            document.getElementById("priceDiv").appendChild(labelMax);
            document.getElementById("priceDiv").appendChild(inputMax);
        }

        /* crea il select per la quantità */
        function createSelectQuantity() {
            /* svuoto div preesistente */
            document.getElementById("quantityDiv").innerHTML = "";

            /* label titolo */
            let label = document.createElement("label");
            /* select */
            let select = document.createElement("select");
            /* valori da vedere con option */
            let values = [1, 2, 3, 4, 5, 10, 20, 50, 100];
            /* quantità del prodotto con più disponibilità in modo da non mettere opzioni superiori */
            let maxqty = conditions["quantitamax"];

            /* for per puntare alla select */
            label.setAttribute("for", "quantity");
            /* titolo label */
            label.innerHTML = "Quantit&agrave; disponibile ";

            /* id per label */
            select.setAttribute("id", "quantity");
            /* quando cambia la selezione */
            select.addEventListener("change", (e) => {
                /* salvo il valore */
                conditions = {
                    ...conditions,
                    quantitamin: e.target.value
                }
                /* ricarico prodotti */
                requestProducts();
            });

            /* ciclo fino alla fine del vettore oppure fino a che non viene superata la quantita massima */
            for (let i = 0; maxqty >= values[i] && i < values.length; i++) {
                /* option */
                let option = document.createElement("option");

                /* valore */
                option.setAttribute("value", values[i]);
                /* testo visualizzato */
                option.textContent = values[i] + "+";
                /* aggiungo alla select */
                select.appendChild(option);
            }

            /* salvo nel div */
            document.getElementById("quantityDiv").appendChild(label);
            document.getElementById("quantityDiv").appendChild(select);
        }

        /* crea il select per l'ordinamento */
        function createSelectSorting() {
            /* svuoto div preesistente */
            document.getElementById("sortDiv").innerHTML = "";

            /* label titolo */
            let label = document.createElement("label");
            /* select */
            let select = document.createElement("select");
            /* vettore con i possibili valori */
            let values = ["Nome Crescente", "Nome Decrescente", "Prezzo Crescente", "Prezzo Decrescente", "Disponibilita' Crescente", "Disponibilita' Decrescente"];

            /* for per puntare alla select */
            label.setAttribute("for", "sort");
            /* testo label */
            label.innerHTML = "Ordina per ";

            /* id per la label */
            select.setAttribute("id", "sort");
            /* quando cambia la selezione */
            select.addEventListener("change", (e) => {
                /* salvo ordinamento nuovo */
                conditions = {
                    ...conditions,
                    ordine: e.target.value
                };
                /* ricarico prodotti */
                requestProducts();
            });

            /* salvo option nella select */
            for (let i = 0; i < values.length; i++) {
                /* option */
                let option = document.createElement("option");

                /* value */
                option.setAttribute("value", i + 1);
                /* testo visualizzato */
                option.textContent = values[i];
                /* salvo nella select */
                select.appendChild(option);
            }

            /* salvo nel div */
            document.getElementById("sortDiv").appendChild(label);
            document.getElementById("sortDiv").appendChild(select);
        }

        /* crea la pagination */
        function createPagination() {
            /* svuoto div preesistente */
            document.getElementById("paginationDiv").innerHTML = "";

            /* per ogni elemento del vettore */
            for (index in pages) {
                /* creo input */
                let input = document.createElement("input");

                /* tipo button */
                input.setAttribute("type", "button");
                /* valore */
                input.setAttribute("value", pages[index]);
                /* se premuto */
                input.addEventListener("click", (e) => {
                    /* salvo la pagina nuova */
                    conditions = {
                        ...conditions,
                        pagina: e.target.value
                    };

                    /* richiedo prodotti di quella pagina */
                    requestProducts();
                });

                /* la pagina attuale è la penultima nel vettore e non è la penultima (come numero di pagina) aggiungo i puntini per dire indicare il buco tra i numeri */
                if (parseInt(index) === (pages.length - 1) && pages[--index] && parseInt(pages[index]) !== (parseInt(pages[++index]) - 1)) {
                    /* input senza EventListener*/
                    let inputPointEnd = document.createElement("input");

                    /* button */
                    inputPointEnd.setAttribute("type", "button");
                    /* valore puntini */
                    inputPointEnd.setAttribute("value", "...");

                    /* aggiungo al div */
                    document.getElementById("paginationDiv").append(inputPointEnd);
                }

                /* aggiungo input con pagina */
                document.getElementById("paginationDiv").append(input);

                /* la prima pagina indicata nel vettore è sempre 1; se quella successiva non è la 2 aggiungo i puntini per indicare il buco tra i numeri */
                if (parseInt(index) === 0 && pages[++index] && parseInt(pages[index]) !== 2) {
                    /* input senza EventListener*/
                    let inputPointStart = document.createElement("input");

                    /* button */
                    inputPointStart.setAttribute("type", "button");
                    /* valore puntini */
                    inputPointStart.setAttribute("value", "...");

                    /* aggiungo al div */
                    document.getElementById("paginationDiv").append(inputPointStart);
                }
            }
        }
    </script>
</head>

<body onload="load()">
    <!-- div la na NavBar -->
    <div id="navBarDiv">
        <?php
        include("./api/functions.php");
        printNavBar();
        ?>
    </div>
    <!-- contenuto della pagina -->
    <div id="contentDiv">
        <!-- div con gli elementi per filtrare i prodotti -->
        <div id="filtersDiv">
            <!-- div con le categorie -->
            <div id="categoryDiv"></div>
            <!-- div con i filtri -->
            <div id="product-filter">
                <h2>Filtra i Prodotti</h2>
                <!-- div con gli slider -->
                <div id="priceDiv"></div>
                <!-- div con la select delle quantità -->
                <div id="quantityDiv"></div>
                <!-- div con la select con gli ordinamenti -->
                <div id="sortDiv"></div>
                <!-- div con la pagination -->
                <div id="paginationDiv"></div>
            </div>
        </div>
        <!-- div con le card -->
        <div id="productsDiv"></div>
        <!-- div del carrello con collegamento alla pagina -->
        <div id="cartDiv"><img src='../media/icons/cart.svg' alt='Carrello' height='50px'>
            <p>io sono il totale del carrello</p>
            <a href="./cart.php"><button>Vai al Carrello</button></a>
        </div>
    </div>

</body>

</html>