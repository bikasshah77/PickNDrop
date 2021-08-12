<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
        table tr td:last-child{
            width: 120px;
        }
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">GPS Details</h2>
                        <a href="create.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add Address</a>
                    </div>
                    <form method="post" action="index.php">
            <input type="text" name="search" placeholder="Enter Latitude." required/>
            <input type="text" name="search" placeholder="Enter Longitude." required/>
            <input type="submit" value="Search"/>
            </form>
                    <?php
                    require_once "config.php";
                    
                    // Attempt select query execution

            // (B) PROCESS SEARCH WHEN FORM SUBMITTED
            if (isset($_POST['search'])) {
            // (B1) SEARCH FOR USERS
            // require "index.php";

            // (B) CONNECT TO DATABASE
            try {
            $pdo = new PDO(
                "mysql:host=".DB_SERVER.";dbname=".DB_NAME,
                DB_USERNAME, DB_PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
            } catch (Exception $ex) { exit($ex->getMessage()); }

            // (C) SEARCH
            // $stmt = $pdo->prepare("SELECT * FROM `pickndrop` WHERE `Latitude` LIKE ?");
            $stmt = $pdo->prepare("SELECT * FROM `pickndrop` WHERE Latitude > ".$_POST['search']." LIKE ? ORDER BY Latitude ASC");
            // (SELECT * FROM bikas WHERE name > 4 ORDER BY name DESC)
            $stmt->execute(["%".$_POST['search']."%"]);
            $results = $stmt->fetchAll();
            if (isset($_POST['ajax'])) { echo json_encode($results); }



            // (B2) DISPLAY RESULTS
            if (count($results) > 0) {
                foreach ($results as $r) {

                    echo '<table class="table table-bordered table-striped">';
                    echo "<thead>";
                        echo "<tr>";
                            echo "<th>#</th>";
                            echo "<th>Address</th>";
                            echo "<th>Latutude</th>";
                            echo "<th>Longitude</th>";
                            echo "<th>Action</th>";
                        echo "</tr>";
                    echo "</thead>";
                   
                    echo "<tbody>";
                        echo "<tr>";
                            echo "<td>" . $r['id'] . "</td>";
                            echo "<td>" . $r['Address'] . "</td>";
                            echo "<td>" . $r['Latitude'] . "</td>";
                            echo "<td>" . $r['Longitude'] . "</td>";
                            echo "<td>";
                                echo '<a href="read.php?id='. $r['id'] .'" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                echo '<a href="update.php?id='. $r['id'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                echo '<a href="delete.php?id='. $r['id'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                            echo "</td>";
                        echo "</tr>";
                        echo "</tbody>";                            
                        echo "</table>";
                }
            } else { echo "No results found"; }
            }
 
                    // Close connection
                    mysqli_close($link);
                    ?>


                </div>
            </div>        
        </div>
    </div>
</body>
</html>