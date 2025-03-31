<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Manage FLights</title>
    <link rel="stylesheet" href="manageFlights_style.css">
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
                <h1>Manage Flights</h1>
            </div>

            <div class="searchForm">

                <form method="POST">
                    <select name="origin" id="origin">
                        <option value="">Select Origin</option>
                        <option value="Tirana International Airport Nene Tereza">Tirana International Airport Nene Tereza</option>
                        <option value="Prishtina International Airport Adem Jashari">Prishtina International Airport Adem Jashari</option>
                        <option value="Skopje International Airport">Skopje International Airport</option>
                        <option value="Vienna International Airport">Vienna International Airport</option>
                        <option value="Frankfurt Airport">Frankfurt Airport</option>
                        <option value="Amsterdam Schiphol Airport">Amsterdam Schiphol Airport</option>
                        <option value="Charles de Gaulle Airport">Charles de Gaulle Airport</option>
                        <option value="Madrid-Barajas Adolfo Suarez Airport">Madrid-Barajas Adolfo Suarez Airport</option>
                        <option value="Zurich Airport">Zurich Airport</option>
                        <option value="Leonardo da Vinci-Fiumicino Airport">Leonardo da Vinci-Fiumicino Airport</option>
                        <option value="Stuttgart Airport">Stuttgart Airport</option>
                        <option value="Düsseldorf Airport">Düsseldorf Airport</option>
                    </select>
                    <select name="destination" id="destination">
                        <option value="">Select Destination</option>
                        <option value="Tirana International Airport Nene Tereza">Tirana International Airport Nene Tereza</option>
                        <option value="Prishtina International Airport Adem Jashari">Prishtina International Airport Adem Jashari</option>
                        <option value="Skopje International Airport">Skopje International Airport</option>
                        <option value="Vienna International Airport">Vienna International Airport</option>
                        <option value="Frankfurt Airport">Frankfurt Airport</option>
                        <option value="Amsterdam Schiphol Airport">Amsterdam Schiphol Airport</option>
                        <option value="Charles de Gaulle Airport">Charles de Gaulle Airport</option>
                        <option value="Madrid-Barajas Adolfo Suarez Airport">Madrid-Barajas Adolfo Suarez Airport</option>
                        <option value="Zurich Airport">Zurich Airport</option>
                        <option value="Leonardo da Vinci-Fiumicino Airport">Leonardo da Vinci-Fiumicino Airport</option>
                        <option value="Stuttgart Airport">Stuttgart Airport</option>
                        <option value="Düsseldorf Airport">Düsseldorf Airport</option>
                    </select>
                    <input type="date" name="searchDate" id="searchDate">
                    <input type="text" name="flightNumber" id="flightNumber" placeholder="Flight Number">
                    <input type="number" name="priceFrom" id="priceFrom" placeholder="Price From">
                    <input type="number" name="priceTo" id="priceTo" placeholder="Price To">
                    <input type="number" name="flightID" id="flightID" placeholder="Flight ID">
                    <input type="submit" name="searchFlight" id="searchFlight" value="Search">
                </form>
            </div>

            <?php

            $dbconn = mysqli_connect("localhost", "root", "", "flyAir");
            if (!$dbconn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $origin = $destination = $searchDate = $flightNumber = $priceFrom = $priceTo = $flightID = "";

            $sql = "SELECT * FROM flights WHERE 1";

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['searchFlight'])) {

                if (!empty($_POST['origin'])) {
                    $origin = mysqli_real_escape_string($dbconn, $_POST['origin']);
                    $sql .= " AND origin = '$origin'";
                }

                if (!empty($_POST['destination'])) {
                    $destination = mysqli_real_escape_string($dbconn, $_POST['destination']);
                    $sql .= " AND destination = '$destination'";
                }

                if (!empty($_POST['searchDate'])) {
                    $searchDate = mysqli_real_escape_string($dbconn, $_POST['searchDate']);
                    $sql .= " AND departureDate = '$searchDate'";
                }

                if (!empty($_POST['flightNumber'])) {
                    $flightNumber = mysqli_real_escape_string($dbconn, $_POST['flightNumber']);
                    $sql .= " AND flightNumber = '$flightNumber'";
                }

                if (!empty($_POST['priceFrom'])) {
                    $priceFrom = mysqli_real_escape_string($dbconn, $_POST['priceFrom']);
                    $sql .= " AND price >= '$priceFrom'";
                }

                if (!empty($_POST['priceTo'])) {
                    $priceTo = mysqli_real_escape_string($dbconn, $_POST['priceTo']);
                    $sql .= " AND price <= '$priceTo'";
                }

                if (!empty($_POST['flightID'])) {
                    $flightID = mysqli_real_escape_string($dbconn, $_POST['flightID']);
                    $sql .= " AND flightID = '$flightID'";
                }
            }

            $result = mysqli_query($dbconn, $sql);

            if (mysqli_num_rows($result) > 0) {
                echo '<div class="manageFlightsTable">
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Origin</th>
                            <th>Destination</th>
                            <th>Departure Date</th>
                            <th>Departure Time</th>
                            <th>Flight Number</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>';

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>
                        <form method="post">
                            <td>' . $row['flightID'] . '</td>
                            <td>' . $row['origin'] . '</td>
                            <td>' . $row['destination'] . '</td>
                            <td><input type="date" name="departureDate" value="' . $row['departureDate'] . '"></td>
                            <td><input type="time" name="departureTime" value="' . $row['departureTime'] . '"></td>
                            <td><input type="text" name="flightNumber" value="' . $row['flightNumber'] . '"></td>
                            <td><input type="number" name="priceFlight" value="' . $row['price'] . '" step="0.01"></td>
                            <td>
                                <div class="inputsDiv">
                                    <input type="hidden" name="flightID" value="' . $row['flightID'] . '">
                                    <input type="submit" name="updateFlight" value="Update">
                                    <input type="submit" name="deleteFlight" value="Delete" id="deleteFlight">
                                </div>
                            </td>
                        </form>
                    </tr>';
                }

                echo '</table></div>';
            } else {
                echo "<div style='width:100%; text-align:center; color:red;'><h1>No flights found.</h1></div>";
            }

            if (isset($_POST['addFlight'])) {

                $origin = $_POST['origin'];
                $destination = $_POST['destination'];
                $flightDate = $_POST['flightDate'];
                $flightTime = $_POST['flightTime'];
                $flightNumber = $_POST['flightNumber'];
                $price = $_POST['price'];

                if ($origin === $destination) {
                    die("Origin and destination cannot be the same");
                }

                $query_origin = "SELECT airportsID FROM airports WHERE name = '$origin'";
                $result_origin = mysqli_query($dbconn, $query_origin);
                if (!$result_origin || mysqli_num_rows($result_origin) == 0) {
                    die("Origin airport not found!");
                }
                $origin_id = mysqli_fetch_assoc($result_origin)['airportsID'];


                $query_destination = "SELECT airportsID FROM airports WHERE name = '$destination'";
                $result_destination = mysqli_query($dbconn, $query_destination);
                if (!$result_destination || mysqli_num_rows($result_destination) == 0) {
                    die("Destination airport not found!");
                }
                $destination_id = mysqli_fetch_assoc($result_destination)['airportsID'];


                if (empty($flightNumber)) {
                    do {
                        $flightNumber = strtoupper($origin[0]) . strtoupper($destination[0]) . rand(1000000000, 9999999999);
                        $check_flight_query = "SELECT flightNumber FROM flights WHERE flightNumber = '$flightNumber'";
                        $check_flight_result = mysqli_query($dbconn, $check_flight_query);
                    } while (mysqli_num_rows($check_flight_result) > 0);
                } else {
                    $check_flight_query = "SELECT flightNumber FROM flights WHERE flightNumber = '$flightNumber'";
                    $check_flight_result = mysqli_query($dbconn, $check_flight_query);
                    if (mysqli_num_rows($check_flight_result) > 0) {
                        die("Error: Flight number already exists!");
                    }
                }

                $insert_query = "INSERT INTO flights (flightNumber, departure_airport_id, arrival_airport_id, origin, destination, departureTime, departureDate, price)
                VALUES ('$flightNumber','$origin_id','$destination_id','$origin','$destination','$flightTime','$flightDate','$price')";
                $result = mysqli_query($dbconn, $insert_query);

                if ($result) {
                    echo "<script>alert('Flight added successfully!'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
                    exit();
                } else {
                    echo "<script>alert('Error: " . mysqli_error($dbconn) . "');</script>";
                }
            }

            if (isset($_POST['updateFlight'])) {
                $flightID = $_POST['flightID'];
                $departureDate = $_POST['departureDate'];
                $departureTime = $_POST['departureTime'];
                $flightNumber = $_POST['flightNumber'];
                $price = $_POST['priceFlight'];

                $update_query = "UPDATE flights 
                     SET departureDate='$departureDate', 
                         departureTime='$departureTime', 
                         flightNumber='$flightNumber', 
                         price='$price' 
                     WHERE flightID='$flightID'";

                if (mysqli_query($dbconn, $update_query)) {
                    echo "<script>alert('Flight updated successfully!'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
                    exit();
                } else {
                    echo "<script>alert('Error updating flight: " . mysqli_error($dbconn) . "');</script>";
                }
            }

            if (isset($_POST['deleteFlight'])) {
                $flightID = $_POST['flightID'];

                $deleteQuery = "DELETE FROM flights WHERE flightID='$flightID'";

                if (mysqli_query($dbconn, $deleteQuery)) {
                    echo "<script>alert('Flight deleted successfully!'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
                    exit();
                } else {
                    echo "<script>alert('Error deleting flight: " . mysqli_error($dbconn) . "');</script>";
                }
            }



            mysqli_close($dbconn);
            ?>




            <div class="addh2">
                <h2>Add a New Flight</h2>
            </div>
            <div class="searchForm" id="addNewFlight">
                <form method="post" action="">
                    <select name="origin" id="origin">
                        <option value="">Select Origin</option>
                        <option value="Tirana International Airport Nene Tereza">Tirana International Airport Nene Tereza</option>
                        <option value="Prishtina International Airport Adem Jashari">Prishtina International Airport Adem Jashari</option>
                        <option value="Skopje International Airport">Skopje International Airport</option>
                        <option value="Vienna International Airport">Vienna International Airport</option>
                        <option value="Frankfurt Airport">Frankfurt Airport</option>
                        <option value="Amsterdam Schiphol Airport">Amsterdam Schiphol Airport</option>
                        <option value="Charles de Gaulle Airport">Charles de Gaulle Airport</option>
                        <option value="Madrid-Barajas Adolfo Suarez Airport">Madrid-Barajas Adolfo Suarez Airport</option>
                        <option value="Zurich Airport">Zurich Airport</option>
                        <option value="Leonardo da Vinci-Fiumicino Airport">Leonardo da Vinci-Fiumicino Airport</option>
                        <option value="Stuttgart Airport">Stuttgart Airport</option>
                        <option value="Düsseldorf Airport">Düsseldorf Airport</option>
                    </select>
                    <select name="destination" id="destination">
                        <option value="">Select Destination</option>
                        <option value="Tirana International Airport Nene Tereza">Tirana International Airport Nene Tereza</option>
                        <option value="Prishtina International Airport Adem Jashari">Prishtina International Airport Adem Jashari</option>
                        <option value="Skopje International Airport">Skopje International Airport</option>
                        <option value="Vienna International Airport">Vienna International Airport</option>
                        <option value="Frankfurt Airport">Frankfurt Airport</option>
                        <option value="Amsterdam Schiphol Airport">Amsterdam Schiphol Airport</option>
                        <option value="Charles de Gaulle Airport">Charles de Gaulle Airport</option>
                        <option value="Madrid-Barajas Adolfo Suarez Airport">Madrid-Barajas Adolfo Suarez Airport</option>
                        <option value="Zurich Airport">Zurich Airport</option>
                        <option value="Leonardo da Vinci-Fiumicino Airport">Leonardo da Vinci-Fiumicino Airport</option>
                        <option value="Stuttgart Airport">Stuttgart Airport</option>
                        <option value="Düsseldorf Airport">Düsseldorf Airport</option>
                    </select>
                    <input type="date" name="flightDate" id="flightDate">
                    <input type="time" name="flightTime" id="flightTime">
                    <input type="text" name="flightNumber" id="flightNumber" placeholder="Flight Number">
                    <input type="number" name="price" id="price" placeholder="Price">
                    <input type="submit" name="addFlight" id="addFlight" value="Add Flight">
                </form>
            </div>
        </div>

    </div>
</body>

</html>