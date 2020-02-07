<?php
namespace Anax\Weather;

?>

<?= $content ?>

<p>Skriv in en ip-adress eller longitud och latitud för den plats du vill kolla väderprognos för</p>

<form method="post" action="checkAdress">
    <input type="text" name="ipadress" value="<?=$userIp?>" style="width: 300px"><br><br>
    <input type="text" name="inputLong" placeholder="Longitude" style="width: 300px"><br><br>
    <input type="text" name="inputLat" placeholder="Latitude" style="width: 300px"><br><br>
    <input type="submit" value="Skicka" style="width: 180px">
</form><br>

<?php
if (isset($result) && $result != null) {
    if (isset($city) && $city != null) {
        echo "<h3>Resultat för " . $city . "</h3>";
    } elseif (isset($longitude) && isset($latitude) && $longitude != null && $latitude != null) {
        echo "<h3>Resultat för longitud: " . $longitude . " och latitud: " . $latitude . "</h3>";
    }
    echo $result[0]["daily"]["summary"];
    echo "<table class='table-weather'>
            <tr>
                <th>Beskrivning</th>";
    foreach ($result[0]["daily"]["data"] as $w) {
        echo "<th>" . date('d M', $w["time"]) . "</th>";
    }
    echo "</tr>
        <tr>
            <td>Väder</td>";
    foreach ($result[0]["daily"]["data"] as $w) {
        echo "<td>" . $w["summary"] . "</td>";
    }
    echo "</tr>
        <tr>
            <td>Lägsta temperatur</td>";
    foreach ($result[0]["daily"]["data"] as $w) {
        echo "<td>" . intval($w["temperatureLow"]) . "°C</td>";
    }
    echo "</tr>
        <tr>
            <td>Högsta temperatur</td>";
    foreach ($result[0]["daily"]["data"] as $w) {
        echo "<td>" . intval($w["temperatureHigh"]) . "°C</td>";
    }
    echo "</tr>
        <tr>
            <td>Vind</td>";
    foreach ($result[0]["daily"]["data"] as $w) {
        echo "<td>" . intval($w["windSpeed"]) . "m/s</td>";
    }
    echo "</tr>
        <tr>
            <td>Typ av nederbörd</td>";
    foreach ($result[0]["daily"]["data"] as $w) {
        echo "<td>" . $w["precipType"] . "</td>";
    }
    echo "</tr>
        <tr>
            <td>Nederbörd mängd</td>";
    foreach ($result[0]["daily"]["data"] as $w) {
        echo "<td>" . $w["precipProbability"] . "mm/h</td>";
    }
    echo "</tr>
        </table>
    <div id='map' class='map'></div>
    <script>
        window.onload = function() {
            var map = new OpenLayers.Map('map');
            map.addLayer(new OpenLayers.Layer.OSM());

            var lonLat = new OpenLayers.LonLat(" . $longitude . "," . $latitude . ")
                .transform(
                    new OpenLayers.Projection('EPSG:4326'),
                    map.getProjectionObject()
                );
                
            var zoom=16;

            var markers = new OpenLayers.Layer.Markers('Markers');
            map.addLayer(markers);
            
            markers.addMarker(new OpenLayers.Marker(lonLat));
            
            map.setCenter (lonLat, zoom);
        }
    </script>";
}
?>

<?php
if (isset($errorMessage) && $errorMessage != null) {
    echo "<div class='errorMessage'>" . $errorMessage . "</div>";
}
?>

<?=$contentJSON;?>

<p>Skriv in en ip-adress eller longitud och latitud för den plats du vill kolla väderprognos för</p>

<form method="get" action="checkIPJSON">
    <input type="text" name="ipadressJSON" value="<?=$userIp?>" style="width: 300px"><br><br>
    <input type="text" name="inputLongJSON" placeholder="Longitude" style="width: 300px"><br><br>
    <input type="text" name="inputLatJSON" placeholder="Latitude" style="width: 300px"><br><br>
    <input type="submit" value="Skicka" style="width: 180px">
</form>

<h3>Mitt JSON API</h3>
<p>Mitt api nås via denna url: http://www.student.bth.se/~frlg18/dbwebb-kurser/ramverk1/me/redovisa/htdocs/weather/checkIPJSON</p>
<p>Du hämtar data med parametern "ipadressJSON" som är en ipadress, eller med parametrarna "inputLongJSON" och "inputLatJSON" som är 
longitud och latitud för den plats som du vill hämta väderprognos för. Där finns en sammanfattning för kommande veckas väder och sen 
väderobjektet med resultaten för vädret den kommande veckan.</p>
<p>Nedan finns en länk där jag angett longitud och latitud för Gävle:</p>
<a href="http://www.student.bth.se/~frlg18/dbwebb-kurser/ramverk1/me/redovisa/htdocs/weather/checkIPJSON?ipadressJSON=&inputLongJSON=17.14174&inputLatJSON=60.67452">http://www.student.bth.se/~frlg18/dbwebb-kurser/ramverk1/me/redovisa/htdocs/geo/checkIPJSON?ipadressJSON=2a03:2880:f003:c07:face:b00c::2</a>
