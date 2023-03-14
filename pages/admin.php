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
    <script src="../lib/datetimeformat.js"></script>

    <title>Admin</title>
</head>
<body>
    <?php
    if (empty($_SESSION['username'])) {
        echo "<script>window.location.href = '../login.html'</script>";
    }
    else if (isset($_SESSION['username'])) {
        if ($_SESSION['usertype'] != "Admin") {
            echo "<script>window.location.href = '../index.php'</script>";
        }
        else {
            echo '<script> var ID_USER = "'.$_SESSION['id'].'";</script>';
            echo '<script> var username = "'.$_SESSION['username'].'";</script>';
            echo '<script> var FULLNAME = "'.$_SESSION['fullname'].'";</script>';
            echo '<script> var team = "'.$_SESSION['team'].'";</script>';
            echo '<script> var USERTYPE = "'.$_SESSION['usertype'].'";</script>';
        }
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
                                echo '<li class="nav-item"><button class="btn" aria-current="page" onclick="location.reload();"><i class="bi bi-person-circle me-2 text-primary"></i>Admin</button></li>';
                            } 
                            ?>
                            <li class="nav-item">
                                <button class="btn" onclick="window.location.href = 'setting.php'"><i class="bi bi-gear-fill me-2 text-success"></i>Setting</button>
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

    <section class="mt-5 pt-5">
        <div class="container">
            <div class="input-group mb-4">
                <button class="btn btn-primary" onclick="addNewUser(false)"><i class="bi bi-plus-circle me-2"></i>New User</button>
                <input type="text" name="" id="" class="form-control" placeholder="Search User By Name">
                <button class="btn btn-outline-primary" onclick="searchUser()"><i class="bi bi-search me-2"></i>Search</button>
                <button class="btn btn-outline-secondary" onclick="resetSearch()"><i class="bi bi-x-circle me-2"></i>Reset</button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="text-center">
                        <th>#</th>
                        <th>Name</th>
                        <th>Nickname</th>
                        <th>Team<i class="bi bi-gear ms-2" onclick="settingTeam()" style="cursor: pointer;"></i></th>
                        <th>Type</th>
                        <th><i class="bi bi-info-circle text-info"></i></th>
                        <th><i class="bi bi-pencil-square text-success"></i></th>
                        <th><i class="bi bi-trash3-fill text-danger"></i></th>
                    </thead>
                    <tbody class="text-center" id="table-user">
    
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <div class="modal fade" tabindex="-1" id="add-new-user-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add-username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="add-username">
                    </div>
                    <div class="mb-3">
                        <label for="add-fullname" class="form-label">Fullname</label>
                        <input type="text" class="form-control" id="add-fullname">
                    </div>
                    <div class="mb-3">
                        <label for="add-nickname" class="form-label">Nickname</label>
                        <input type="text" class="form-control" id="add-nickname">
                    </div>
                    <div class="mb-3">
                        <label for="add-password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="add-password">
                    </div>
                    <div class="mb-3">
                        <label for="add-team" class="form-label">Team</label>
                        <select class="form-select" id="add-team">

                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add-type" class="form-label">Type</label>
                        <select class="form-select" id="add-type">
                            <option value="" selected>Select</option>
                            <option value="Admin">Admin</option>
                            <option value="Tech">Tech</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="addNewUser(true)">Add New User</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="view-user-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="view-username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="view-username" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="view-fullname" class="form-label">Fullname</label>
                        <input type="text" class="form-control" id="view-fullname" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="view-nickname" class="form-label">Nickname</label>
                        <input type="text" class="form-control" id="view-nickname" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="view-password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="view-password" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="view-team" class="form-label">Team</label>
                        <input type="text" class="form-control" id="view-team" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="view-type" class="form-label">Type</label>
                        <input type="text" class="form-control" id="view-type" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="edit-user-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-id">
                    <div class="mb-3">
                        <label for="edit-username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="edit-username">
                    </div>
                    <div class="mb-3">
                        <label for="edit-fullname" class="form-label">Fullname</label>
                        <input type="text" class="form-control" id="edit-fullname">
                    </div>
                    <div class="mb-3">
                        <label for="edit-nickname" class="form-label">Nickname</label>
                        <input type="text" class="form-control" id="edit-nickname">
                    </div>
                    <div class="mb-3">
                        <label for="edit-password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="edit-password">
                    </div>
                    <div class="mb-3">
                        <label for="edit-team" class="form-label">Team</label>
                        <select class="form-select" id="edit-team"></select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-type" class="form-label">Type</label>
                        <select class="form-select" id="edit-type">
                            <option value="" selected>Select</option>
                            <option value="Admin">Admin</option>
                            <option value="Tech">Tech</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="editUser(null)">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="delete-user-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="delete-id">
                    <h5 class="text-center mb-3"><i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>Do You Want To Delete This User?</h5>
                    <div class="mb-3">
                        <label for="delete-fullname" class="form-label">Fullname</label>
                        <input type="text" class="form-control" id="delete-fullname" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="delete-nickname" class="form-label">Nickname</label>
                        <input type="text" class="form-control" id="delete-nickname" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="delete-team" class="form-label">Team</label>
                        <input type="text" class="form-control" id="delete-team" disabled>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="deleteUser(null)">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="setting-team-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Setting Team</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-1">
                        <input type="text" class="form-control" placeholder="Add New Team" id="add-new-team">
                        <button class="btn btn-outline-primary" onclick="addNewTeam()"><i class="bi bi-plus-circle"></i></button>
                    </div>
                    <hr>
                    <div class="alert alert-danger mb-1 visually-hidden" role="alert" id="alert-team"></div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="text-center">
                                <th align="center">#</th>
                                <th align="center">Team</th>
                                <th align="center">Edit</th>
                                <th align="center">Delete</th>
                            </thead>
                            <tbody id="list_team">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            document.getElementById("navbar-username").innerText = FULLNAME;
            document.getElementById("offcanvas-username").innerText = FULLNAME;
            loadUsers();
        }

        function loadUsers() {
            var load_user = requestHTTPS('../api/backend.php', {
                'api': 'load-user'
            }, true);

            var list_users = "";
            
            for (var i = 0; i < load_user.list_user.length; i++) {
                var item = load_user.list_user[i];
                list_users += '<tr>';
                    list_users += '<td valign="middle">'+(i+1)+'</td>';
                    list_users += '<td valign="middle">'+item.fullname+'</td>';
                    list_users += '<td valign="middle">'+item.nickname+'</td>';
                    list_users += '<td valign="middle">'+item.team+'</td>';
                    list_users += '<td valign="middle">'+item.usertype+'</td>';
                    list_users += '<td valign="middle"><button class="btn btn-outline-info" id="'+item.id+'" onclick="viewUser(this)">View</button></td>';
                    list_users += '<td valign="middle"><button class="btn btn-outline-success" id="'+item.id+'" onclick="editUser(this)">Edit</button></td>';
                    list_users += '<td valign="middle"><button class="btn btn-outline-danger" id="'+item.id+'" onclick="deleteUser(this)">Delete</button></td>';
                list_users += '</tr>';
            }
            document.getElementById("table-user").innerHTML = list_users;
        }

        function loadTeams(id_select) {
            var teams = requestHTTPS('../api/backend.php', {
                'api': 'load-team'
            }, true);
            var list_team = '<option value="" selected>Select</option>';
            for (var i = 0; i < teams.list_team.length; i++) {
                var item = teams.list_team[i];
                list_team += '<option value="'+item.team+'">'+item.team+'</option>';
            }
            document.getElementById(id_select).innerHTML = list_team;
        }

        function addNewUser(param) {
            if (param == false) {
                loadTeams("add-team");
                $('#add-new-user-modal').modal('show');
            }
            else if (param == true) {
                var username = document.getElementById("add-username").value;
                var fullname = document.getElementById("add-fullname").value;
                var nickname = document.getElementById("add-nickname").value;
                var password = document.getElementById("add-password").value;
                var team = document.getElementById("add-team").value;
                var type = document.getElementById("add-type").value;

                if (username == "" || fullname == "" || nickname == "" || password == "" || team == "" || type == "") {

                }
                else {
                    var insert = requestHTTPS('../api/backend.php', {
                        'api': 'insert-user',
                        'username': username,
                        'fullname': fullname,
                        'nickname': nickname,
                        'password': password,
                        'team': team,
                        'type': type,
                    }, true);
                    if (insert.result == true) {
                        location.reload();
                    }
                    else {
    
                    }
                }
            }
        }

        function viewUser(btn) {
            var id_user = btn.id;
            var view_user = requestHTTPS('../api/backend.php', {
                'api': 'select-user',
                'id': id_user
            }, true);

            document.getElementById("view-username").value = view_user.username;
            document.getElementById("view-fullname").value = view_user.fullname;
            document.getElementById("view-nickname").value = view_user.nickname;
            document.getElementById("view-password").value = view_user.password;
            document.getElementById("view-team").value = view_user.team;
            document.getElementById("view-type").value = view_user.usertype;

            $('#view-user-modal').modal('show');
        }

        function editUser(btn) {
            if (btn != null) {
                var id_user = btn.id;
                var view_user = requestHTTPS('../api/backend.php', {
                    'api': 'select-user',
                    'id': id_user
                }, true);
                document.getElementById("edit-id").value = view_user.id;
                document.getElementById("edit-username").value = view_user.username;
                document.getElementById("edit-fullname").value = view_user.fullname;
                document.getElementById("edit-nickname").value = view_user.nickname;
                document.getElementById("edit-password").value = view_user.password;
                loadTeams("edit-team");
                document.getElementById("edit-team").value = view_user.team;
                document.getElementById("edit-type").value = view_user.usertype;

                $('#edit-user-modal').modal('show');
            }
            else if (btn == null) {
                var id_user = document.getElementById("edit-id").value;
                var username = document.getElementById("edit-username").value;
                var fullname = document.getElementById("edit-fullname").value;
                var nickname = document.getElementById("edit-nickname").value;
                var password = document.getElementById("edit-password").value;
                var team = document.getElementById("edit-team").value;
                var type = document.getElementById("edit-type").value;
                
                var edit_user = requestHTTPS('../api/backend.php', {
                    'api': 'update-user',
                    'id': id_user,
                    'username': username,
                    'fullname': fullname,
                    'nickname': nickname,
                    'password': password,
                    'team': team,
                    'type': type
                }, true);

                if (edit_user.result == true) {
                    location.reload();
                }
                else {

                }
            }
        }

        function deleteUser(btn) {
            if (btn != null) {
                var id_user = btn.id;
                var view_user = requestHTTPS('../api/backend.php', {
                    'api': 'select-user',
                    'id': id_user
                }, true);
                document.getElementById("delete-id").value = view_user.id;
                document.getElementById("delete-fullname").value = view_user.fullname;
                document.getElementById("delete-nickname").value = view_user.nickname;
                document.getElementById("delete-team").value = view_user.team;

                $('#delete-user-modal').modal('show');
            }
            else if (btn == null) {
                var id_user = document.getElementById("delete-id").value;

                var delete_user = requestHTTPS('../api/backend.php', {
                    'api': 'delete-user',
                    'id': id_user,
                }, true);

                if (delete_user.result == true) {
                    location.reload();
                }
                else {

                }
            }
        }

        function settingTeam() {
            var list_team = requestHTTPS('../api/backend.php', {
                'api': 'load-team'
            }, true);

            var teams = "";
            for (var i = 0; i < list_team.list_team.length; i++) {
                var item = list_team.list_team[i];
                teams += '<tr>';
                    teams += '<td align="center">'+(i+1)+'</td>';
                    teams += '<td align="center"><input type="text" class="form-control" value="'+item.team+'" id="edit-'+item.id+'"></td>';
                    teams += '<td align="center">';
                        teams += '<btton class="btn btn-outline-success" id="EDIT-'+item.id+'" onclick="editTeam(this)">';
                            teams += '<i class="bi bi-pencil-square"></i>';
                        teams += '</btton>';
                    teams += '</td>';
                    teams += '<td align="center">';
                        teams += '<btton class="btn btn-outline-danger" id="DELETE-'+item.id+'" onclick="deleteTeam(this)">';
                            teams += '<i class="bi bi-trash3-fill"></i>';
                        teams += '</btton>';
                    teams += '</td>';
                teams += '</tr>';
            }
            document.getElementById("list_team").innerHTML = teams;
            $("#setting-team-modal").modal('show');
        }

        function addNewTeam() {
            var newTeam = document.getElementById("add-new-team").value;
            if (newTeam == "") {
                document.getElementById("alert-team").innerText = "Please Enter Team Name";
                document.getElementById("alert-team").classList.remove("visually-hidden");
                document.getElementById("alert-team").classList.add("visually-visible");
                return;
            }
            else {
                var insert = requestHTTPS('../api/backend.php', {
                    'api': 'insert-team',
                    'team': newTeam
                }, true);
                if (insert.result == true) {
                    location.reload();
                }
                else if (insert.result == false) {
                    document.getElementById("alert-team").innerText = insert.message;
                    document.getElementById("alert-team").classList.remove("visually-hidden");
                    document.getElementById("alert-team").classList.add("visually-visible");
                    return;
                }
            }
        }

        function editTeam(btn) {
            var teamID = btn.id.replace("EDIT-", "");
            var newTeam = document.getElementById("edit-"+teamID).value;
            if (newTeam == "") {
                document.getElementById("alert-team").innerText = "Please Enter Team Name";
                document.getElementById("alert-team").classList.remove("visually-hidden");
                document.getElementById("alert-team").classList.add("visually-visible");
                return;
            }
            else {
                var update = requestHTTPS('../api/backend.php', {
                    'api': 'update-team',
                    'teamID': teamID,
                    'team': newTeam
                }, true);
                if (update.result == true) {
                    location.reload();
                }
                else if (update.result == false) {
                    document.getElementById("alert-team").innerText = update.message;
                    document.getElementById("alert-team").classList.remove("visually-hidden");
                    document.getElementById("alert-team").classList.add("visually-visible");
                    return;
                }
            }
        }

        function deleteTeam(btn) {
            var teamID = btn.id.replace("DELETE-", "");
            var del = requestHTTPS('../api/backend.php', {
                'api': 'delete-team',
                'teamID': teamID,
            }, true);
            if (del.result == true) {
                location.reload();
            }
            else if (del.result == false) {
                document.getElementById("alert-team").innerText = del.message;
                document.getElementById("alert-team").classList.remove("visually-hidden");
                document.getElementById("alert-team").classList.add("visually-visible");
                return;
            }
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
