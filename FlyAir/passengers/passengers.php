<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passengers</title>
    <link rel="stylesheet" href="passangers-style.css">
</head>

<body>
    <div class="main">
        <div class="navbar" id="navbar">
            <div class="logo">
                <h1>FlyAir</h1>
            </div>
            <div class="nav">
                <ul>
                    <li><a href="">Dashboard</a></li>
                    <li><a href="">Reservations</a></li>
                    <li><a href="">Profile</a></li>
                    <li><a href="">Logout</a></li>
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

            if (isset($_POST['discard'])) {
                header("Location: ../reservation/reservation.php");
                exit();
            }

            if(isset($_POST['continue'])){
                if(!empty($_SESSION['passengers'])){
                    header("Location: ../payment/payment.php");
                    exit();
                }else{
                    echo '<script>
                            alert("Ju lutem shto pasagjeret!")
                        </script>';
                }
            }

            if (isset($_POST['confirmPassangers'])) {
                if (
                    !empty($_POST['fullname']) && !empty($_POST['gender']) &&
                    !empty($_POST['personalNumber']) && !empty($_POST['passportNumber']) &&
                    !empty($_POST['dateOfBirth'])
                ) {

                    $newPassenger = [
                        'fullname' => $_POST['fullname'],
                        'gender' => $_POST['gender'],
                        'personalNumber' => $_POST['personalNumber'],
                        'passportNumber' => $_POST['passportNumber'],
                        'dateOfBirth' => $_POST['dateOfBirth']
                    ];

                    if (!isset($_SESSION['passengers'])) {
                        $_SESSION['passengers'] = [];
                    }

                    $_SESSION['passengers'][] = $newPassenger;

                    $_SESSION['message'] = "Passenger added successfully!";

                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                }else{
                    echo '<script>
                            alert("Ju lutem plotesoni te dhenat!")
                        </script>';
                }
            }

            // Shfaq mesazhin nëse ekziston
            if (isset($_SESSION['message'])) {
                echo '<div class="confirmPassangers"><p>' . $_SESSION['message'] . '</p></div>';
                unset($_SESSION['message']); // Fshije mesazhin pasi të jetë shfaqur
            }


            ?>
            <h1>Passenger Information</h1>
            <div class="flightDetails">
                <h2>Flight Details</h2>
                <p><span>Flight Number:</span> <?php echo $_SESSION['flightNumber']; ?></p>
                <p><span>From:</span> <?php echo $_SESSION['origin']; ?></p>
                <p><span>To:</span> <?php echo $_SESSION['destination']; ?></p>
                <p><span>Departure:</span> <?php echo $_SESSION['departure_date']; ?></p>
                <p><span>Return:</span></p>
                <p><span>Total Amount:</span> $<?php echo $_SESSION['price']; ?></p>
                <p><span>Status:</span> <?php echo $_SESSION['flightStatus']; ?></p>
            </div>

            <?php

            if ($_SESSION['returnFlight'] === true) {
                echo '<div class="flightDetails">
                        <h2>Return Flight Details</h2>
                        <p><span>Flight Number: </span>' . $_SESSION['returnFlightNumber'] . '</p>
                        <p><span>From: </span>' . $_SESSION['returnOrigin'] . '</p>
                        <p><span>To: </span>' . $_SESSION['returnDestination'] . '</p>
                        <p><span>Departure: </span>' . $_SESSION['returnDepartureDate'] . '</p>
                        <p><span>Return:</span></p>
                        <p><span>Total Amount: </span>$' . $_SESSION['returnPrice'] . '</p>
                        <p><span>Status: </span>' . $_SESSION['flightStatus'] . '</p>
                    </div>';
            }

            ?>


            <div class="flightPassengers">
                <form method="post">
                    <h2>Flight Passengers</h2>
                    <h2>Passenger</h2>
                    <div class="passenger">
                        <label for="">Full Name:</label>
                        <input type="text" name="fullname" id="fullname">
                        <label for="">Gender:</label>
                        <select name="gender" id="gender">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                        <label for="">Personal Number:</label>
                        <input type="number" name="personalNumber" id="personalNumber">
                        <label for="">Passport Number:</label>
                        <input type="text" name="passportNumber" id="passportNumber">
                        <label for="">Date of Birth:</label>
                        <input type="date" name="dateOfBirth" id="dateOfBirth">
                    </div>
                    <div class="buttons">
                        <form method="post">
                            <input type="submit" value="Add Passenger" name="confirmPassangers" id="confirmPassengers">
                        </form>
                    </div>
                </form>
            </div>
        </div>
        <?php

        if (!empty($_SESSION['passengers'])) {
            echo '<h2 class="confirmedpassengers">Confirmed Passengers</h2>';
            echo '<table border="1">
                    <tr>
                        <th></th>
                        <th>Full Name</th>
                        <th>Gender</th>
                        <th>Personal Number</th>
                        <th>Passport Number</th>
                        <th>Date of Birth</th>
                    </tr>';

            foreach ($_SESSION['passengers'] as $index => $passenger) {
                echo "<tr>
                        <td>" . ($index + 1) . "</td>
                        <td>{$passenger['fullname']}</td>
                        <td>{$passenger['gender']}</td>
                        <td>{$passenger['personalNumber']}</td>
                        <td>{$passenger['passportNumber']}</td>
                        <td>{$passenger['dateOfBirth']}</td>
                      </tr>";
            }

            echo '</table>';
        }

        ?>
        <div class="confirmOrDiscard">
            <form method="post">
                <input type="submit" value="Discard" name="discard" id="discard">
                <input type="submit" value="Continue" name="continue" id="continue">
            </form>
        </div>
    </div>

    <script src="passangers-script.js"></script>
</body>

</html>