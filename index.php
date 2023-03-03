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
    <link rel="icon" type="image/png" href="images/icon.png"/>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="api/https.js"></script>
    <script src="lib/datetimeformat.js"></script>

    <title>Daily Plan</title>

    <style>
        #active-username {
            background-color: lightskyblue;
            animation: blink1 2s linear infinite;
        }
        #active-team {
            background-color: lightskyblue;
            animation: blink2 2s linear infinite;
        }
        #active-nickname {
            background-color: lightskyblue;
            animation: blink3 2s linear infinite;
        }
        @keyframes blink1 {
            50% {
                background-color: white;
            }
        }
        @keyframes blink2 {
            50% {
                background-color: white;
            }
        }
        @keyframes blink3 {
            50% {
                background-color: white;
            }
        }
    </style>
</head>

<body>
    <?php
    if (empty($_SESSION['username'])) {
        echo "<script>window.location.href = 'login.html'</script>";
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
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel" data-bs-scroll="true">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvas-username"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item">
                                <button class="btn" onclick="location.reload();"><i class="bi bi-house-door-fill me-2 text-dark"></i>Home</button>
                            </li>
                            <?php
                            if ($_SESSION['usertype'] == "Admin") {
                                echo '<li class="nav-item"><button class="btn" onclick="window.location.href = \'pages/admin.php\'"><i class="bi bi-person-circle me-2 text-primary"></i>Admin</button></li>';
                                echo '<li class="nav-item"><button class="btn" onclick=""><i class="bi bi-calendar-week-fill me-2 text-primary"></i>Plan</button></li>';
                                echo '<li class="nav-item"><button class="btn" onclick="holiday()"><i class="bi bi-calendar-x me-2 text-primary"></i>Holiday</button></li>';
                            } 
                            ?>
                            <li class="nav-item">
                                <button class="btn" onclick="window.location.href = 'pages/setting.php'"><i class="bi bi-gear-fill me-2 text-success"></i>Setting</button>
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
            <div class="text-center pb-3">
                <h4 id="today-title"></h4>
            </div>
            <div class="row text-center">
                <div class="col-lg-3">
                    <div class="border border-3 rounded-4 border-success py-1">
                        <h5><i class="bi bi-sun-fill me-2"></i>DAY</h5>
                        <h2 id="count_d"></h5>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="border border-3 rounded-4 border-warning py-1">
                        <h5><i class="bi bi-moon-fill me-2"></i>NIGHT</h5>
                        <h2 id="count_n"></h2>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="border border-3 rounded-4 border-secondary py-1">
                        <h5><i class="bi bi-building-fill-x me-2"></i></i>OFF</h5>
                        <h2 id="count_off"></h2>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="border border-3 rounded-4 border-danger py-1">
                        <h5><i class="bi bi-person-fill-slash me-2"></i>LEAVE</h5>
                        <h2 id="count_al"></h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <hr>

    <div class="container mt-4 px-4 pt-3">
        <div class="input-group">
            <select class="form-select" id="month-select">

            </select>
            <select class="form-select" id="year-select">

            </select>
            <button class="btn btn-outline-primary d-none d-sm-block px-5" onclick="changeTablePlan()"><i class="bi bi-arrow-right-square-fill me-2"></i>Go</button>
            <button class="btn btn-outline-secondary d-none d-sm-block px-5"><i class="bi bi-x-circle me-2"></i>Reset</button>
        </div>
    </div>

    <div class="container mt-2 px-4 py-4">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover table-sm table-borderless table-fixed w-auto" id="day-table">
                <thead class="text-center border-dark border-top border-bottom" >
                    <tr id="date_thead">
                        
                    </tr>
                    <tr id="day_thead">
                        
                    </tr>
                </thead>
                <tbody class="text-center" id="user_column">
                    
                </tbody>
            </table>
        </div>
    </div>
    <hr>

    <div class="modal" tabindex="-1" id="option-plan-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="date-select-plan">Select Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeAlert()"></button>
                </div>
                <div class="modal-body">
                    <input type="radio" class="btn-check" name="option-plan-radio" id="option-D" autocomplete="off">
                    <label class="btn btn-outline-success me-1" for="option-D">D</label>

                    <input type="radio" class="btn-check" name="option-plan-radio" id="option-N" autocomplete="off">
                    <label class="btn btn-outline-warning me-1" for="option-N">N</label>

                    <input type="radio" class="btn-check" name="option-plan-radio" id="option-AL" autocomplete="off">
                    <label class="btn btn-outline-danger me-1" for="option-AL">AL</label>

                    <input type="radio" class="btn-check" name="option-plan-radio" id="option-1stAL" autocomplete="off">
                    <label class="btn btn-outline-danger me-1" for="option-1stAL">1<sup>st</sup>-AL</label>

                    <input type="radio" class="btn-check" name="option-plan-radio" id="option-2ndAL" autocomplete="off">
                    <label class="btn btn-outline-danger me-1" for="option-2ndAL">2<sup>nd</sup>-AL</label>

                    <div class="alert alert-danger mt-4 visually-hidden" role="alert" id="alert-select-plan">
                        Please Select Option!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="save-plan-btn">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="holiday-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="">Set Holiday</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="date" class="form-control" name="" id="date-holiday">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="" onclick="saveHoliday()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function () {
            document.getElementById("navbar-username").innerText = FULLNAME;
            document.getElementById("offcanvas-username").innerText = FULLNAME;
    
            var current = new Date();
            var year = current.getFullYear();
            var month = current.getMonth();
            var date = current.getDate();
            var day = current.getDay();
    
            renderInputMonthYear(current);
            renderTablePlan(month, year);
            countUserWork(date, month, year);
        }
    
        function renderInputMonthYear(current) {
            var month = current.getMonth();
            var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            var month_option = "";
            for (var i in months) {
                if (i == month) {
                    month_option += '<option value="'+i+'" selected>'+months[i]+'</option>';
                }
                else {
                    month_option += '<option value="'+i+'">'+months[i]+'</option>';
                }
            }
            document.getElementById("month-select").innerHTML = month_option;
    
            var year = current.getFullYear();
            var year_option = "";
            for (var i = year-2; i < year+5; i++) {
                if (i == year) {
                    year_option += '<option value="'+i+'" selected>'+i+'</option>';
                }
                else {
                    year_option += '<option value="'+i+'">'+i+'</option>';
                }
            }
            document.getElementById("year-select").innerHTML = year_option;
        }
    
        function savePlan() {
            var option = document.getElementsByName("plan-option");
            for (var i = 0; i < option.length; i++) {
                var save_plan = requestHTTPS('api/backend.php', {
                    'api': 'save-plan',
                    'datesave': option[i].id,
                    'options': option[i].value
                }, true);
            }
            location.reload();
        }
    
        function loadPlan(month, year) {
            var load_plan = requestHTTPS('api/backend.php', {
                'api': 'load-plan',
                'month': month,
                'year': year
            }, true);
            try {
                for (var i in load_plan.list_plan) {
                    var item = load_plan.list_plan[i];
                    document.getElementById(item.datesave).value = item.options;
                }
            }
            catch (err) {
                console.log(err);
            }
        }
    
        function renderTablePlan(month, year) {
            var first_day_month = new Date(year, month, 1).getDay();
            var last_date_month = new Date(year, month + 1, 0).getDate();
            var last_day_month = new Date(year, month, last_date_month).getDay();
            var last_date_last_month = new Date(year, month, 0).getDate();
    
            var date_thead = "";
            var day_thead = "";
            
            date_thead += '<th valign="middle" class="fw-semibold text-dark border-dark border-start border-end" rowspan="2">NAME</th>';
            date_thead += '<th valign="middle" class="fw-semibold text-dark border-dark border-start border-end" rowspan="2">TEAM</th>';
            date_thead += '<th valign="middle" class="fw-semibold text-dark border-dark border-start border-end" rowspan="2">NICKNAME</th>';
             
            for (var i = 0; i < last_date_month; i++) {
                if (i+1 < 10) {
                    var date_fmt = dateFormat(i+1, "onedigit");
                }
                else if (i+1 >= 10) {
                    var date_fmt = dateFormat(i+1, "twodigit");
                }
                var month_fmt = monthFormat(month, "idx");
                var year_fmt = yearFormat(year, "fourdigit");
                date_thead += '<th valign="middle" class="fw-bold text-white border-white border-start border-end" style="background-color: #262626;">';
                    date_thead += '<div>';
                        date_thead += '<span class="text-center h6">'+date_fmt.twodigit_string+'</span><br>';
                        date_thead += '<span class="text-center h6">'+month_fmt.short+'</span><br>';
                        date_thead += '<span class="text-center h6">'+year_fmt.twodigit_string+'</span>';
                    date_thead += '</div>';
                date_thead += '</th>';
            }
            document.getElementById("date_thead").innerHTML = date_thead;
            var d = 0;
            for (var i = first_day_month; i < last_date_month + first_day_month; i++) {
                if (i == 7 || i == 14 || i == 21 || i == 28 || i == 35) {
                    d = 0;
                }
                else if (i < 7) {
                    d = i;
                }
                else {
                    d++;
                }
                var day_fmt = dayFormat(d, "idx");
                day_thead += '<td class="text-dark border-top border-bottom border-start border-dark" style="background-color: '+day_fmt.color+';">';
                    day_thead += '<div class="py-3" style="transform: rotate(270deg);">';
                        day_thead += '<span class="text-center fw-semibold">'+day_fmt.short+'</span>';
                    day_thead += '</div>';
                day_thead += '</td>';
            }
            document.getElementById("day_thead").innerHTML = day_thead;
            renderUser(month, year, last_date_month);
        }

        function changeTablePlan() {
            var select_month = document.getElementById("month-select").value;
            var select_year = document.getElementById("year-select").value;
            var month_cvt = parseInt(select_month);
            var year_cvt = parseInt(select_year);
            var new_plan = new Date();
            renderTablePlan(month_cvt, year_cvt);
        }

        function renderUser(month, year, last_date_month) {
            var load_user = requestHTTPS('api/backend.php', {
                'api': 'load-user',
            }, true);

            var user_column = "";
            var temp = load_user.list_user[0].team;
            for (var i = 0; i < load_user.list_user.length; i++) {
                var item = load_user.list_user[i];

                if (i < load_user.list_user.length-1) {
                    if (load_user.list_user[i].team != load_user.list_user[i+1].team) {
                        user_column += '<tr class="border-bottom border-dark">';
                    }
                    else {
                        user_column += '<tr class="border-bottom">';
                    }
                }
                else if (i == load_user.list_user.length-1) {
                    user_column += '<tr class="border-bottom border-dark">';
                }
                
                if (item.id == ID_USER) {
                    user_column += '<td valign="middle" id="active-username">'+item.fullname+'</td>';
                    user_column += '<td valign="middle" id="active-team">'+item.team+'</td>';
                    user_column += '<td valign="middle" id="active-nickname">'+item.nickname+'</td>';
                }
                else if (item.id != ID_USER) {
                    user_column += '<td valign="middle">'+item.fullname+'</td>';
                    user_column += '<td valign="middle">'+item.team+'</td>';
                    user_column += '<td valign="middle">'+item.nickname+'</td>';
                }

                for (var j = 0; j < last_date_month; j++) {
                    if (j+1 < 10) {
                        var id_td = yearFormat(year, "fourdigit").fourdigit_string + "-" + monthFormat(month, "idx").twodigit + "-" + dateFormat(j+1, "onedigit").twodigit_string;
                    }
                    else if (j+1 >= 10) {
                        var id_td = yearFormat(year, "fourdigit").fourdigit_string + "-" + monthFormat(month, "idx").twodigit + "-" + dateFormat(j+1, "twodigit").twodigit_string;
                    }

                    if (ID_USER == item.id) {
                        user_column += '<td valign="middle" id="'+item.id+':'+id_td+'" class="border-dark border-start booder-end" style="cursor: pointer;" onclick="optionPopUp(this)">none</td>';
                    }
                    else {
                        user_column += '<td valign="middle" id="'+item.id+':'+id_td+'" class="table-active border-dark border-start booder-end">none</td>';
                    }
                }
                user_column += '</tr>';
            }
            document.getElementById("user_column").innerHTML = user_column;
            userPlan(month, year);
        }

        function userPlan(month, year) {
            var user_plan = requestHTTPS('api/backend.php', {
                'api': 'load-user-plan',
                'month': month+1,
                'year': year
            }, true);

            for (var i in user_plan.plan_user) {
                var item = user_plan.plan_user[i];
                if (item.options == "OFF") {
                    document.getElementById(item.id_user+":"+item.datesave).innerHTML = item.options;
                    document.getElementById(item.id_user+":"+item.datesave).style.backgroundColor = "#A6A6A6";
                    document.getElementById(item.id_user+":"+item.datesave).classList.add("text-white");
                }
                else if (item.options == "D") {
                    document.getElementById(item.id_user+":"+item.datesave).innerHTML = item.options;
                    document.getElementById(item.id_user+":"+item.datesave).style.backgroundColor = "#C6E0B4";
                    document.getElementById(item.id_user+":"+item.datesave).classList.add("text-dark");
                }
                else if (item.options == "N") {
                    document.getElementById(item.id_user+":"+item.datesave).innerHTML = item.options;
                    document.getElementById(item.id_user+":"+item.datesave).style.backgroundColor = "#FFE699";
                    document.getElementById(item.id_user+":"+item.datesave).classList.add("text-dark");
                }
                else if (item.options == "AL") {
                    document.getElementById(item.id_user+":"+item.datesave).innerHTML = item.options;
                    document.getElementById(item.id_user+":"+item.datesave).style.backgroundColor = "#FF5050";
                    document.getElementById(item.id_user+":"+item.datesave).classList.add("text-dark");
                    document.getElementById(item.id_user+":"+item.datesave).classList.add("fw-semibold");
                }
                else if (item.options == "1stAL") {
                    document.getElementById(item.id_user+":"+item.datesave).innerHTML = "1<sup>st</sup>-AL";
                    document.getElementById(item.id_user+":"+item.datesave).style.backgroundColor = "#FF5050";
                    document.getElementById(item.id_user+":"+item.datesave).classList.add("text-dark");
                    document.getElementById(item.id_user+":"+item.datesave).classList.add("fw-semibold");
                }
                else if (item.options == "2ndAL") {
                    document.getElementById(item.id_user+":"+item.datesave).innerHTML = "2<sup>nd</sup>-AL";
                    document.getElementById(item.id_user+":"+item.datesave).style.backgroundColor = "#FF5050";
                    document.getElementById(item.id_user+":"+item.datesave).classList.add("text-dark");
                    document.getElementById(item.id_user+":"+item.datesave).classList.add("fw-semibold");
                }
                else if (item.options == "H" || item.options == "SET") {
                    document.getElementById(item.id_user+":"+item.datesave).innerHTML = item.options;
                    document.getElementById(item.id_user+":"+item.datesave).style.backgroundColor = "#FFFFFF";
                    document.getElementById(item.id_user+":"+item.datesave).classList.add("text-dark");
                }
                else if (item.options == "OT-D") {
                    document.getElementById(item.id_user+":"+item.datesave).innerHTML = item.options;
                    document.getElementById(item.id_user+":"+item.datesave).style.backgroundColor = "#66FF00";
                    document.getElementById(item.id_user+":"+item.datesave).classList.add("text-dark");
                }
                else if (item.options == "OT-N") {
                    document.getElementById(item.id_user+":"+item.datesave).innerHTML = item.options;
                    document.getElementById(item.id_user+":"+item.datesave).style.backgroundColor = "#FFBB00";
                    document.getElementById(item.id_user+":"+item.datesave).classList.add("text-dark");
                }
            }
        }

        function optionPopUp(ele) {
            var date_title = ele.id.replace(ID_USER+":", "");   // ex. 2023-01-31
            var date_head = date_title[8] + date_title[9];
            var month_head = monthFormat(date_title[5] + date_title[6], "twodigit").short;
            var year_head = date_title[0] + date_title[1] + date_title[2] + date_title[3]
            document.getElementById("date-select-plan").innerText = date_head + " " + month_head + ". " + year_head;

            if (ele.innerHTML != "none" && ele.innerHTML != "H" && ele.innerHTML != "OFF") {
                if (ele.innerHTML == "1<sup>st</sup>-AL") {
                    var radio_btn = "option-1stAL";
                }
                else if (ele.innerHTML == "2<sup>nd</sup>-AL") {
                    var radio_btn = "option-2ndAL";
                }
                else {
                    var radio_btn = "option-" + ele.innerHTML;
                }
                document.getElementById(radio_btn).checked = true;
            }
            else {
                var none_btn = document.getElementsByName("option-plan-radio");
                for (var i = 0; i < none_btn.length; i++) {
                    none_btn[i].checked = false;
                }
            }

            var btn_save = document.getElementById("save-plan-btn");
            btn_save.onclick = function() {
                savePlanUser(date_title);
            }
            $('#option-plan-modal').modal('show');
        }

        function savePlanUser(date) {
            var radio = document.getElementsByName("option-plan-radio");
            var uncheck = 0;
            for (var i = 0; i < radio.length; i++) {
                if (radio[i].checked == false) {
                    uncheck++;
                }
            }

            if (uncheck == radio.length) {
                document.getElementById("alert-select-plan").classList.remove("visually-hidden");
                document.getElementById("alert-select-plan").classList.add("visually-visible");
                return;
            }
            else {
                for (var j = 0; j < radio.length; j++) {
                    if (radio[j].checked) {
                        var plan = radio[j].id.replace("option-", "");
                        var save_plan = requestHTTPS('api/backend.php', {
                            'api': 'save-plan',
                            'user_id': ID_USER,
                            'datesave': date,
                            'options': plan,
                        }, true);
                        if (save_plan.result == true) {
                            location.reload();
                        }
                        else if (save_plan.result == false) {
                            console.log(save_plan.message);
                        }
                    }
                }
            }
        }

        function closeAlert() {
            document.getElementById("alert-select-plan").classList.remove("visually-visible");
            document.getElementById("alert-select-plan").classList.add("visually-hidden");
        }

        function countUserWork(date, month, year) {
            var m = monthFormat(month, "idx");
            var today = year + "-" + m.twodigit + "-" + date;
            document.getElementById("today-title").innerText = date + "-" + m.short + "-" + year;
            var count_user = requestHTTPS('api/backend.php', {
                'api': 'count-user-today',
                'today': today,
            }, true);
            document.getElementById("count_d").innerText = count_user.today_d;
            document.getElementById("count_n").innerText = count_user.today_n;
            document.getElementById("count_off").innerText = count_user.today_off;
            document.getElementById("count_al").innerText = count_user.today_al;
        }

        function holiday() {
            $('#holiday-modal').modal('show');
        }

        function saveHoliday() {
            var date = document.getElementById("date-holiday").value;
            var save_holiday = requestHTTPS('api/backend.php', {
                'api': 'save-holiday',
                'holiday': date,

            }, true);
            if (save_holiday.result == true) {
                location.reload();
            }
        }
    
        function logout() {
            var logout_api = requestHTTPS('api/backend.php', {
                'api': 'logout',
            }, true);
            if (logout_api.result == true) {
                window.location.href = "login.html";
            }
        }
    </script>
</body>
</html>