<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Airports</title>
    <link rel="stylesheet" href="manageAirports.css">
</head>

<body>
    <div class="main">

        <div class="navbar">
            <div class="nav">
                <h1>FlyAir</h1>
                <a href="../dashboard-admin/dashboard-admin.php">Dashboard</a>
            </div>
        </div>

        <div class="content">
            <div>
                <h1>Manage Airports</h1>
            </div>

            <?php

            session_start();

            if (!isset($_SESSION['user_id'])) {
                header("Location: ../login/index.php");
                exit();
            }


            $dbconn = mysqli_connect("localhost", "root", "", "flyAir");
            if (!$dbconn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            if (isset($_POST['delete'])) {
                $airportid = $_POST['airportid'];

                $sql = "DELETE FROM airports WHERE airportsID='$airportid'";
                $result = mysqli_query($dbconn, $sql);

                if ($result) {
                    $_SESSION['success_message'] = "The airport was deleted successfully";

                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    echo mysqli_error($dbconn);
                }
            }

            if (isset($_POST['createNewAirport'])) {
                $name = $_POST['airportName'];
                $location = $_POST['location'];

                $sql = "INSERT INTO airports (name,location)
                VALUES ('$name','$location')";
                $result = mysqli_query($dbconn, $sql);

                if ($result) {
                    $_SESSION['success_message'] = "The airport was added successfully";

                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    echo mysqli_error($dbconn);
                }
            }


            if (isset($_POST['update'])) {

                $name = $_POST['airport'];
                $location = $_POST['airportLocation'];
                $airportid = $_POST['airportid'];

                $sql = "UPDATE airports SET name='$name',
                                            location='$location'
                        WHERE airportsID='$airportid'";
                $result = mysqli_query($dbconn, $sql);

                if ($result) {
                    $_SESSION['success_message'] = "The airport was updated successfully";

                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    echo mysqli_error($dbconn);
                }
            }



            if(isset($_SESSION['success_message'])){
                echo '<div style="width: 100%; padding: 10px; background-color: #d1fbe5;
                                  font-size: 20px; color: green; border-radius: 10px;
                                  margin-top: 15px; margin-bottom: 15px; text-align:center;">
                        <p>'.$_SESSION['success_message'].'</p>
                      </div>';
                unset($_SESSION['success_message']);
            }


            ?>

            <div class="form">
                <form method="post" action="">
                    <input type="text" name="airportName" id="airportName" placeholder="Airport Name">
                    <input type="text" name="location" id="location" placeholder="Locaiton">
                    <input type="submit" name="createNewAirport" id="createNewAirport" value="Create New Airport">
                </form>
            </div>

            <div class="manageAirportsTable">
                <?php

                $sql = "SELECT * FROM airports";
                $result = mysqli_query($dbconn, $sql);

                if ($result) {
                    echo '<table>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Action</th>
                            </tr>';
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>
                                <form method="post" action="">
                                    <td>' . $row['airportsID'] . '</td>
                                    <td>
                                        <input type="text" name="airport" value="' . $row['name'] . '">
                                    </td>
                                    <td>
                                        <input type="text" name="airportLocation" value="' . $row['location'] . '">
                                    </td>
                                    <td>
                                        <div>
                                            <input type="submit" name="update" id="update" value="Update">
                                            <input type="hidden" name="airportid" value="' . $row['airportsID'] . '">
                                            <input type="submit" name="delete" id="delete" value="Delete">
                                            
                                        </div>
                                    </td>
                                </form>';
                    }

                    echo '</tr>
                        </table>';
                }

                ?>

            </div>
        </div>

    </div>
</body>

</html>