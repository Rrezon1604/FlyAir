<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payments</title>
    <link rel="stylesheet" href="style.css">
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
                <h1>Manage Payments</h1>
            </div>

            <div class="paymentsTable">
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




                if (isset($_POST['deletepayment'])) {
                    $paymentID = $_POST['paymentID'];

                    $delete_sql = "DELETE FROM payments WHERE paymentID = '$paymentID'";
                    $result = mysqli_query($dbconn, $delete_sql);

                    if ($result) {
                        $_SESSION['success_message'] = "Deleted successfully";

                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    } else {
                        echo '<div style="width: 100%; padding: 10px; background-color: #d1fbe5;
                                      font-size: 20px; color: red; border-radius: 10px;
                                      margin-top: 15px; margin-bottom: 15px; text-align:center;">
                            <p>Payment cannot be deleted because the reservation has been made.</p>
                          </div>';
                    }
                }




                if (isset($_SESSION['success_message'])) {
                    echo '<div style="width: 100%; padding: 10px; background-color: #d1fbe5;
                                      font-size: 20px; color: green; border-radius: 10px;
                                      margin-top: 15px; margin-bottom: 15px; text-align:center;">
                            <p>' . $_SESSION['success_message'] . '</p>
                          </div>';
                    unset($_SESSION['success_message']);
                }




                $sql = "SELECT * FROM payments";
                $result = mysqli_query($dbconn, $sql);


                if (mysqli_num_rows($result) > 0) {
                    echo '<table>
                            <tr>
                                <th>ID</th>
                                <th>User ID</th>
                                <th>Amount</th>
                                <th>Payment Date</th>
                                <th>Card Number</th>
                                <th>Cardholder Name</th>
                                <th>Expiration Date</th>
                                <th>Flight ID</th>
                                <th>Action</th>
                            </tr>';
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>
                                <form method="post" action="">
                                    <td>' . $row['paymentID'] . '</td>
                                    <td>' . $row['user_id'] . '</td>
                                    <td>' . $row['amount'] . '</td>
                                    <td>' . $row['payment_date'] . '</td>
                                    <td>' . $row['card_number'] . '</td>
                                    <td>' . $row['cardholder_name'] . '</td>
                                    <td>' . $row['expiration_date'] . '</td>
                                    <td>' . $row['flight_id'] . '</td>
                                    <td>
                                        <input type="hidden" name="paymentID" value="' . $row['paymentID'] . '">
                                        <input type="submit" value="Delete" name="deletepayment" id="deletepayment">
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