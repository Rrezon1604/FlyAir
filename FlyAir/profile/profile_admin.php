<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile_style.css">
</head>

<body>
    <div class="main">

        <div class="navbar" id="navbar">
            <div class="logo">
                <h1>FlyAir</h1>
            </div>
            <div class="nav">
                <ul>
                    <li><a href="../dashboard-admin/dashboard-admin.php">Dashboard</a></li>
                    <li><a href="profile_admin.php">Profile</a></li>
                    <li><a href="../login/index.php">Logout</a></li>
                </ul>
            </div>
        </div>

        <div class="content">
            <div>
                <h2>Your Profile</h2>
            </div>

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

            if (!isset($_SESSION['user_id'])) {
                die("Gabim: Përdoruesi nuk është i identifikuar.");
            }

            $user_id = $_SESSION['user_id'];

            $sql_old = "SELECT profilePicture FROM users WHERE userID = '$user_id'";
            $result_old = mysqli_query($dbconn, $sql_old);
            $row_old = mysqli_fetch_assoc($result_old);

            $oldProfilePicture = $row_old['profilePicture'];

            if (isset($_POST['savechanges'])) {

                $profilePicture = "";

                if (!empty($_FILES['profilepicture']['name'])) {
                    $target_dir = "../uploads/";
                    $file_name = basename($_FILES["profilepicture"]["name"]);
                    $target_file = $target_dir . $file_name;
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                    $allowed_types = ["jpg", "jpeg", "png", "gif"];
                    if (in_array($imageFileType, $allowed_types)) {
                        if (move_uploaded_file($_FILES["profilepicture"]["tmp_name"], $target_file)) {
                            $profilePicture = $file_name;

                            if (!empty($oldProfilePicture) && file_exists("../uploads/" . $oldProfilePicture)) {
                                unlink("../uploads/" . $oldProfilePicture);
                            }
                        } else {
                            $_SESSION['error_message'] = "Ngarkimi i fotos dështoi!";
                            header("Location: " . $_SERVER['PHP_SELF']);
                            exit;
                        }
                    } else {
                        $_SESSION['error_message'] = "Lejohen vetëm skedarë JPG, JPEG, PNG, GIF!";
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit;
                    }
                }

                $fullname = $_POST['fullname'];
                $dateofbirth = $_POST['dateofbirth'];

                $dob = new DateTime($dateofbirth);
                $today = new DateTime();
                $age = $today->diff($dob)->y;

                if ($age > 18) {
                    if (!empty($profilePicture)) {
                        $updatesql = "UPDATE users SET fullName = '$fullname', dateofBirth = '$dateofbirth', profilePicture = '$profilePicture' WHERE userID = '$user_id'";
                    } else {
                        $updatesql = "UPDATE users SET fullName = '$fullname', dateofBirth = '$dateofbirth' WHERE userID = '$user_id'";
                    }


                    $result = mysqli_query($dbconn, $updatesql);

                    if ($result) {
                        echo "<p style='color:#3fdf94; font-family: Verdana, Geneva, Tahoma, sans-serif;'> Details successfully updated! </p>";
                    } else {
                        echo "<p style='color:red; font-family: Verdana, Geneva, Tahoma, sans-serif;'> Failed! </p>";
                    }
                }else{
                    echo "<p style='color:red; font-family: Verdana, Geneva, Tahoma, sans-serif;'> Duhet te jeni 18 vjeq e siper! </p>";
                }
            }


            if (isset($_POST['changepassword'])) {

                $get_currentpassword = "SELECT passwordi FROM users WHERE userID = '$user_id'";
                $getpassword = mysqli_query($dbconn, $get_currentpassword);

                if (mysqli_num_rows($getpassword) > 0) {
                    $row = mysqli_fetch_assoc($getpassword);

                    $_SESSION['currentpassword'] = $row['passwordi'];
                } else {
                    die(mysqli_error($dbconn));
                }

                $passwordi = $_SESSION['currentpassword'];
                $currentpassword = $_POST['currentpassword'];
                $newpassword = $_POST['newpassword'];
                $confrimpassword = $_POST['confirmpassword'];

                if (!empty($_POST['currentpassword']) && !empty($_POST['newpassword']) && !empty($_POST['confirmpassword'])) {
                    if ($currentpassword == $passwordi) {
                        if ($newpassword == $confrimpassword) {
                            $updatepassword = "UPDATE users SET passwordi = '$newpassword' WHERE userID = '$user_id'";
                            $result = mysqli_query($dbconn, $updatepassword);

                            if ($result) {
                                echo "<p style='color:#3fdf94; font-family: Verdana, Geneva, Tahoma, sans-serif;'> Details successfully updated! </p>";
                            } else {
                                echo "<p style='color:red; font-family: Verdana, Geneva, Tahoma, sans-serif;'> Failed! </p>";
                            }
                        } else {
                            echo "<p style='color:red; font-family: Verdana, Geneva, Tahoma, sans-serif;'> Passwordi i ri dhe confirmimi i passwordit duhet te jete i njejte!</p>";
                        }
                    } else {
                        echo "<p style='color:red; font-family: Verdana, Geneva, Tahoma, sans-serif;'> Shkruani sakte passwordin e meparshem! </p>";
                    }
                } else {
                    echo "<p style='color:red; font-family: Verdana, Geneva, Tahoma, sans-serif;'> Plotesoni kushtet per te nderruar fjalekalimin! </p>";
                }
            }



            $sql = "SELECT * FROM users WHERE userID = '$user_id'";
            $result = mysqli_query($dbconn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);

                echo '
                    <div class="profileimg">
                        <img src= "../uploads/' . $row['profilePicture'] . '" alt="Profile Picture">
                    </div>
                    <div class="profileform">
                        <h3>' . $row['email'] . '</h3>
                        <p></p>
                        <form method="post" enctype="multipart/form-data">
                            <div class="changeprofile">
                                <label for="">Full Name:</label>
                                <input type="text" name="fullname" id="fullname" value="' . $row['fullName'] . '">
                                <label for="">Date of Birth</label>
                                <input type="date" name="dateofbirth" id="dateofbirth" value="' . $row['dateofBirth'] . '">
                                <label for="">Profile Picture:</label>
                                <input type="file" name="profilepicture" id="profilepicture" accept="image/*">
                                <input type="submit" value="Save Changes" name="savechanges" id="savechanges">
                            </div>
                            <div class="changepassword">    
                                <h3>Change Password</h3>
                                <label for="">Current Password:</label>
                                <input type="password" name="currentpassword" id="currentpassword">
                                <label for="">New Passoword:</label>
                                <input type="password" name="newpassword" id="newpassword">
                                <label for="">Confirm New Password:</label>
                                <input type="password" name="confirmpassword" id="confirmpassword">
                                <input type="submit" value="Change Password" name="changepassword" id="changepassword">
                            </div>
                        </form>
                    </div>';
            }
            ?>

        </div>

        <div class="footer">
            <div class="navbar" style="margin-left: 40%;">
                <ul>
                    <li><a href="../dashboard-admin/dashboard-admin.php">Dashboard</a></li>
                    <li><a href="../profile/profile_admin.php">Profile</a></li>
                </ul>
            </div>
        </div>

    </div>
</body>

</html>