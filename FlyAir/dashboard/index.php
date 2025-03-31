<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="main">

        <div class="navbar" id="navbar">
            <div class="logo">
                <h1>FlyAir</h1>
            </div>
            <div class="nav">
                <ul>
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="../reservations-list/reservation-list.php">Reservations</a></li>
                    <li><a href="../profile/profile.php">Profile</a></li>
                    <li><a href="../login/index.php">Logout</a></li>
                </ul>
            </div>
        </div>

        <div class="content">
            <div class="welcome">
                <h1>Welcome!</h1>
            </div>
            <div class="reservationform">
                <h1>Make a reservation</h1>
                <div class="form">

                    <?php
                    session_start();

                    if (!isset($_SESSION['user_id'])) {
                        header("Location: ../login/index.php");
                        exit();
                    }

                    unset($_SESSION['return_date']);

                    $dbconn = mysqli_connect("localhost", "root", "", "flyAir");
                    if (!$dbconn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    if (isset($_POST['search'])) {
                        if (!empty($_POST['origin']) && !empty($_POST['destination'])  && !empty($_POST['departuredate'])) {

                            $_SESSION['origin'] = $_POST['origin'];
                            $_SESSION['destination'] = $_POST['destination'];
                            $_SESSION['return_date'] = isset($_POST['returndate']) ? mysqli_real_escape_string($dbconn, $_POST['returndate']) : NULL;
                            $_SESSION['departure_date'] = $_POST['departuredate'];


                            $query = "SELECT flightNumber, price FROM flights WHERE origin = ? AND destination = ? AND departureDate = ?";
                            $stmt = mysqli_prepare($dbconn, $query);
                            if ($stmt) {
                                mysqli_stmt_bind_param($stmt, "sss", $_SESSION['origin'], $_SESSION['destination'],  $_SESSION['departure_date']);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);

                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $_SESSION['flightNumber'] = $row['flightNumber'];
                                        $_SESSION['flightStatus'] = 'Pending';
                                        $_SESSION['price'] = $row['price'];

                                        header("Location: ../reservation/reservation.php");
                                        exit();
                                    }
                                } else {
                                    // echo "<script> alert('Nuk ka fluturime ne kete date!'); </script>";
                                    header("Location: ../reservation/reservation.php");
                                    exit();
                                }

                                mysqli_stmt_close($stmt);
                            }
                        } else {
                            echo "<script>alert('Ju lutemi zgjedheni vendin nga ku deshironi te udhetoni, daten dhe destinacionin!');</script>";
                        }
                    }
                    ?>



                    <form method="POST">
                        <div class="chechkdiv">
                            <input type="checkbox" name="returnTrip" id="returnTripCheckbox">
                            <label class="check" for="">Return Trip</label>
                        </div>
                        <br>
                        <div class="tripdiv">
                            <div>
                                <label for="">Origin</label>
                                <select name="origin" id="origin">
                                    <option value="">Select Origin</option>
                                    <option value="Prishtina International Airport Adem Jashari">Prishtina</option>
                                    <option value="Tirana International Airport Nene Tereza">Tirana</option>
                                    <option value="Skopje International Airport">Skopje</option>
                                    <option value="Vienna International Airport">Vienna</option>
                                    <option value="Frankfurt Airport">Frankfurt</option>
                                    <option value="Amsterdam Schiphol Airport">Amsterdam</option>
                                    <option value="Charles de Gaulle Airport">Paris</option>
                                    <option value="Madrid-Barajas Adolfo Suarez Airport">Madrid</option>
                                    <option value="Zurich Airport">Zurich</option>
                                    <option value="Leonardo da Vinci-Fiumicino Airport">Rome</option>
                                    <option value="Stuttgart Airport">Stuttgart</option>
                                    <option value="Düsseldorf Airport">Düsseldorf</option>
                                </select>
                            </div>
                            <div>
                                <label for="">Destination</label>
                                <select name="destination" id="destination">
                                    <option value="">Select Destination</option>
                                    <option value="Prishtina International Airport Adem Jashari">Prishtina</option>
                                    <option value="Tirana International Airport Nene Tereza">Tirana</option>
                                    <option value="Skopje International Airport">Skopje</option>
                                    <option value="Vienna International Airport">Vienna</option>
                                    <option value="Frankfurt Airport">Frankfurt</option>
                                    <option value="Amsterdam Schiphol Airport">Amsterdam</option>
                                    <option value="Charles de Gaulle Airport">Paris</option>
                                    <option value="Madrid-Barajas Adolfo Suarez Airport">Madrid</option>
                                    <option value="Zurich Airport">Zurich</option>
                                    <option value="Leonardo da Vinci-Fiumicino Airport">Rome</option>
                                    <option value="Stuttgart Airport">Stuttgart</option>
                                    <option value="Düsseldorf Airport">Düsseldorf</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="datediv">
                            <div class="departuredate">
                                <label for="">Departure Date</label>
                                <input type="date" name="departuredate" id="departuredate">
                            </div>
                            <div class="returndate" style="display: none;">
                                <label for="">Return Date</label>
                                <input type="date" name="returndate" id="returndate">
                            </div>
                        </div>

                        <input type="submit" value="Search" name="search" id="search">
                    </form>
                </div>
            </div>

            <div class="destinations">
                <div>
                    <h1>Explore Our Destinations</h1>
                </div>

                <div class="chossedestination">
                    <div>
                        <div class="destination">
                            <div class="img">
                                <img src="images/tirana.jpg" alt="">
                            </div>
                            <div class="info">
                                <h3>Fly to Tirana</h3>
                                <p>Flight starting from 100€</p>
                            </div>
                        </div>
                        <div class="destination">
                            <div class="img">
                                <img src="images/pristina.jpg" alt="">
                            </div>
                            <div class="info">
                                <h3>Fly to Prishtina</h3>
                                <p>Flight starting from 100€</p>
                            </div>
                        </div>
                        <div class="destination">
                            <div class="img">
                                <img src="images/skopje.jpg" alt="">
                            </div>
                            <div class="info">
                                <h3>Fly to Skopje</h3>
                                <p>Flight starting from 100€</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="destination">
                            <div class="img">
                                <img src="images/vienna.jpg" alt="">
                            </div>
                            <div class="info">
                                <h3>Fly to Vienna</h3>
                                <p>Flight starting from 100€</p>
                            </div>
                        </div>
                        <div class="destination">
                            <div class="img">
                                <img src="images/rome.jpg" alt="">
                            </div>
                            <div class="info">
                                <h3>Fly to Rome</h3>
                                <p>Flight starting from 100€</p>
                            </div>
                        </div>
                        <div class="destination">
                            <div class="img">
                                <img src="images/amsterdam.jpg" alt="">
                            </div>
                            <div class="info">
                                <h3>Fly to Amsterdam</h3>
                                <p>Flight starting from 100€</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="destination">
                            <div class="img">
                                <img src="images/paris.jpg" alt="">
                            </div>
                            <div class="info">
                                <h3>Fly to Paris</h3>
                                <p>Flight starting from 100€</p>
                            </div>
                        </div>
                        <div class="destination">
                            <div class="img">
                                <img src="images/madrid.jpg" alt="">
                            </div>
                            <div class="info">
                                <h3>Fly to Madrid</h3>
                                <p>Flight starting from 100€</p>
                            </div>
                        </div>
                        <div class="destination">
                            <div class="img">
                                <img src="images/zurich.jpg" alt="">
                            </div>
                            <div class="info">
                                <h3>Fly to Zurich</h3>
                                <p>Flight starting from 100€</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <script src="script.js"></script>
</body>

</html>