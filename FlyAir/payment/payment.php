<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="paymentStyle.css">
</head>

<body>
    <div class="main overlay">

        <div class="navbar" id="navbar">
            <div class="logo">
                <h1>FlyAir</h1>
            </div>
            <div class="nav">
                <ul>
                    <li><a href="../dashboard/index.php">Dashboard</a></li>
                    <li><a href="">Reservations</a></li>
                    <li><a href="">Profile</a></li>
                    <li><a href="../login/index.php">Logout</a></li>
                </ul>
            </div>
        </div>

        <div class="content">
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

            $origin = $_SESSION['origin'];
            $destination = $_SESSION['destination'];
            $departureDate = $_SESSION['departure_date'];
            $price = $_SESSION['price'];

            $getFlightid = "SELECT flightID FROM flights WHERE origin = '$origin' AND destination = '$destination' AND departureDate = '$departureDate'";
            $sql = mysqli_query($dbconn, $getFlightid);

            if (mysqli_num_rows($sql) > 0) {
                $row = mysqli_fetch_assoc($sql);

                $_SESSION['flightID'] = $row['flightID'];
            } else {
                die("SQL Error: " . mysqli_error($dbconn));
            }

            if (isset($_POST['discard'])) {
                header("Location: ../passengers/passengers.php");
                exit();
            }

            if (isset($_POST['go_dashboard'])) {
                header("Location: ../dashboard/index.php");
                exit();
            }



            ?>
            <div>
                <h1>Payment Details</h1>
            </div>

            <div class="reservationPayment">
                <h2>Reservation </h2>
                <p><span>Flight ID:</span> <?php echo $_SESSION['flightID'] ?></p>
                <p><span>Departure Date:</span> <?php echo $_SESSION['departure_date']  ?></p>
                <p><span>Return Date:</span></p>

                <h3>Passengers</h3>

                <?php

                if (!empty($_SESSION['passengers'])) {
                    echo '<table border="1">
                            <tr>
                                <th>Full Name</th>
                                <th>Gewicht</th>
                                <th>Price</th>
                            </tr>';

                    foreach ($_SESSION['passengers'] as $index => $passenger) {
                        echo "<tr>
                                <td>{$passenger['fullname']}</td>
                                <td>20kg</td>
                                <td>{$price}</td>
                              </tr>";
                    }

                    echo '</table>';
                }

                if (!empty($_SESSION['return_date'])) {

                    $returnOrigin = $_SESSION['returnOrigin'];
                    $returnDestination = $_SESSION['returnDestination'];
                    $returnDate = $_SESSION['returnDepartureDate'];
                    $returnPrice = $_SESSION['returnPrice'];

                    $getFlightid = "SELECT * from flights WHERE origin = '$returnOrigin' AND  destination = '$returnDestination' AND departureDate = '$returnDate'";
                    $sql = mysqli_query($dbconn, $getFlightid);

                    if (mysqli_num_rows($sql) > 0) {
                        $row = mysqli_fetch_assoc($sql);

                        $_SESSION['returnFlightID'] = $row['flightID'];

                        echo '<h2 class="returnInfo">Return Reservation</h2>
                        <p><span>Flight ID: </span>' .  $_SESSION['returnFlightID'] . '</p>
                        <p><span>Departure Date: </span>' . $returnDate . '</p>
                        <p><span>Return Date: </span></p>
                        <h3>Passengers</h3>
                        <table border="1">
                            <tr>
                                <th>Full Name</th>
                                <th>Gewicht</th>
                                <th>Price</th>
                            </tr>';

                        foreach ($_SESSION['passengers'] as $index => $passenger) {
                            echo "<tr>
                                    <td>{$passenger['fullname']}</td>
                                    <td>20kg</td>
                                    <td>" . $_SESSION['returnPrice'] . "</td>
                                  </tr>";
                        }

                        echo '</table>';
                    }
                }

                ?>
            </div>

            <div class="paymentInformation">
                <h2>Payment Information</h2>
                <?php

                $_SESSION['paymentFinished'] = false;
                $_SESSION['passengersFinished'] = false;
                $_SESSION['reservationFinished'] = false;

                if (isset($_POST['finishPayment'])) {
                    if (!empty($_POST['cardNumber']) && !empty($_POST['cardholderName']) && !empty($_POST['expirationDate'])) {
                        $_SESSION['cardNumber'] = $_POST['cardNumber'];
                        $_SESSION['cardholderName'] = $_POST['cardholderName'];
                        $_SESSION['expirationDate'] = $_POST['expirationDate'];
                        $flightID = $_SESSION['flightID'];
                        

                        if (isset($_SESSION['user_id'])) {
                            $userID = (int) $_SESSION['user_id'];
                        }

                        $cardNumber = $_POST['cardNumber'];
                        $cardholderName = $_POST['cardholderName'];
                        $expirationDate = $_POST['expirationDate'];
                        



                        if (!empty($_SESSION['returnFlight']) && $_SESSION['returnFlight'] === true) {
                            $returnFlightID = $_SESSION['returnFlightID'];
                            $returnPrice = $_SESSION['returnPrice'];

                            $sql1 = "INSERT INTO payments (user_id, amount, card_number, cardholder_name, expiration_date, flight_id) 
                                VALUES ('$userID','$price','$cardNumber','$cardholderName','$expirationDate','$flightID')";
                            $sql2 = "INSERT INTO payments (user_id, amount, card_number, cardholder_name, expiration_date, flight_id) 
                                VALUES ('$userID','$returnPrice','$cardNumber','$cardholderName','$expirationDate','$returnFlightID')";
                            $result1 = mysqli_query($dbconn, $sql1);
                            $result2 = mysqli_query($dbconn, $sql2);

                            if ($result1 && $result2) {
                                if (!empty($_SESSION['passengers'])) {
                                    foreach ($_SESSION['passengers'] as $passenger) {
                                        $fullname = mysqli_real_escape_string($dbconn, $passenger['fullname']);
                                        $gender = mysqli_real_escape_string($dbconn, $passenger['gender']);
                                        $personalNumber = mysqli_real_escape_string($dbconn, $passenger['personalNumber']);
                                        $passportNumber = mysqli_real_escape_string($dbconn, $passenger['passportNumber']);
                                        $dateOfBirth = mysqli_real_escape_string($dbconn, $passenger['dateOfBirth']);

                                        $flight_id = $_SESSION['flightID'];

                                        $check_query = "SELECT * FROM passengers WHERE personal_number = '$personalNumber' AND passport_number = '$passportNumber'";
                                        $check_result = mysqli_query($dbconn, $check_query);

                                        if (mysqli_num_rows($check_result) > 0) {


                                            $getPaymentID = "SELECT * FROM payments WHERE card_number = '$cardNumber' AND cardholder_name = '$cardholderName'";
                                            $result = mysqli_query($dbconn, $getPaymentID);

                                            if (mysqli_num_rows($result) > 0) {
                                                $row = mysqli_fetch_assoc($result);

                                                $paymentID = $row['paymentID'];


                                                $getPassengerID = "SELECT * FROM passengers WHERE personal_number = '$personalNumber' OR passport_number = '$passportNumber'";
                                                $result = mysqli_query($dbconn, $getPassengerID);


                                                $row = mysqli_fetch_assoc($result);

                                                $passengerID = $row['passangerID'];

                                                $_SESSION['status_reservation'] = 'Pending';
                                                $staus_reservation = $_SESSION['status_reservation'];

                                                $sql1 = "INSERT INTO reservations (user_id, flight_id, departure_date, total_amount, passenger_id, payment_id, status_reservation)
                                                        VALUES ('$userID', '$flight_id', '$departureDate','$price','$passengerID', '$paymentID', '$staus_reservation')";
                                                $sql2 = "INSERT INTO reservations (user_id, flight_id, departure_date, total_amount, passenger_id, payment_id, status_reservation)
                                                        VALUES ('$userID', '$returnFlightID', '$returnDate','$returnPrice','$passengerID', '$paymentID', '$staus_reservation')";

                                                $result1 = mysqli_query($dbconn, $sql1);
                                                $result2 = mysqli_query($dbconn, $sql2);

                                                if ($result1 && $result2) {
                                                    $_SESSION['reservationFinished'] = true;
                                                } else {
                                                    echo "Gabim në insertimin e rezervimit: " . mysqli_error($dbconn);
                                                }
                                            } else {
                                                echo "Gabim në marrjen e ID se payment: " . mysqli_error($dbconn);
                                            }


                                            $_SESSION['passengersFinished'] = true;
                                        } else {
                                            $sql = "INSERT INTO passengers (user_id, full_name, gender, personal_number, passport_number, date_of_birth) 
                                                    VALUES ('$userID','$fullname', '$gender', '$personalNumber', '$passportNumber', '$dateOfBirth')";

                                            $result = mysqli_query($dbconn, $sql);

                                            if (!$result) {
                                                echo mysqli_error($dbconn);
                                            } else {
                                                $getPaymentID = "SELECT * FROM payments WHERE card_number = '$cardNumber' AND cardholder_name = '$cardholderName'";
                                                $result = mysqli_query($dbconn, $getPaymentID);

                                                if (mysqli_num_rows($result) > 0) {
                                                    $row = mysqli_fetch_assoc($result);

                                                    $paymentID = $row['paymentID'];


                                                    $getPassengerID = "SELECT * FROM passengers WHERE personal_number = '$personalNumber' OR passport_number = '$passportNumber'";
                                                    $result = mysqli_query($dbconn, $getPassengerID);


                                                    $row = mysqli_fetch_assoc($result);

                                                    $passengerID = $row['passangerID'];

                                                    $_SESSION['status_reservation'] = 'Pending';
                                                    $staus_reservation = $_SESSION['status_reservation'];

                                                    $sql1 = "INSERT INTO reservations (user_id, flight_id, departure_date, total_amount, passenger_id, payment_id, status_reservation)
                                                            VALUES ('$userID', '$flight_id', '$departureDate','$price','$passengerID', '$paymentID', '$staus_reservation')";
                                                    $sql2 = "INSERT INTO reservations (user_id, flight_id, departure_date, total_amount, passenger_id, payment_id, status_reservation)
                                                            VALUES ('$userID', '$returnFlightID', '$returnDate','$returnPrice','$passengerID', '$paymentID', '$staus_reservation')";

                                                    $result1 = mysqli_query($dbconn, $sql1);
                                                    $result2 = mysqli_query($dbconn, $sql2);

                                                    if ($result1 && $result2) {
                                                        $_SESSION['reservationFinished'] = true;
                                                    } else {
                                                        echo "Gabim në insertimin e rezervimit: " . mysqli_error($dbconn);
                                                    }
                                                } else {
                                                    echo "Gabim në marrjen e ID se payment: " . mysqli_error($dbconn);
                                                }


                                                $_SESSION['passengersFinished'] = true;
                                            }
                                        }
                                    }
                                } else {
                                    echo "Sesioni i pasagjereve eshte i zbrazet";
                                }

                                $_SESSION['paymentFinished'] = true;

                                if ($_SESSION['paymentFinished'] === true && $_SESSION['passengersFinished'] === true && $_SESSION['reservationFinished'] === true) {
                                    echo '<div id="succes_modal" class="modal">
                                            <div class="modal-content">
                                                <h2 style=color:#0fd675> Thank you for your reservation! </h2>
                                                <form method="post">
                                                    <input type="submit" value="Finish!" name="go_dashboard" id="goDashboard">
                                                </form>
                                            </div>
                                        </div>
                                        <script>
                                var succes_modal = document.getElementById("succes_modal");
                                var errorSpan = document.getElementsByClassName("close")[0];

                                succes_modal.style.display = "block";

                                errorSpan.onclick = function() {
                                    succes_modal.style.display = "none";
                                }

                                window.onclick = function(event) {
                                    if (event.target == succes_modal) {
                                        succes_modal.style.display = "none";
                                    }

                                }

                                
                            </script>';
                                } else {
                                    echo "Nuk u kryen te gjitha insertimet";
                                }
                            } else {
                                echo mysqli_error($dbconn);
                            }
                        } else {

                            $sql = "INSERT INTO payments (user_id, amount,card_number, cardholder_name, expiration_date, flight_id) 
                            VALUES ('$userID','$price','$cardNumber','$cardholderName','$expirationDate','$flightID')";
                            $result = mysqli_query($dbconn, $sql);

                            if ($result) {
                                if (!empty($_SESSION['passengers'])) {
                                    foreach ($_SESSION['passengers'] as $passenger) {
                                        $fullname = mysqli_real_escape_string($dbconn, $passenger['fullname']);
                                        $gender = mysqli_real_escape_string($dbconn, $passenger['gender']);
                                        $personalNumber = mysqli_real_escape_string($dbconn, $passenger['personalNumber']);
                                        $passportNumber = mysqli_real_escape_string($dbconn, $passenger['passportNumber']);
                                        $dateOfBirth = mysqli_real_escape_string($dbconn, $passenger['dateOfBirth']);

                                        $flight_id = $_SESSION['flightID'];

                                        $sql = "INSERT INTO passengers (user_id, full_name, gender, personal_number, passport_number, date_of_birth) 
                                                VALUES ('$userID','$fullname', '$gender', '$personalNumber', '$passportNumber', '$dateOfBirth')";

                                        $result = mysqli_query($dbconn, $sql);

                                        if ($result) {

                                            $_SESSION['passengersFinished'] = true;

                                            $getPaymentID = "SELECT * FROM payments WHERE card_number = '$cardNumber' AND cardholder_name = '$cardholderName'";
                                            $result = mysqli_query($dbconn, $getPaymentID);

                                            if (mysqli_num_rows($result) > 0) {
                                                $row = mysqli_fetch_assoc($result);

                                                $paymentID = $row['paymentID'];

                                                $getPassengerID = "SELECT * FROM passengers WHERE personal_number = '$personalNumber' AND passport_number = '$passportNumber'";
                                                $result = mysqli_query($dbconn, $getPassengerID);

                                                if (mysqli_num_rows($result) > 0) {
                                                    $row = mysqli_fetch_assoc($result);

                                                    $passengerID = $row['passangerID'];

                                                    $_SESSION['status_reservation'] = 'Pending';
                                                    $staus_reservation = $_SESSION['status_reservation'];

                                                    $sql = "INSERT INTO reservations (user_id, flight_id, departure_date, total_amount, passenger_id, payment_id, status_reservation)
                                                        VALUES ('$userID', '$flight_id', '$departureDate','$price','$passengerID', '$paymentID', '$staus_reservation')";
                                                    $result = mysqli_query($dbconn, $sql);

                                                    if ($result) {
                                                        $_SESSION['reservationFinished'] = true;
                                                    } else {
                                                        echo "Rezervimi nuk u be";
                                                    }
                                                } else {
                                                    echo "Nuk u gjet pasagjeri me kete numer personal";
                                                }
                                            } else {
                                                echo "Nuk u gjet payment ID";
                                            }
                                        } else {
                                            echo "Nuk u shtua pasagjeri";
                                        }
                                    }
                                }

                                $_SESSION['paymentFinished'] = true;

                                if ($_SESSION['paymentFinished'] === true && $_SESSION['passengersFinished'] === true && $_SESSION['reservationFinished'] === true) {
                                    echo '<div id="succes_modal" class="modal">
                                            <div class="modal-content">
                                                <h2 style=color:#0fd675> Thank you for your reservation! </h2>
                                                <form method="post">
                                                    <input type="submit" value="Finish!" name="go_dashboard" id="goDashboard">
                                                </form>
                                            </div>
                                        </div>
                                        <script>
                                var succes_modal = document.getElementById("succes_modal");
                                var errorSpan = document.getElementsByClassName("close")[0];

                                succes_modal.style.display = "block";

                                errorSpan.onclick = function() {
                                    succes_modal.style.display = "none";
                                }

                                window.onclick = function(event) {
                                    if (event.target == succes_modal) {
                                        succes_modal.style.display = "none";
                                    }

                                }

                                

                            </script>';
                                } else {
                                    echo "Nuk u kryen te gjitha insertimet";
                                }
                            } else {
                                echo mysqli_error($dbconn);
                            }
                        }
                    } else {
                        echo "Ju lutem plotesoni te dhenat e karteles: " . mysqli_error($dbconn);
                    }
                }

                ?>
                <form method="post">
                    <label for="">Card Number</label>
                    <input type="number" name="cardNumber" id="cardNumber">
                    <label for="">Cardholder Name</label>
                    <input type="text" name="cardholderName" id="cardholderName">
                    <label for="">Expiration Date (MM/YY)</label>
                    <input type="month" name="expirationDate" id="expirationDate">
                    <input type="submit" value="Finish Payment" name="finishPayment" id="finishPayment">
                    <input type="submit" value="Discard" name="discard" id="discard">
                </form>
            </div>
        </div>
    </div>
</body>

</html>