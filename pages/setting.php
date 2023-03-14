<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="../images/icon.png"/>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="../api/https.js"></script>

    <title>Setting</title>
</head>
<body>
    <?php
    if (empty($_SESSION['username'])) {
        echo "<script>window.location.href = '../login.html'</script>";
    }
    else if (isset($_SESSION['username'])) {
        echo '<script> var ID_USER = "'.$_SESSION['id'].'";</script>';
        echo '<script> var username = "'.$_SESSION['username'].'";</script>';
        echo '<script> var FULLNAME = "'.$_SESSION['fullname'].'";</script>';
        echo '<script> var team = "'.$_SESSION['team'].'";</script>';
        echo '<script> var USERTYPE = "'.$_SESSION['usertype'].'";</script>';
    }
    ?>
    <section>
        <nav class="navbar bg-light fixed-top">
            <div class="container-fluid">
                <span class="navbar-brand" id="navbar-username"></span>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvas-username"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item">
                                <button class="btn" onclick="window.location.href = '../index.php'"><i class="bi bi-house-door-fill me-2 text-dark"></i>Home</button>
                            </li>
                            <?php
                            if ($_SESSION['usertype'] == "Admin") {
                                echo '<li class="nav-item"><button class="btn" aria-current="page" onclick="window.location.href = \'admin.php\'"><i class="bi bi-person-circle me-2 text-primary"></i>Admin</button></li>';
                            } 
                            ?>
                            <li class="nav-item">
                                <button class="btn" onclick="location.reload();"><i class="bi bi-gear-fill me-2 text-success"></i>Setting</button>
                            </li>
                            <hr>
                            <li class="nav-item">
                                <button class="btn" onclick="logout()"><i class="bi bi-box-arrow-right me-2 text-danger"></i>Logout</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </section>

    <script>
        window.onload = function() {
            document.getElementById("navbar-username").innerText = FULLNAME;
            document.getElementById("offcanvas-username").innerText = FULLNAME;
        }

        function logout() {
            var logout_api = requestHTTPS('../api/backend.php', {
                'api': 'logout',
            }, true);
            if (logout_api.result == true) {
                window.location.href = "../login.html";
            }
        }
    </script>
</body>
</html>
