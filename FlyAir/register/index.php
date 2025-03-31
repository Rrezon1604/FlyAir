<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Fly Air</title>
    <link rel="stylesheet" href="register.css">
</head>

<body>
    <div class="register">
        <div class="registerform">
            <div class="registerh1">
                <h1>Register</h1>
            </div>
            <div class="form">

                <?php

                session_start();

                if (isset($_POST['register'])) {
                    $dbconn = mysqli_connect("localhost", "root", "", "flyAir");
                    if (!$dbconn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    $fullName = mysqli_real_escape_string($dbconn, $_POST['name']);
                    $email = mysqli_real_escape_string($dbconn, $_POST['email']);
                    $gender = mysqli_real_escape_string($dbconn, $_POST['gender']);
                    $dateofBirth = mysqli_real_escape_string($dbconn, $_POST['dateofbirth']);
                    $passwordi = mysqli_real_escape_string($dbconn, $_POST['password']);
                    $deafultRole = "User";

                    $dob = new DateTime($dateofBirth);
                    $now = new DateTime();
                    $age = $now->diff($dob)->y;

                    if ($age < 18) {
                        $_SESSION['error_message'] = "Duhet të jeni 18 vjeç ose më të vjetër për t'u regjistruar!";
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit;
                    }

                    if (!empty($_FILES['profilePicture']['name'])) {
                        $file_name = basename($_FILES["profilePicture"]["name"]);
                        $target_file = $file_name;
                        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                        $allowed_types = ["jpg", "jpeg", "png", "gif"];
                        if (in_array($imageFileType, $allowed_types)) {
                            if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $target_file)) {
                                $profilePicture = $file_name;
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
                    } else {
                        $profilePicture = "userimg.png";
                    }

                    $sql = "SELECT * FROM users WHERE email = '$email'";
                    $res = mysqli_query($dbconn, $sql);

                    if (mysqli_num_rows($res) == 0) {
                        $sql = "INSERT INTO users (fullName, email, gender, dateofBirth, passwordi, profilePicture, user_role) 
                                VALUES ('$fullName', '$email', '$gender', '$dateofBirth', '$passwordi', '$profilePicture', '$deafultRole')";

                        $res = mysqli_query($dbconn, $sql);
                        if ($res) {
                            $_SESSION['success_message'] = "U regjistruat me sukses!";
                            header("Location: " . $_SERVER['PHP_SELF']);
                            exit;
                        } else {
                            $_SESSION['error_message'] = "Gabim gjatë regjistrimit!";
                        }
                    } else {
                        $_SESSION['error_message'] = "Ky email është i regjistruar!";
                    }
                }

                if (isset($_SESSION['success_message'])) {
                    echo "
                    <div id='successModal' class='modal'>
                        <div class='modal-content'>
                            <span class='close'>&times;</span>
                            <p style='color: green;'>".$_SESSION['success_message']."</p>
                        </div>
                    </div>
                    <script>
                        var successModal = document.getElementById('successModal');
                        var successSpan = document.getElementsByClassName('close')[0];
                
                        successModal.style.display = 'block';
                
                        successSpan.onclick = function() {
                            successModal.style.display = 'none';
                        }
                
                        window.onclick = function(event) {
                            if (event.target == successModal) {
                                successModal.style.display = 'none';
                            }
                        }
                    </script>";
                    unset($_SESSION['success_message']);
                } else if (isset($_SESSION['error_message'])) {
                    echo "
                    <div id='errorModal' class='modal'>
                        <div class='modal-content'>
                            <span class='close'>&times;</span>
                            <p style='color: red;'>".$_SESSION['error_message']."</p>
                        </div>
                    </div>
                    <script>
                        var errorModal = document.getElementById('errorModal');
                        var errorSpan = document.getElementsByClassName('close')[0];
                
                        errorModal.style.display = 'block';
                
                        errorSpan.onclick = function() {
                            errorModal.style.display = 'none';
                        }
                
                        window.onclick = function(event) {
                            if (event.target == errorModal) {
                                errorModal.style.display = 'none';
                            }
                        }
                    </script>";
                    unset($_SESSION['error_message']);
                }
                ?>
    
                <form method="post" enctype="multipart/form-data">
                    <label for="">Full Name:</label>
                    <input type="text" name="name" id="name" placeholder="Enter your Full Name:" autocomplete="off" required>
                    <label for="">Email:</label>
                    <input type="email" name="email" id="email" placeholder="Enter your Email:" autocomplete="off" required>
                    <label for="">Gender:</label>
                    <select name="gender" id="gender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                    <label for="">Date of Birth:</label>
                    <input type="date" name="dateofbirth" id="dateofbirth" required>
                    <label for="">Password:</label>
                    <input type="password" name="password" id="password" placeholder="Enter your Password:" autocomplete="off" required>
                    <label for="">Profile Picture:</label>
                    <input type="file" name="profilePicture" id="profilePicture" accept="image/*">
                    <input type="submit" name="register" id="register" value="Register">
                </form>
            </div>
            <div class="text">
                <p>Already have an account? <a href="../login/index.php">Login</a></p>
            </div>
        </div>
    </div>
</body>

</html>