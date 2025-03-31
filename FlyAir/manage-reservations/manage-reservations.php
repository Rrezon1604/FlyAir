<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reservations</title>
    <link rel="stylesheet" href="manageReservations.css">
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
                <h1>Manage Reservations</h1>
            </div>

            <div class="reservationsTable">
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

                


                if(isset($_POST['deleteReservation'])){
                    $reservationID = $_POST['reservationID'];

                    $delete_sql = "DELETE FROM reservations WHERE id = '$reservationID'";
                    $result = mysqli_query($dbconn, $delete_sql);

                    if($result){
                        $_SESSION['success_message'] = "Deleted successfully";

                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    }else{
                        echo mysqli_error($dbconn);
                    }
                }

                if (isset($_POST['updateReservation'])) {
                    $statusi = $_POST['status'];
                    $reservationID = $_POST['reservationID'];

                    $update_sql = "UPDATE reservations 
                        SET status_reservation = '$statusi'
                        WHERE id = '$reservationID'";
                    $result = mysqli_query($dbconn, $update_sql);

                    if ($result) {
                        $_SESSION['success_message'] = "Updated successfully";

                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    }else{
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




                $sql = "SELECT * FROM reservations";
                $result = mysqli_query($dbconn, $sql);


                if (mysqli_num_rows($result) > 0) {
                    echo '<table>
                            <tr>
                                <th>ID</th>
                                <th>User ID</th>
                                <th>Flight ID</th>
                                <th>Departure Date</th>
                                <th>Return Date</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>';
                    while ($row = mysqli_fetch_assoc($result)) {
                        $status = $row['status_reservation'];
                        $option1 = $status;
                        $option2 = ($status == "Pending") ? "Confirmed" : "Pending";
                        echo '<tr>
                                <form method="post" action="">
                                    <td>' . $row['id'] . '</td>
                                    <td>' . $row['user_id'] . '</td>
                                    <td>' . $row['flight_id'] . '</td>
                                    <td>' . $row['departure_date'] . '</td>
                                    <td>N/A</td>
                                    <td>' . $row['total_amount'] . '</td>
                                    <td> 
                                        <select name="status"> 
                                            <option value="' . $option1 . '">' . $option1 . '</option>
                                            <option value="' . $option2 . '">' . $option2 . '</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="hidden" name="reservationID" value="' . $row['id'] . '">
                                        <input type="submit" value="Update" name="updateReservation" id="updateReservation">
                                        <input type="submit" value="Delete" name="deleteReservation" id="deleteReservation">
                                    </td>
                                </form>';
                    }
                    echo "</tr>
                        </table>";
                }

                ?>
            </div>
        </div>
    </div>
</body>

</html>