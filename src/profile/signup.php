<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>OrderHub | Registrati</title>
    <link rel="stylesheet" href="./styleProfile.css">
</head>

<body>
    <div id="contentDiv">
        <div id="formDiv">
            <form action="./validation/signup.php" method="POST">
                <div class="formInternalDiv f-column">
                    <input type="email" autocomplete="email" name="email" id="emailInput" placeholder="Email" maxlength="255" required />
                    <input type="email" autocomplete="email" name="emailCheck" id="emailInputCheck" placeholder="Conferma l'Email" maxlength="255" required />
                </div>
                <div class="formInternalDiv f-column">
                    <input type="password" autocomplete="new-password" name="password" id="passwordInput" placeholder="Password" required />
                    <input type="password" autocomplete="new-password" name="passwordCheck" id="passwordInputCheck" placeholder="Conferma la Password" required />
                </div>
                <div class="formInternalDiv f-column">
                    <input type="text" name="ragioneSociale" id="businessNameInput" placeholder="Ragione Sociale" maxlength="255" required />
                    <input type="text" name="pIva" id="vatNumberInput" placeholder="Partita Iva" minlength="11" maxlength="11" required />
                </div>
                <div class="formInternalDiv f-column">
                    <div>
                        <select name="prefisso" id="prefixSelect">
                            <option value="+93">(+93) Afghanistan</option>
                            <option value="+1">(+1) Alaska</option>
                            <option value="+355">(+355) Albania</option>
                            <option value="+213">(+213) Algeria</option>
                            <option value="+376">(+376) Andorra</option>
                            <option value="+244">(+244) Angola</option>
                            <option value="+1 264">(+1 264) Anguilla</option>
                            <option value="+1 268">(+1 268) Antigua e Barbuda</option>
                            <option value="+599">(+599) Antille Olandesi</option>
                            <option value="+966">(+966) Arabia Saudita</option>
                            <option value="+54">(+54) Argentina</option>
                            <option value="+374">(+374) Armenia</option>
                            <option value="+297">(+297) Aruba</option>
                            <option value="+247">(+247) Ascensione</option>
                            <option value="+61">(+61) Australia</option>
                            <option value="+67210-1-2">(+67210-1-2) Australia Antartica</option>
                            <option value="+43">(+43) Austria</option>
                            <option value="+994">(+994) Azerbaigian</option>
                            <option value="+1 242">(+1 242) Bahamas</option>
                            <option value="+973">(+973) Bahrain</option>
                            <option value="+880">(+880) Bangladesh</option>
                            <option value="+1 246">(+1 246) Barbados</option>
                            <option value="+32">(+32) Belgio</option>
                            <option value="+501">(+501) Belize</option>
                            <option value="+229">(+229) Benin</option>
                            <option value="+1 441">(+1 441) Bermuda</option>
                            <option value="+975">(+975) Bhutan</option>
                            <option value="+375">(+375) Bielorussia</option>
                            <option value="+591">(+591) Bolivia</option>
                            <option value="+387">(+387) Bosnia Erzegovina</option>
                            <option value="+267">(+267) Botswana</option>
                            <option value="+55">(+55) Brasile</option>
                            <option value="+673">(+673) Brunei</option>
                            <option value="+359">(+359) Bulgaria</option>
                            <option value="+226">(+226) Burkina Faso</option>
                            <option value="+257">(+257) Burundi</option>
                            <option value="+855">(+855) Cambogia</option>
                            <option value="+237">(+237) Camerun</option>
                            <option value="+1">(+1) Canada</option>
                            <option value="+238">(+238) Capo Verde</option>
                            <option value="+235">(+235) Ciad</option>
                            <option value="+56">(+56) Cile</option>
                            <option value="+86">(+86) Cina</option>
                            <option value="+357">(+357) Cipro</option>
                            <option value="+57">(+57) Colombia</option>
                            <option value="+269">(+269) Comore</option>
                            <option value="+242">(+242) Congo</option>
                            <option value="+682">(+682) Cook</option>
                            <option value="+850">(+850) Corea del Nord</option>
                            <option value="+82">(+82) Corea del Sud</option>
                            <option value="+225">(+225) Costa d’Avorio</option>
                            <option value="+506">(+506) Costa Rica</option>
                            <option value="+385">(+385) Croazia</option>
                            <option value="+53">(+53) Cuba</option>
                            <option value="+45">(+45) Danimarca</option>
                            <option value="+246">(+246) Diego Garcia</option>
                            <option value="+1 767">(+1 767) Dominica</option>
                            <option value="+593">(+593) Ecuador</option>
                            <option value="+20">(+20) Egitto</option>
                            <option value="+503">(+503) El Salvador</option>
                            <option value="+971">(+971) Emirati Arabi Uniti</option>
                            <option value="+291">(+291) Eritrea</option>
                            <option value="+372">(+372) Estonia</option>
                            <option value="+251">(+251) Etiopia</option>
                            <option value="+7">(+7) Federazione Russa</option>
                            <option value="+679">(+679) Fiji</option>
                            <option value="+63">(+63) Filippine</option>
                            <option value="+358">(+358) Finlandia</option>
                            <option value="+33">(+33) Francia</option>
                            <option value="+241">(+241) Gabon</option>
                            <option value="+220">(+220) Gambia</option>
                            <option value="+995">(+995) Georgia</option>
                            <option value="+49">(+49) Germania</option>
                            <option value="+233">(+233) Ghana</option>
                            <option value="+1 876">(+1 876) Giamaica</option>
                            <option value="+81">(+81) Giappone</option>
                            <option value="+350">(+350) Gibilterra</option>
                            <option value="+253">(+253) Gibuti</option>
                            <option value="+962">(+962) Giordania</option>
                            <option value="+44">(+44) Gran Bretagna</option>
                            <option value="+30">(+30) Grecia</option>
                            <option value="+1 473">(+1 473) Grenada</option>
                            <option value="+299">(+299) Groenlandia</option>
                            <option value="+590">(+590) Guadalupa</option>
                            <option value="+671">(+671) Guam</option>
                            <option value="+502">(+502) Guatemala</option>
                            <option value="+224">(+224) Guinea</option>
                            <option value="+245">(+245) Guinea Bissau</option>
                            <option value="+240">(+240) Guinea Equatoriale</option>
                            <option value="+592">(+592) Guyana</option>
                            <option value="+594">(+594) Guyana Francese</option>
                            <option value="+509">(+509) Haiti</option>
                            <option value="+504">(+504) Honduras</option>
                            <option value="+852">(+852) Hong Kong</option>
                            <option value="+91">(+91) India</option>
                            <option value="+62">(+62) Indonesia</option>
                            <option value="+98">(+98) Iran</option>
                            <option value="+964">(+964) Iraq</option>
                            <option value="+353">(+353) Irlanda</option>
                            <option value="+354">(+354) Islanda</option>
                            <option value="+1 345">(+1 345) Isole Cayman</option>
                            <option value="+500">(+500) Isole Falkland</option>
                            <option value="+298">(+298) Isole Fær Oer</option>
                            <option value="+670">(+670) Isole Marianne</option>
                            <option value="+692">(+692) Isole Marshall</option>
                            <option value="+672">(+672) Isole Norfolk</option>
                            <option value="+677">(+677) Isole Salomone</option>
                            <option value="+1 284">(+1 284) Isole Vergini (GBR)</option>
                            <option value="+1 340">(+1 340) Isole Vergini (U.S.)</option>
                            <option value="+972">(+972) Israele</option>
                            <option value="+39">(+39) Italia</option>
                            <option value="+996">(+996) Kazakistan</option>
                            <option value="+254">(+254) Kenia</option>
                            <option value="+996">(+996) Kirghizstan</option>
                            <option value="+686">(+686) Kiribati</option>
                            <option value="+965">(+965) Kuwait</option>
                            <option value="+856">(+856) Laos</option>
                            <option value="+266">(+266) Lesotho</option>
                            <option value="+371">(+371) Lettonia</option>
                            <option value="+961">(+961) Libano</option>
                            <option value="+231">(+231) Liberia</option>
                            <option value="+218">(+218) Libia</option>
                            <option value="+423">(+423) Liechtenstein</option>
                            <option value="+370">(+370) Lituania</option>
                            <option value="+352">(+352) Lussemburgo</option>
                            <option value="+853">(+853) Macao</option>
                            <option value="+389">(+389) Macedonia</option>
                            <option value="+261">(+281) Madagascar</option>
                            <option value="+265">(+265) Malawi</option>
                            <option value="+960">(+960) Maldive</option>
                            <option value="+60">(+60) Malesia</option>
                            <option value="+223">(+223) Mali</option>
                            <option value="+356">(+356) Malta</option>
                            <option value="+212">(+212) Marocco</option>
                            <option value="+596">(+596) Martinica</option>
                            <option value="+222">(+222) Mauritania</option>
                            <option value="+230">(+230) Mauritius</option>
                            <option value="+52">(+52) Messico</option>
                            <option value="+373">(+373) Moldavia</option>
                            <option value="+377">(+377) Monaco (Principato di)</option>
                            <option value="+976">(+976) Mongolia</option>
                            <option value="+1 664">(+1 664) Montserrat</option>
                            <option value="+258">(+258) Mozambico</option>
                            <option value="+264">(+264) Namibia</option>
                            <option value="+977">(+977) Nepal</option>
                            <option value="+505">(+505) Nicaragua</option>
                            <option value="+234">(+234) Nigeria</option>
                            <option value="+683">(+683) Niue</option>
                            <option value="+47">(+47) Norvegia</option>
                            <option value="+687">(+687) Nuova Caledonia</option>
                            <option value="+64">(+64) Nuova Zelanda</option>
                            <option value="+968">(+968) Oman</option>
                            <option value="+31">(+31) Paesi Bassi (Olanda)</option>
                            <option value="+92">(+92) Pakistan</option>
                            <option value="+680">(+680) Palau</option>
                            <option value="+507">(+507) Panama</option>
                            <option value="+675">(+675) Papua Nuova Guinea</option>
                            <option value="+595">(+595) Paraguay</option>
                            <option value="+51">(+51) Perù</option>
                            <option value="+689">(+689) Polinesia Francese</option>
                            <option value="+48">(+48) Polonia</option>
                            <option value="+351">(+351) Portogallo</option>
                            <option value="+1 787">(+1 787) Porto Rico</option>
                            <option value="+974">(+974) Qatar</option>
                            <option value="+420">(+420) Rep. Ceca</option>
                            <option value="+236">(+236) Rep. Centrafrica</option>
                            <option value="+243">(+243) Rep. Democratica del Congo</option>
                            <option value="+1">(+1) Rep. Dominicana</option>
                            <option value="+262">(+262) Reunion (Francia)</option>
                            <option value="+40">(+40) Romania</option>
                            <option value="+250">(+250) Ruanda</option>
                            <option value="+290">(+290) Saint Elena</option>
                            <option value="+1 869">(+1 869) Saint Kitts e Nevis</option>
                            <option value="+508">(+508) Saint Pierre e Miquelon</option>
                            <option value="+1 784">(+1 784) Saint Vincent</option>
                            <option value="+684">(+684) Samoa Americane</option>
                            <option value="+685">(+685) Samoa Occidentale</option>
                            <option value="+1 758">(+1 758) Santa Lucia</option>
                            <option value="+239">(+239) Sao Tomè e Principe</option>
                            <option value="+221">(+221) Senegal</option>
                            <option value="+248">(+248) Seychelles</option>
                            <option value="+232">(+232) Sierra Leone</option>
                            <option value="+65">(+65) Singapore</option>
                            <option value="+963">(+963) Siria</option>
                            <option value="+421">(+421) Slovacchia</option>
                            <option value="+386">(+386) Slovenia</option>
                            <option value="+252">(+252) Somalia</option>
                            <option value="+34">(+34) Spagna</option>
                            <option value="+94">(+94) Sri Lanka</option>
                            <option value="+27">(+27) Sudafrica</option>
                            <option value="+249">(+249) Sudan</option>
                            <option value="+46">(+46) Svezia</option>
                            <option value="+41">(+41) Svizzera</option>
                            <option value="+268">(+268) Swaziland</option>
                            <option value="+737">(+737) Tajikistan</option>
                            <option value="+886">(+886) Taiwan</option>
                            <option value="+255">(+255) Tanzania</option>
                            <option value="+66">(+66) Thailandia</option>
                            <option value="+228">(+228) Togo</option>
                            <option value="+676">(+676) Tonga</option>
                            <option value="+1 868">(+1 868) Trinidad e Tobago</option>
                            <option value="+216">(+216) Tunisia</option>
                            <option value="+90">(+90) Turchia</option>
                            <option value="+993">(+993) Turkmenistan</option>
                            <option value="+1 649">(+1 649) Turks e Caicos</option>
                            <option value="+688">(+688) Tuvalu</option>
                            <option value="+380">(+380) Ucraina</option>
                            <option value="+256">(+256) Uganda</option>
                            <option value="+36">(+36) Ungheria</option>
                            <option value="+598">(+598) Uruguay</option>
                            <option value="+1">(+1) USA</option>
                            <option value="+998">(+998) Uzbekistan</option>
                            <option value="+678">(+678) Vanuato</option>
                            <option value="+58">(+58) Venezuela</option>
                            <option value="+84">(+84) Vietnam</option>
                            <option value="+681">(+681) Wallis e Futuna</option>
                            <option value="+967">(+967) Yemen</option>
                            <option value="+381">(+381) Yugoslavia</option>
                            <option value="+260">(+260) Zambia</option>
                            <option value="+263">(+263) Zimbabwe</option>
                        </select>
                        <input type="text" name="numeroTelefono" id="cellPhoneInput" placeholder="Numero di Telefono" maxlength="255" required />
                    </div>
                </div>
                <div class="formInternalDiv f-row">
                    <input type="text" name="indirizzo" id="addressInput" placeholder="Indirizzo" maxlength="255" required />
                    <input type="text" name="numeroCivico" id="addressNumberInput" placeholder="Numero" maxlength="255" required />
                </div>
                <div class="formInternalDiv f-column">
                    <input type="text" name="comune" id="townInput" placeholder="Comune" maxlength="255" required />
                    <input type="text" name="provincia" id="countyInput" placeholder="Provincia" maxlength="255" required />
                </div>
                <div class="formInternalDiv f-column">
                    <input type="text" name="cap" id="postalCodeInput" placeholder="Cap" maxlength="255" required />
                    <input type="text" name="stato" id="stateInput" placeholder="Stato" maxlength="255" required />
                </div>
                <div class="formInternalDiv">
                    <input type="submit" value="Registrati">
                </div>
            </form>
            <div class="formInternalDiv">
                Sei già un membro? <a href="./signin.php"><button>Accedi</button></a>
            </div><br>
            <div class="formInternalDiv">
                <a href="../index.php"><button>Torna Alla Home</button></a>
            </div>
        </div>
    </div>
    </div>
</body>

</html>