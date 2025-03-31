<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completed Reservation</title>
    <link rel="stylesheet" href="reservation_List.css">
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
                    <li><a href="reservation-list.php">Reservations</a></li>
                    <li><a href="../profile/profile.php">Profile</a></li>
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


            $dbconn = mysqli_connect("localhost", "root", "", "flyair");
            if (!$dbconn) {
                die("Connection failed: " . mysqli_connect_error());
            }


            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ticket'])) {
                if (isset($_POST['reservation_id'])) {
                    $_SESSION['reservation_id'] = $_POST['reservation_id'];
                    header("Location: ../ticket/ticket.php");
                    exit();
                }
            }

            if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_reservation'])) {
                if(isset($_POST['reservation_id'])) {
                    
                    $reservationID = $_POST['reservation_id'];

                    $sql = "DELETE FROM reservations WHERE id = '$reservationID'";
                    $result = mysqli_query($dbconn, $sql);

                    if($result){
                        echo "<script>
                                alert('Rezervimi u fshi me sukses!')
                            </script>";
                    }
                }
            }

            if (!isset($_SESSION['user_id'])) {
                die("Gabim: Përdoruesi nuk është i identifikuar.");
            }

           



            ?>

            <h1>Completed Reservations</h1>

            <?php

            $user_id = mysqli_real_escape_string($dbconn, $_SESSION['user_id']);


            if (!isset($_SESSION['user_id'])) {
                die("Gabim: Përdoruesi nuk është i identifikuar.");
            }


            $sql = "SELECT r.id, r.departure_date, 
            p.full_name, p.gender, p.personal_number, p.date_of_birth, 
            f.flightID, f.flightNumber, f.origin, f.destination, f.departureTime, f.price
            FROM reservations r
            JOIN passengers p ON r.passenger_id = p.passangerID
            JOIN flights f ON r.flight_id = f.flightID
            WHERE r.user_id = '$user_id'";


            $result = mysqli_query($dbconn, $sql);

            if (mysqli_num_rows($result) > 0) {
                echo '<table>
            <tr>
                <th>Full Name</th>
                <th>Gender</th>
                <th>Personal Number</th>
                <th>Date of Birth</th>
                <th>Flight Number</th>
                <th>Origin</th>
                <th>Destination</th>
                <th>Departure Date</th>
                <th>Ticket</th>
            </tr>';

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                <td>{$row['full_name']}</td>
                <td>{$row['gender']}</td>
                <td>{$row['personal_number']}</td>
                <td>{$row['date_of_birth']}</td>
                <td>{$row['flightNumber']}</td>
                <td>{$row['origin']}</td>
                <td>{$row['destination']}</td>
                <td>{$row['departure_date']}</td>
                <td>
                    <form method='post'>
                        <input type='hidden' name='reservation_id' value='{$row['id']}'>
                        <input type='submit' name='ticket' value='Ticket' id='ticket'>
                        <input type='submit' name='delete_reservation' value='Delete' id='deleteReservation'>
                    </form>
                </td>
              </tr>";
                }

                echo "</table>";
            } else {
                echo "<h2 style='color:red; font-family: Verdana, Geneva, Tahoma, sans-serif;'> Nuk u gjet asnje rezervim!</h2>";
            }

            mysqli_close($dbconn);

            ?>
        </div>

        <div class="footer">
            <ul>
                <li><a href="../dashboard/index.php">Dashboard</a></li>
                <li><a href="reservation-list.php">Reservations</a></li>
                <li><a href="../profile/profile.php">Profile</a></li>
            </ul>
        </div>
        
    </div>
</body>

</html>