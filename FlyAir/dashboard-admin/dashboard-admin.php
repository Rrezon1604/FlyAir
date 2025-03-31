<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="main">

        <div class="logo">
            <h1>FlyAir</h1>
        </div>

        <div class="admindashboard">
            <h1>Admin Dashboard</h1>
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

            ?>
            <div>
                <h2>Total Reservation</h2>
                <h1><?php

                    $query = "SELECT COUNT(*) AS totalReservations FROM reservations";
                    $result = mysqli_query($dbconn, $query);

                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        $totalReservations = $row['totalReservations'];
                        echo $totalReservations;
                    } else {
                        echo "Gabim në marrjen e numrit të fluturimeve: " . mysqli_error($dbconn);
                    }

                    ?></h1>
                <p><a href="../manage-reservations/manage-reservations.php">Manage Reservations</a></p>
            </div>
            <div>
                <h2>Total Flights</h2>
                <h1><?php
                    $query = "SELECT COUNT(*) AS totalFlights FROM flights";
                    $result = mysqli_query($dbconn, $query);

                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        $totalFlights = $row['totalFlights'];
                        echo $totalFlights;
                    } else {
                        echo "Gabim në marrjen e numrit të fluturimeve: " . mysqli_error($dbconn);
                    }
                    ?></h1>
                <p><a href="../manage-flights/manage-flights.php">Manage Flights</a></p>
            </div>
            <div>
                <h2>Total Users</h2>
                <h1><?php
                    $query = "SELECT COUNT(*) AS totalUsers FROM users";
                    $result = mysqli_query($dbconn, $query);

                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        $totalUsers = $row['totalUsers'];
                        echo $totalUsers;
                    } else {
                        echo "Gabim në marrjen e numrit të fluturimeve: " . mysqli_error($dbconn);
                    }
                    ?></h1>
                <p><a href="../manage-users/manage-users.php">Manage Users</a></p>
            </div>
            <div>
                <h2>Total Aiports</h2>
                <h1><?php

                    $query = "SELECT COUNT(*) AS totalAirports FROM airports";
                    $result = mysqli_query($dbconn, $query);

                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        $totalAirports = $row['totalAirports'];
                        echo $totalAirports;
                    } else {
                        echo "Gabim në marrjen e numrit të fluturimeve: " . mysqli_error($dbconn);
                    }

                    ?></h1>
                <p><a href="../manage-airports/manage-airports.php">Manage Aiports</a></p>
            </div>
            <div>
                <h2>Total Passangers</h2>
                <h1><?php

                    $query = "SELECT COUNT(*) AS totalPassangers FROM passengers";
                    $result = mysqli_query($dbconn, $query);

                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        $totalPassangers = $row['totalPassangers'];
                        echo $totalPassangers;
                    } else {
                        echo "Gabim në marrjen e numrit të fluturimeve: " . mysqli_error($dbconn);
                    }

                    ?></h1>
                <p><a href="../manage-passengers/manage-passengers.php">Manage Passangers</a></p>
            </div>
            <div>
                <h2>Total Payments</h2>
                <h1><?php

                    $query = "SELECT COUNT(*) AS totalPayments FROM payments";
                    $result = mysqli_query($dbconn, $query);

                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        $totalPayments = $row['totalPayments'];
                        echo $totalPayments;
                    } else {
                        echo "Gabim në marrjen e numrit të pagesave: " . mysqli_error($dbconn);
                    }

                    ?></h1>
                <p><a href="../manage-payments/index.php">Manage Payments</a></p>
            </div>
        </div>

        <footer>
            <ul>
                <li><a href="../profile/profile_admin.php">Profile</a></li>
                <li><a href="../login/index.php">Logout</a></li>
            </ul>
        </footer>

    </div>
</body>

</html>