<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="manage_users.css">
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
                <h1>Manage Users</h1>
            </div>

            <div class="manageUsersTable">
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
                    $userid = $_POST['userid'];

                    $sql = "DELETE FROM users WHERE userID = '$userid'";
                    $result = mysqli_query($dbconn, $sql);

                    if ($result) {
                        $_SESSION['success_message'] = "DELETED SUCCESSFULLY";

                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    } else {
                        $_SESSION['error_message'] = "The user cannot be deleted because there are reservations made by this user.";
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    }
                }

                if (isset($_POST['change'])) {
                    $userid = $_POST['userid'];
                    $role = $_POST['role'];

                    $sql = "UPDATE users SET user_role = '$role' 
                                WHERE userID = '$userid'";
                    $result = mysqli_query($dbconn, $sql);

                    if ($result) {
                        $_SESSION['success_message'] = "SUCCESS";

                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    } else {
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
                }else if(isset($_SESSION['error_message'])){
                    echo '<div style="width: 100%; padding: 10px; background-color: #d1fbe5;
                                      font-size: 20px; color: red; border-radius: 10px;
                                      margin-top: 15px; margin-bottom: 15px; text-align:center;">
                            <p>'.$_SESSION['error_message'].'</p>
                          </div>';
                    unset($_SESSION['error_message']);
                }




                $sql = "SELECT * FROM users";
                $result = mysqli_query($dbconn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    echo '<table>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Date of Birth</th>
                                <th>Profile Image</th>
                                <th>Role</th>
                                <th>Change Role</th>
                                <th>Delete</th>
                            </tr>';
                    while ($row = mysqli_fetch_assoc($result)) {
                        $role = $row['user_role'];
                        $option1 = $role;
                        $option2 = ($option1 == "User") ? "Admin" : "User";

                        echo '<tr>
                                <form method="post" action="">
                                    <td>' . $row['userID'] . '</td>
                                    <td>' . $row['fullName'] . '</td>
                                    <td>' . $row['email'] . '</td>
                                    <td>' . $row['gender'] . '</td>
                                    <td>' . $row['dateofBirth'] . '</td>
                                    <td> <img src= "../uploads/' . $row['profilePicture'] . '" alt="Profile Picture"> </td>
                                    <td>' . $row['user_role'] . '</td>
                                    <td> <select name="role">
                                            <option value="' . $option1 . '">' . $option1 . '</option>
                                            <option value"' . $option2 . '">' . $option2 . '</option>
                                        </select>
                                        <input type="submit" name="change" id="change" value="Change">
                                    </td>
                                    <td> 
                                        <input type="hidden" name="userid" value="' . $row['userID'] . '">
                                        <input type="submit" name="delete" id="delete" value="Delete"> 
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