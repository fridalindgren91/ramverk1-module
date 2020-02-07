<?php

namespace Anax\Controller;

?>

<?= $content ?>

<p>Skriv in den ip-adress du vill validera nedan</p>

<form method="post" action="checkIP">
    <input type="text" name="ipadress" style="width: 300px">
    <input type="submit" value="Skicka" style="width: 180px">
</form>

<?php
if (isset($result)) {
    echo "<br>Resultat";
    echo $result;
    echo "Domännamn: " . $host;
}
?>

<?= $contentJSON ?>

<p>Skriv in den ip-adress du vill validera nedan</p>

<form method="get" action="checkIPJSON">
    <input type="text" name="ipadressJSON" style="width: 300px">
    <input type="submit" value="Skicka" style="width: 180px">
</form>

<h3>Mitt JSON API</h3>
<p>Mitt api nås via denna url: http://www.student.bth.se/~frlg18/dbwebb-kurser/ramverk1/me/redovisa/htdocs/ip/checkIPJSON</p>
<p>Du hämtar data med parametern "ipadressJSON". Där finns följande information:<br>
"ipadress" -> den angivna ipadressen<br>
"ipRes" -> Information om den angivna ip adressen är en giltig v4 eller v6 adress<br>
"hostJSON" -> Domännamnet, ip adressens host</p>
<p>Nedan finns en länk där jag angett facebooks ip-adress</p>
<a href="http://www.student.bth.se/~frlg18/dbwebb-kurser/ramverk1/me/redovisa/htdocs/ip/checkIPJSON?ipadressJSON=2a03:2880:f003:c07:face:b00c::2">http://www.student.bth.se/~frlg18/dbwebb-kurser/ramverk1/me/redovisa/htdocs/ip/checkIPJSON?ipadressJSON=2a03:2880:f003:c07:face:b00c::2</a>
