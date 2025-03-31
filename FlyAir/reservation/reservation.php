<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Details</title>
    <link rel="stylesheet" href="reservation.css">
</head>

<body>
    <div class="main">

        <div class="navbar" id="navbar">
            <div class="logo">
                <h1>FlyAir</h1>
            </div>
            <div class="nav">
                <ul>
                    <li><a href="../dashboard/index.php">Dashboard</a></li>
                    <li><a href="reservation.php">Reservations</a></li>
                    <li><a href="../profile/profile.php">Profile</a></li>
                    <li><a href="../login/index.php">Logout</a></li>
                </ul>
            </div>
        </div>

        <div class="content">
            <div class="h1div">
                <h1>Your Reservations</h1>
            </div>

            <div class="success">
                <p>success</p>
            </div>

            <div class="inforeservation">
                <?php


                session_start();

                if (!isset($_SESSION['user_id'])) {
                    header("Location: ../login/index.php");
                    exit();
                }
    

                unset($_SESSION['passengers']);

                $dbconn = mysqli_connect("localhost", "root", "", "flyAir");
                if (!$dbconn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                if (!isset($_SESSION['origin'], $_SESSION['destination'], $_SESSION['departure_date'])) {
                    exit;
                }

                $origin = trim($_SESSION['origin']);
                $destination = trim($_SESSION['destination']);
                $departure_date = trim($_SESSION['departure_date']);
                $return_date = isset($_SESSION['return_date']) ? trim($_SESSION['return_date']) : '';
                $_SESSION['flightStatus'] = 'Pending';

                if (empty($origin) || empty($destination) || empty($departure_date)) {
                    exit;
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['returnSelectFlight']) && !empty($_POST['returnflightID'])) {



                    $flight_id = mysqli_real_escape_string($dbconn, $_POST['returnflightID']); 

                    $get_flight = "SELECT * FROM flights WHERE flightID = '$flight_id'";
                    $flight_result = mysqli_query($dbconn, $get_flight);

                    if (!$flight_result) {
                        die("SQL Error: " . mysqli_error($dbconn));
                    }

                    if (mysqli_num_rows($flight_result) > 0) {

                        $row = mysqli_fetch_assoc($flight_result);

                        $_SESSION['returnFlightNumber'] = $row['flightNumber'];
                        $_SESSION['returnOrigin'] = $row['origin'];
                        $_SESSION['returnDestination'] = $row['destination'];
                        $_SESSION['returnDepartureDate'] = $row['departureDate'];
                        $_SESSION['return_date'] = $row['departureDate'];
                        $_SESSION['returnPrice'] = $row['price'];
                    }

                    header("Location: reservation.php");
                    exit();
                   
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selectflight']) && isset($_POST['flightID'])) {


                    $flight_id = mysqli_real_escape_string($dbconn, $_POST['flightID']);

                    $get_flight = "SELECT * FROM flights WHERE flightID = '$flight_id'";
                    $flight_result = mysqli_query($dbconn, $get_flight);

                    if (!$flight_result) {
                        die("SQL Error: " . mysqli_error($dbconn));
                    }

                    if (mysqli_num_rows($flight_result) > 0) {
                        $row = mysqli_fetch_assoc($flight_result);

                        $_SESSION['flightNumber'] = $row['flightNumber'];
                        $_SESSION['origin'] = $row['origin'];
                        $_SESSION['destination'] = $row['destination'];
                        $_SESSION['departure_date'] = $row['departureDate'];
                        $_SESSION['price'] = $row['price'];
                    }

                    header("Location: reservation.php");
                    exit();
                }



                if (isset($_POST['discard'])) {
                    header("Location: ../dashboard/index.php");
                    exit();
                }

                if (isset($_POST['continue'])) {
                    if($_SESSION['onedirectionFlight'] === true || $_SESSION['returnFlight'] === true){
                        header("Location: ../passengers/passengers.php");
                        exit();
                    }else{
                        echo"<script> 
                            alert('Zgjedhni fluturimin!')
                            </script>";
                    }
                }

                ?>

                <?php

                $_SESSION['onedirectionFlight'] = false;

                $sql = "SELECT * FROM flights WHERE origin = '$origin' AND destination = '$destination' AND departureDate = '$departure_date'";
                $result = mysqli_query($dbconn, $sql);

                $result = mysqli_query($dbconn, $sql);

                if (!$result) {
                    die("Gabim në query: " . mysqli_error($dbconn));
                }

                if (mysqli_num_rows($result) > 0) {
                    echo '
                    <h1> Reservations Details</h1>
                    <br>
                    <br>
                    <h3>Flight</h3>
                    <br>
                    <p><span>Flight Number: </span>' . $_SESSION['flightNumber'] . '</p>
                    <p><span>From: </span>' . $_SESSION['origin'] . '</p>
                    <p><span>To: </span>' . $_SESSION['destination'] . '</p>
                    <p><span>Departure: </span>' . $_SESSION['departure_date'] . '</p>
                    <p><span>Return:</span></p>
                    <p><span>Total Amount: </span>' . $_SESSION['price'] . '</p>
                    <p><span>Status: </span>' . $_SESSION['flightStatus'] . '</p>';

                    $_SESSION['onedirectionFlight'] = true;
                } else {
                    echo "<h1 style='color:red;'>Nuk ka fluturime ne kete date!</h1>";
                }

                ?>

            </div>

            <div class="tableinfo">
                <h3>Avaliable Flights to Change Flight</h3>
                <div class="tablecontainer">
                    <?php

                    $sql = "SELECT * FROM flights WHERE origin = '$origin' AND destination = '$destination'";
                    $result = mysqli_query($dbconn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        echo '<table>
                                <tr>
                                    <th>Flight Number </th>
                                    <th>From </th>
                                    <th>To </th>
                                    <th>Departure </th>
                                    <th>Return </th>
                                    <th>Price </th>
                                    <th>Select </th>
                                </tr>';
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>
                                    <td>' . $row['flightNumber'] . '</td>
                                    <td>' . $row['origin'] . '</td>
                                    <td>' . $row['destination'] . '</td>
                                    <td>' . $row['departureDate'] . '</td>
                                    <td> </td>
                                    <td>' . $row['price'] . '</td>
                                    <td>
                                        <form method="post">
                                            <input type="hidden" value="' . $row['flightID'] . '" name="flightID"> 
                                            <input type="submit" value="Select Flight" name="selectflight" id="selectflight"> 
                                        </form>
                                    </td>
                                </tr>';
                        }

                        echo '</table>';
                    }

                    ?>
                </div>

                <div class="inforeservation" id="returnFlightReservation">

                    <?php


                    $_SESSION['returnFlight'] = false;

                    if (!empty($_SESSION['return_date'])) {
                        $return_date = $_SESSION['return_date'];

                        $sql = "SELECT * FROM flights WHERE origin = '$destination' AND destination = '$origin' AND departureDate = '$return_date'";
                        $result = mysqli_query($dbconn, $sql);

                        if (!$result) {
                            die("Error in query: " . mysqli_error($dbconn));
                        }

                        if (mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);

                            $_SESSION['returnFlightID'] = $row['flightID'];
                            $_SESSION['returnFlightNumber'] = $row['flightNumber'];
                            $_SESSION['returnOrigin'] = $row['origin'];
                            $_SESSION['returnDestination'] = $row['destination'];
                            $_SESSION['returnDepartureDate'] = $row['departureDate'];
                            $_SESSION['returnPrice'] = $row['price'];

                            $_SESSION['returnFlight'] = true;

                            

                            echo "<h1>Return Details</h1>
                            <br>
                            <h3>Flight</h3>
                            <br>
                            <p><span>Flight Number:</span> " . $_SESSION['returnFlightNumber'] . "</p>
                            <p><span>From: </span>" . $_SESSION['returnOrigin'] . "</p>
                            <p><span>To: </span>" . $_SESSION['returnDestination'] . "</p>
                            <p><span>Departure: </span>" . $_SESSION['returnDepartureDate'] . "</p>
                            <p><span>Return: </span></p>
                            <p><span>Total Amount:</span> $" . $_SESSION['returnPrice'] . "</p>
                            <p><span>Status: </span>" . $_SESSION['flightStatus'] .  "</p>";
                        } else {
                            echo "<h1><span style='color:red;'>Nuk ka fluturim kthimi per daten e caktuar!</span></h1>";
                        }
                    }

                    ?>

                </div>

                <div class="tableinfo">
                    <?php

                    if (!empty($_SESSION['return_date'])) {
                        $return_date = $_SESSION['return_date'];


                        $sql = "SELECT * FROM flights WHERE origin = '$destination' AND destination = '$origin'";
                        $result = mysqli_query($dbconn, $sql);

                        if (!$result) {
                            die("Error in query: " . mysqli_error($dbconn));
                        }

                        if (mysqli_num_rows($result) > 0) {
                            echo '<div class="tablecontainer">
                                <table>
                                    <tr>
                                        <th>Flight Number </th>
                                        <th>From </th>
                                        <th>To </th>
                                        <th>Departure </th>
                                        <th>Return </th>
                                        <th>Price </th>
                                        <th>Select </th>
                                    </tr>';
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr>
                                        <td>' . $row['flightNumber'] . '</td>
                                        <td>' . $row['origin'] . '</td>
                                        <td>' . $row['destination'] . '</td>
                                        <td>' . $row['departureDate'] . '</td>
                                        <td> </td>
                                        <td>' . $row['price'] . '</td>
                                        <td>
                                            <form method="post" action="">
                                                <input type="hidden" value="' . $row['flightID'] . '" name="returnflightID"> 
                                                <input type="submit" value="Select Flight" name="returnSelectFlight" id="selectflight"> 
                                            </form>
                                        </td>
                                    </tr>';
                            }

                            echo '</table>
                                </div>';
                        }
                    }else{
                        echo mysqli_error($dbconn);
                    }

                    ?>
                </div>

                <div class="buttons">
                    <form method="post">
                        <input type="submit" name="discard" id="discard" value="Discard">
                        <input type="submit" name="continue" id="continue" value="Continue">
                    </form>
                </div>

            </div>
        </div>

    </div>
</body>

</html>