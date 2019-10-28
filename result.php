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
                        <a href="#" class="list-group-item list-group-item-action flex-column align-items-start active">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">List group item heading</h5>
                                <small>3 days ago</small>
                            </div>
                            <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget
                                risus varius blandit.</p>
                            <small>Donec id elit non mi porta.</small>
                        </a>
                    </div>


                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <p>
                        this is where the House info will go

                    </p>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>
                                Crime
                            </th>
                            <th>
                                Number
                            </th>

                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        echo "<tr>
                         <td>";
                        echo "Murder";
                        echo " </td>
                            <td>";
                        echo "18";
                        echo "</td>

                        </tr>";
                        ?>

                        </tbody>
                    </table>
                </div>

            </div>

            <div class="row">

                <div class="col-md-6">
                    <p>
                        Flood data?

                    </p>

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