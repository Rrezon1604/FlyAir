<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Ticket</title>
    <link rel="stylesheet" href="style.css">

    <style>

        @media print {
            button {
                display: none;
                
            }
        }

        
    </style>
</head>

<body>
    <div class="main">
        <div>
            <h1>Flight Ticket</h1>
        </div>
        <div id="ticket">
            <?php

            session_start();

            if (!isset($_SESSION['user_id'])) {
                header("Location: ../login/index.php");
                exit();
            }


            $dbconn = mysqli_connect("localhost", "root", "", "flyair");
            if (!$dbconn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            if (!isset($_SESSION['reservation_id'])) {
                die("Gabim: Nuk ekziston ky rezervim.");
            }

            $reservation_id = $_SESSION['reservation_id'];

            $sql = "SELECT r.id, r.departure_date, 
            p.full_name, p.gender, p.personal_number, p.date_of_birth, 
            f.flightID, f.origin, f.flightNumber, f.destination, f.departureTime, f.price
            FROM reservations r
            JOIN passengers p ON r.passenger_id = p.passangerID
            JOIN flights f ON r.flight_id = f.flightID
            WHERE r.id = '$reservation_id'";

            $result = mysqli_query($dbconn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                echo "
                    <p><span>Full Name: </span>" . $row['full_name'] . "</p>
                    <p><span>Gender: </span>" . $row['gender'] . "</p>
                    <p><span>Personal Number: </span>" . $row['personal_number'] . "</p>
                    <p><span>Date of Birth: </span>" . $row['date_of_birth'] . "</p>
                    <p><span>Flight Number: </span>" . $row['flightNumber'] . "</p>
                    <p><span>Origin: </span>" . $row['origin'] . "</p>
                    <p><span>Destination: </span>" . $row['destination'] . "</p>
                    <p><span>Departure Date: </span>" . $row['departure_date'] . "</p>";
            }

            ?>
            
        </div>
        <div>
            <button type="button" onclick="window.print()">Print Ticket</button>
        </div>
    </div>

</body>

</html>