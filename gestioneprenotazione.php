<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gestioneprenotazione.php</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $numTable = $_POST["numTable"];
        $time = $_POST["time"];
        $note = getNote();
        $listMeals = getListMeans();
        $service = $_POST["service"];

        $order = array("nome" => $name, "cognome" => $surname, "numTable" => $numTable, "time" => $time, "note" => $note, "listaPasti" => $listMeals, "servizio" => $service);
        $waiters = array("Yuri Viligiardi", "Giacomo Petrini", "Neri Nardini", "Cosimo Ballarini", "Mario Rossi");
        $prices = array("antipasto" => 5, "primo" => 6, "secondo" =>7);
        $report = array($waiters[rand(0,4)] => $order);

        showPrices($prices);

        $check = checkError($listMeals);
        if ($check == true) {
            $dataError = date("d-m-Y H:i");
            echo "<p><b>Data Error: $dataError</b></p>";
            echo "<a href='prenotazione.html'><p>TORNA ALLA HOME DEGLI ORDINI</p></a>";
        } else {
            $cost = calculateCost($order, $prices);
            echo "<p>Il costo totale è $cost</p>";
            $order["costoTot"] = $cost;
            $order["data"] = date("d-m-Y H:i");
            echo "<p>-------------------------------------------</p>";
            showData($report, $order);
        }

        

        //methods
        function checkError($lm){
            if(empty($lm) == 1){
                echo "<p><b>L'utente deve selezionare almeno un pasto</b></p>";
                return true;
            } elseif (in_array("antipasto", $lm) && (count($lm) == 1)) {
                echo "<p><b>L'utente non può selezionare solo l'antipasto</b></p>";
                return true;
            } else {
                return false;
            }
        }

        function showData($r, $o){
            echo "<table>";
            echo "<tr>";
                echo "<th>cameriere</th>";
                foreach ($o as $key => $value) {
                    echo "<th>$key</th>";
                }
            echo "</tr>";
            echo "<tr>";
                foreach ($r as $key => $value) {
                    echo "<td>" . $key . "</td>";
                }
                foreach ($o as $key => $value) {
                    if($key == "listaPasti"){
                        echo "<td>"; 
                        foreach ($o["listaPasti"] as $key) {
                            echo "<p>$key</p>";
                        }
                        echo "</td>";
                    } else {
                        echo "<td>$value</td>";
                    }
                    
                }
            echo "</tr>";
            echo "</table>";
        }

        function costService($c, $s){
            if ($s == "Non Custodito") {
                return $c + 3;
            } else if ($s == "Custodito") {
                return $c + 5;
            } else {
                return $c;
            }
        }

        function discount($c, $lm){
            if (in_array("primo",$lm) && in_array("secondo", $lm) && (count($lm) == 2)) {
                $discount = ($c /100) * 10;
                return $c - $discount;
            } if (count($lm) == 3) {
                $discount = ($c /100) * 15;
                return $c - $discount;
            } else {
                return $c;
            }
            
        }

        function calculateCost($o, $prices){
            $cost = 0;
            foreach ($o["listaPasti"] as $key ) {
                $cost += $prices[$key];
            }
            $cost = discount($cost, $o["listaPasti"]);
            $cost = costService($cost, $o["servizio"]);
            return $cost;
        }

        function showPrices($prices){
            echo "<p>-------------------------------------------</p>";
            echo "<h3>Prezzi dei pasti</h3>";
            echo "<ul>"; 
            foreach ($prices as $key => $value) {
                echo "<li>$key: $value euro</li>";
            }
            echo "</ul>";
            echo "<p>-------------------------------------------</p>";
        }

        function getNote(){
            if (isset($_POST["note"])) {
                $n = $_POST["note"];
            } else {
                $n = "Nessuna nota inserita";
            }
            return $n;
        }

        function getListMeans(){
            if (isset($_POST["meals"])) {
                $list = $_POST["meals"];
            } else {
                $list = "";
            }
            return $list;
        }

        
         
    ?>
</body>
</html>