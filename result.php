<?php
session_start();

if ($_SESSION['active'] != 'true') {
    $_SESSION['message'] = "You must log in before viewing this page!";
    header("location: error.php");
}


$attom = $_SESSION["attom-api"];
$crime = $_SESSION["crime-api"];
$flood = $_SESSION["flood-api"];
$yelp = $_SESSION["yelp-api"];
$map = $_SESSION["map-api"];


//$map = base64_decode($map);
//file_put_contents("images/googleMap.png", $map);

//print_r($yelp);
?>


<!DOCTYPE html>
<html lang="en">
<head>

    <title>Results For Houses</title>


    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <style> div{
            border: 1px solid #a0b3b0;
            align-content: space-around;
        }
        .address{
            border: none;
        }

    </style>

</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h3>
                Housing Info.
            </h3>
            <a href="main.php"> Go back to search</a>
            <div class="row">


                <div class="col-md-8">
                    <img alt="Google Map" src="images/googleMap.png" width="100%">
                </div>


                <div class="col-md-4" style="overflow-y:auto;">


                    <div class="list-group">

                        <?php

                        for ($x = 0; $x <= sizeof($attom); $x++) {
                            $line1 = $attom[$x]["line1"];
                            $line2 = $attom[$x]["line2"];

                            if (!isset($line1)) {
                                break;
                            }

                            echo "
                            <a href=\"#\" onclick=\"toggley('yelp$x');togglef('flood$x')\" 
                            class=\"address\"
                            >
                            <div class=\"d-flex w-100 justify-content-between\">
                                <h4 class=\"mb-1\">";

                            echo $x+1 . "). " . $line1 .
                                "</h4>
                            </div>
                            <h6 class=\"mb-1\">" . $line2 . "</h6>
                            </a>
                            ";


                        }


                        ?>
                    </div>


                </div>
            </div>
            <div class="row">
                <div class="col-md-6">

                    <h4>Flood Data</h4>

                    <?php


                    for ($f = 0; $f < sizeof($flood); $f++) {


                        $flood_risk = $flood[$f]["flood-risk"];
                        $flood_zone = $flood[$f]["flood-zone"];


                        if (!isset($flood_risk)) {
                            $flood_risk = "Flood API Does Not Have Risk for this Address";
                        }
                        if (!isset($flood_zone)) {
                            $flood_zone = "Flood API Does Not Have Zone for this Address";
                        }


                        echo "
                        <div id=\"flood$f\" style=\"display: none\">
                        
                        <h6 class=\"flood\">Flood Risk:" . $flood_risk . " </h6>
                        <h6 class=\"flood\">Flood Zone:" . $flood_zone . " </h6>
                        
                        
                        ";

                        echo "
                        <a  
                        href= \"images/fema-flood-zone.pdf\"
                        style=\"
                        text-align: center;
                        padding-left: 40%;
                        padding-right: 15%;
                        
                        
                        \"
                        > Click to view Flood Zone Category Guide</a>
                        
                       
                        ";
                        echo "</div>";


                    }
                    ?>
                    <br><br>

                    <h4>Yelp Data</h4>
                    <div>

                        <?php

                        for ($y = 0; $y <= sizeof($yelp); $y++) {


                            echo "
                             <div id=\"yelp$y\" style=\"display: none\">
                             ";

                            for ($r = 0; $r <= sizeof($yelp[$y]); $r++) {
                                $name = $yelp[$y]["res_name"][$r];


                                echo "<h5 class=\"mb-1\">" . $name . "</h5>";

                                $location = $yelp[$y]["location"][$r];
                                $location = $location[0] . " " . $location[1];
                                echo "<h6 class=\"mb-1\">" . $location . "</h6>";


                            }
                            echo "</div>";


                        }


                        ?>

                    </div>
                </div>


                <div class="col-md-6">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>
                                Crime by County
                            </th>
                            <th>
                                Number
                            </th>

                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        foreach ($crime as $c => $crime_name) {
                            $crime_name = $c;
                            $crime_num = $crime[$c];

                            $crime_name = ucwords(str_replace("-", " ", $crime_name));


                            echo "<tr>
                         <td>";
                            echo $crime_name;
                            echo " </td>
                            <td>";
                            echo $crime_num;
                            echo "</td>

                        </tr>";
                        }


                        ?>

                        </tbody>
                    </table>
                </div>

            </div>


            <script>

                function click($one, $two) {

                    console.log("click run ");
                    togglef($one);
                    toggley($two);
                }


                function togglef($id) {
                    console.log("flood runnging");
                    var x = document.getElementById($id);
                    var flood = "flood";
                    var yelp = "yelp";


                    if (x === document.getElementById(flood.concat("0"))) {
                        x.style.display = "block";
                    } else {
                        document.getElementById(flood.concat("0")).style.display = "none";
                    }

                    if (x === document.getElementById(flood.concat("1"))) {
                        x.style.display = "block";
                    } else {
                        document.getElementById(flood.concat("1")).style.display = "none";
                    }


                    if (x === document.getElementById(flood.concat("2"))) {
                        x.style.display = "block";
                    } else {
                        document.getElementById(flood.concat("2")).style.display = "none";
                    }


                    if (x === document.getElementById(flood.concat("3"))) {
                        x.style.display = "block";
                    } else {
                        document.getElementById(flood.concat("3")).style.display = "none";
                    }


                    if (x === document.getElementById(flood.concat("4"))) {
                        x.style.display = "block";
                    } else {
                        document.getElementById(flood.concat("4")).style.display = "none";
                    }


                }


                function toggley($id) {
                    console.log("yelp runing.");
                    var x = document.getElementById($id);
                    var flood = "flood";
                    var yelp = "yelp";


                    if (x === document.getElementById(yelp.concat("0"))) {
                        x.style.display = "block";
                    } else {
                        document.getElementById(yelp.concat("0")).style.display = "none";
                    }

                    if (x === document.getElementById(yelp.concat("1"))) {
                        x.style.display = "block";
                    } else {
                        document.getElementById(yelp.concat("1")).style.display = "none";
                    }


                    if (x === document.getElementById(yelp.concat("2"))) {
                        x.style.display = "block";
                    } else {
                        document.getElementById(yelp.concat("2")).style.display = "none";

                    }


                    if (x === document.getElementById(yelp.concat("3"))) {
                        x.style.display = "block";
                    } else {
                        document.getElementById(yelp.concat("3")).style.display = "none";
                    }


                    if (x === document.getElementById(yelp.concat("4"))) {
                        x.style.display = "block";
                    } else {
                        document.getElementById(yelp.concat("4")).style.display = "none";
                    }



                }
            </script>
            <script src="js/jquery.min.js"></script>
            <script src="js/bootstrap.min.js"></script>
            <script src="js/scripts.js"></script>
</body>
</html>
