<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Fly Air</title>
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <div class="login">
        <div class="loginform">
            <div class="loginh1">
                <h1>Login</h1>
            </div>
            <div class="form">
                <?php
                session_start();

                if (isset($_POST['login'])) {
                    $dbconn = mysqli_connect("localhost", "root", "", "flyAir");
                    if (!$dbconn) {
                        die("Connection failed: " . mysqli_connect_error());
                    };

                    $email = $_POST['email'];
                    $password = $_POST['password'];

                    $_SESSION['email'] = $_POST['email'];
                    $_SESSION['password'] = $_POST['password'];

                    $sql = "SELECT * FROM users WHERE email = '$email' AND passwordi = '$password'";
                    $res = mysqli_query($dbconn, $sql);

                    if (mysqli_num_rows($res) == 0) {
                        $_SESSION['error_message'] = "Keni gabuar email-in ose password-in!";
                    } else {
                        $row = mysqli_fetch_assoc($res);

                        $_SESSION['user_id'] = $row['userID'];
                        $_SESSION['user_email'] = $row['email'];
                        $_SESSION['role'] = $row['user_role'];

                        if ($row['user_role'] == 'Admin') {
                            header("Location: ../dashboard-admin/dashboard-admin.php");
                        } else {
                            header("Location: ../dashboard/index.php");
                        }
                        exit();
                    }

                    if (isset($_SESSION['error_message'])) {
                        echo "
                            <div id='errorModal' class='modal'>
                                <div class='modal-content'>
                                    <span class='close'>&times;</span>
                                    <p style='color: red;'>" . $_SESSION['error_message'] . "</p>
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
                }
                ?>

                <form method="POST">
                    <label for="">Email:</label>
                    <input type="email" name="email" id="email" placeholder="Your Email:" autocomplete="off">
                    <label for="">Password:</label>
                    <input type="password" name="password" id="password" placeholder="Your Password:">
                    <input type="submit" name="login" id="login" value="Login">
                </form>
            </div>
            <div class="text">
                <p>Don't have an account? <a href="../register/index.php">Register</a></p>
            </div>
        </div>
    </div>
</body>

</html>