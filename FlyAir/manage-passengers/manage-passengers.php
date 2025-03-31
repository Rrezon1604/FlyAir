<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Passengers</title>
    <link rel="stylesheet" href="managepassengers.css">
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
                <h1>Manage Passengers</h1>
            </div>

            <div class="info" style="display: none;">
                <p>Passenger updated successfully.</p>
            </div>

            <div class="form">
                <form method="post" action="">
                    <input type="text" name="insertFullName" id="fullName" placeholder="Full Name">
                    <select name="insertGender" id="gender">
                        <option value="">Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                    <input type="number" name="insertPersonalNumber" id="personalNumber" placeholder="Personal Number">
                    <input type="date" name="insertDate" id="date">
                    <input type="text" name="insertPassportNumber" id="passportNumber" placeholder="Passport Number">
                    <input type="submit" name="filter" id="filter" value="Filter">
                </form>
            </div>

            <div class="managePassengersTable">
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

                if (isset($_POST['delete'])) {
                    $passangerID = $_POST['passangerID'];

                    $sql = "DELETE FROM passengers WHERE passangerID='$passangerID'";
                    $result = mysqli_query($dbconn, $sql);

                    if ($result) {
                        $_SESSION['success_message'] = "Passanger Deleted Successfully";

                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    } else {
                        $_SESSION['error_message'] = "The passenger cannot be hidden because he has booked flights.";

                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    }
                }


                if (isset($_POST['update'])) {
                    $passangerID = $_POST['passangerID'];
                    $fullName = $_POST['fullname'];
                    $gender = $_POST['gender'];
                    $personalNumber = $_POST['personalnumber'];
                    $passportNumber = $_POST['passportnumber'];
                    $dateofbirth = $_POST['dateofbirth'];


                    $sql = "UPDATE passengers SET full_name='$fullName',
                                                gender='$gender',
                                                personal_number='$personalNumber',
                                                passport_number='$passportNumber',
                                                date_of_birth='$dateofbirth'
                            WHERE passangerID='$passangerID'";
                    $result = mysqli_query($dbconn, $sql);

                    if ($result) {

                        $_SESSION['success_message'] = "Passanger Updated Successfully";

                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    } else {
                        echo mysqli_error($dbconn);
                    }
                }


                if (isset($_SESSION['success_message'])) {
                    echo '<div style="width: 100%; padding: 10px; background-color: #d1fbe5;
                                  font-size: 20px; color: green; border-radius: 10px;
                                  margin-bottom: 25px; text-align:center;">
                        <p>' . $_SESSION['success_message'] . '</p>
                      </div>';
                    unset($_SESSION['success_message']);
                } else if (isset($_SESSION['error_message'])) {
                    echo '<div style="width: 100%; padding: 10px; background-color: #d1fbe5;
                                    font-size: 20px; color: red; border-radius: 10px;
                                    margin-bottom: 25px; text-align:center;">
                                    <p>' . $_SESSION['error_message'] . '</p>
                                    </div>';
                    unset($_SESSION['error_message']);
                }


                $sql = "SELECT * FROM passengers WHERE 1";

                if(isset($_POST['filter'])){

                    if(!empty($_POST['insertFullName'])){
                        $insertFullName = $_POST['insertFullName'];
                        $sql .= " AND full_name='$insertFullName'";
                    }

                    if(!empty($_POST['insertGender'])){
                        $insertGender = $_POST['insertGender'];
                        $sql .= " AND gender='$insertGender'";
                    }

                    if(!empty($_POST['insertPersonalNumber'])){
                        $insertPersonalNumber = $_POST['insertPersonalNumber'];
                        $sql .= " AND personal_number='$insertPersonalNumber'";
                    }

                    if(!empty($_POST['insertDate'])){
                        $inserDate = $_POST['insertDate'];
                        $sql .= " AND date_of_birth='$inserDate'";
                    }

                    if(!empty($_POST['insertPassportNumber'])){
                        $insertPassportNumber = $_POST['insertPassportNumber'];
                        $sql .= " AND passport_number='$insertPassportNumber'";
                    }
                }

                $result = mysqli_query($dbconn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    echo '<table>
                            <tr>
                                <th>Passanger ID</th>
                                <th>User ID</th>
                                <th>Full Name</th>
                                <th>Gender</th>
                                <th>Personal Number</th>
                                <th>Passport Number</th>
                                <th>Date of Birth</th>
                                <th>Actions</th>
                            </tr>';
                    while ($row = mysqli_fetch_assoc($result)) {
                        $gender = $row['gender'];
                        $option1 = $gender;
                        $option2 = ($option1 == "Male") ? "Female" : "Male";
                        echo '<tr>
                                <form method="post" action="">
                                    <td>' . $row['passangerID'] . '</td>
                                    <td>' . $row['user_id'] . '</td>
                                    <td>
                                        <input type="text" name="fullname" value="' . $row['full_name'] . '">
                                    <td>
                                        <select name="gender">
                                            <option value"' . $option1 . '">' . $option1 . '</option>
                                            <option value"' . $option2 . '">' . $option2 . '</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="personalnumber" value="' . $row['personal_number'] . '">
                                    </td>
                                    <td>
                                        <input type="text" name="passportnumber" value="' . $row['passport_number'] . '">
                                    </td>
                                    <td>
                                        <input type="date" name="dateofbirth" value="' . $row['date_of_birth'] . '">
                                    </td>
                                    <td class="action">
                                        <input type="hidden" name="passangerID" value="' . $row['passangerID'] . '">
                                        <input type="submit" name="update" id="update" value="Update">
                                        <input type="submit" name="delete" id="delete" value="Delete">                                       
                                    </td>
                                </form>';
                    }

                    echo '</tr>
                    </table>';
                }else{
                    echo '<div style="width: 100%; padding: 10px; font-size: 20px; color: red; border-radius: 10px;
                    margin-bottom: 25px; text-align:center;">
                    <p>The passenger was not found.</p>
                    </div>';
                }

                ?>

            </div>
        </div>

    </div>
</body>

</html>