<?php
session_start();
$attom = $_SESSION["attom-api"];
$crime = $_SESSION["crime-api"];
$flood = $_SESSION["flood-api"];
$yelp = $_SESSION["yelp-api"];
$map = $_SESSION["map-api"];


$map = base64_decode($map);
file_put_contents("images/map.jpg", $map);

print_r($attom);
?>


<!DOCTYPE html>
<html lang="en">
<head>

    <title>Results For Houses</title>


    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <style> div {
            border: #0b0c0c solid;
        }</style>

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
                    <img alt="Google Map" src="images/map.jpg" width="100%">
                </div>


                <div class="col-md-4" style="overflow-y:auto;height:auto;">


                    <div class="list-group">

                        <?php
                        $line1 = array();
                        for ($x = 0; $x < 5; $x++){
                            $line1[$x] = $attom[$x]["line1"];
                            $line2[$x] = $attom[$x]["line2"];

                            echo "
                            <a href=\"#\" class=\"list-group-item list-group-item-action flex-column align-items-start active\">
                            <div class=\"d-flex w-100 justify-content-between\">
                                <h5 class=\"mb-1\">".$line1."</h5>"."
                            </div>
                            <p class=\"mb-1\">".$line2."</p>
                        </a>
                            ";

                        }


                        ?>
                    </div>


                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <p>


                    </p>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered table-hover">
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
                        for ($c=0;$c<12;$c++){
                            $crime_name = $crime[$c];
                            $crime_num = $crime[$crime_name];

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

            <div class="row">

                <div class="col-md-6">

                    <?php

                    for ($f=0;$f<5;$f++){

                        $flood_risk = $flood[$f]["flood-risk"];
                        $flood_zone = $flood[$f]["flood-zone"];


                        if (!isset($flood_risk)){
                            $flood_risk = "Flood API Does Not Have Risk for this Address";
                        }
                        if (!isset($flood_zone)){
                            $flood_zone = "Flood API Does Not Have Zone for this Address";
                        }




                        echo "
                        
                        <h6>Flood Risk: ".$flood_risk." </h6>
                        <h6>Flood Zone: ".$flood_zone." </h6>
                        
                        ";


                    }


                    ?>

                </div>

                <div class="col-md-6">

                    <p>
                        Yelp?
                    </p>

                </div>
            </div>


            <script type="text/javascript">
                <!--
                function toggle_visibility(id) {
                    var e = document.getElementById(id);
                    if (e.style.display == 'block')
                        e.style.display = 'none';
                    else
                        e.style.display = 'block';
                }

                //-->
            </script>
            <script src="js/jquery.min.js"></script>
            <script src="js/bootstrap.min.js"></script>
            <script src="js/scripts.js"></script>
</body>
</html>