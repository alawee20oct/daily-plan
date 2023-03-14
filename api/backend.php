<?php
session_start();
date_default_timezone_set('Asia/Bangkok');
error_reporting(E_ERROR | E_PARSE);

include "../config/connect_db.php";
$input = json_decode(file_get_contents('php://input'), true);

if ($input['api'] == "login") {
    $username = $input['username'];
    $password = $input['password'];
    $sql = mysqli_query($connect, "SELECT * FROM tb_user WHERE username = '$username'");
    if (mysqli_num_rows($sql) > 0) {
        $sql = mysqli_query($connect, "SELECT * FROM tb_user WHERE username = '$username' AND password = '$password'");
        if (mysqli_num_rows($sql) > 0) {
            while ($row = mysqli_fetch_array($sql)) {
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['fullname'] = $row['fullname'];
                $_SESSION['team'] = $row['team'];
                $_SESSION['usertype'] = $row['usertype'];
            }
            $output = array('result'=>true, 'message'=>"");
        }
        else if (mysqli_num_rows($sql) == 0) {
            $output = array('result'=>false, 'message'=>"wrong-password");
        }
    }
    else {
        $output = array('result'=>false, 'message'=>"user-does-not-exist");
    }
    echo json_encode($output);
    exit();
}

if (empty($_SESSION['username'])) {
    header("Location: ../login.html");
}

if ($input['api'] == "logout") {
    session_destroy();
    $output = array('result'=>true);
}

// else if ($input['api'] == "save-plan") {
//     $id_user = $_SESSION['id'];
//     $options = $input['options'];
//     $datesave = $input['datesave'];
//     $date = date_format(date_create($datesave), "Y-m-d");

//     $sql = mysqli_query($connect, "SELECT * FROM tb_plan WHERE id_user = '$id_user' AND datesave = '$date'");
//     if (mysqli_num_rows($sql) > 0) {
//         $update = mysqli_query($connect, "UPDATE tb_plan SET options = '$options' WHERE id_user = '$id_user' AND datesave = '$date'");
//     }
//     else {
//         $insert = mysqli_query($connect, "INSERT INTO tb_plan (id_user, datesave, options) VALUES ('$id_user', '$date', '$options')");
//     }
// }

else if ($input['api'] == "load-plan") {
    $id_user = $_SESSION['id'];
    $month = $input['month'];
    $year = $input['year'];
    $plans = array();
    $i = 0;
    $sql = mysqli_query($connect, "SELECT * FROM tb_plan WHERE id_user = '$id_user' AND month = '$month' AND year = '$year'");
    while ($row = mysqli_fetch_array($sql)) {
        $plans[$i] = array(
            'datesave'=>date_format(date_create($row['datesave']), "d-M-Y"),
            'options'=>$row['options']
        );
        $i++;
    }
    $output['list_plan'] = $plans;
}

else if ($input['api'] == "load-user") {
    $id_user = $_SESSION['id'];
    $users = array();
    $i = 0;
    $sql = mysqli_query($connect, "SELECT * FROM tb_user WHERE 1 ORDER BY team ASC, fullname ASC");
    while ($row = mysqli_fetch_array($sql)) {
        $users[$i] = array(
            'id'=>$row['id'],
            'fullname'=>$row['fullname'],
            'nickname'=>$row['nickname'],
            'team'=>$row['team'],
            'usertype'=>$row['usertype'],
        );
        $i++;
    }
    $output['list_user'] = $users;
}

else if ($input['api'] == "load-team") {
    $teams = array();
    $i = 0;
    $sql = mysqli_query($connect, "SELECT * FROM tb_team WHERE 1 ORDER BY team ASC");
    while ($row = mysqli_fetch_array($sql)) {
        $teams[$i] = array(
            'id'=>$row['id'],
            'team'=>$row['team'],
        );
        $i++;
    }
    $output['list_team'] = $teams;
}

else if ($input['api'] == "load-user-plan") {
    $user_id = $input['user_id'];
    // $date = $input['date'];
    $month = $input['month'];
    $year = $input['year'];
    $plans = array();
    $i = 0;

    $sql = mysqli_query($connect, "SELECT * FROM tb_plan WHERE month = '$month' AND year = '$year' ORDER BY datesave ASC");
    while ($row = mysqli_fetch_array($sql)) {
        $plans[$i] = array(
            'result'=>true,
            'id'=>$row['id'],
            'id_user'=>$row['id_user'],
            'datesave'=>$row['datesave'],
            'options'=>$row['options'],
            'date'=>$row['date'],
            'month'=>$row['month'],
            'year'=>$row['year'],
        );
        $i++;
    }
    $output['plan_user'] = $plans;
}

else if ($input['api'] == "count-user-today") {
    $today = $input['today'];
    $i = 0;
    $sql_d = mysqli_query($connect, "SELECT * FROM tb_plan WHERE datesave = '$today' AND options = 'D'");
    $sql_n = mysqli_query($connect, "SELECT * FROM tb_plan WHERE datesave = '$today' AND options = 'N'");
    $sql_off = mysqli_query($connect, "SELECT * FROM tb_plan WHERE datesave = '$today' AND options = 'OFF'");
    $sql_al = mysqli_query($connect, "SELECT * FROM tb_plan WHERE datesave = '$today' AND options = 'AL'");
    
    $output = array(
        'today_d'=>mysqli_num_rows($sql_d),
        'today_n'=>mysqli_num_rows($sql_n),
        'today_off'=>mysqli_num_rows($sql_off),
        'today_al'=>mysqli_num_rows($sql_al),
    );
}

else if ($input['api'] == 'save-plan') {
    $user_id = $input['user_id'];
    $datesave = $input['datesave'];
    $options = $input['options'];

    $sql = mysqli_query($connect, "SELECT * FROM tb_plan WHERE id_user = '$user_id' AND datesave = '$datesave'");
    if (mysqli_num_rows($sql) > 0) {
        $update = mysqli_query($connect, "UPDATE tb_plan SET options = '$options' WHERE id_user = '$user_id' AND datesave = '$datesave'");
        if ($update) {
            $output = array('result'=>true, 'message'=>"");
        }
        else {
            $output = array('result'=>false, 'message'=>mysqli_error($connect));
        }
    }
    else {
        $div_date = explode("-", $datesave);
        $d = intval($div_date[2]);
        $m = intval($div_date[1]);
        $y = intval($div_date[0]);
        $insert = mysqli_query($connect,
            "INSERT INTO tb_plan (id_user, datesave, options, date, month, year) 
            VALUES ('$user_id', '$datesave', '$options', '$d', '$m', '$y')"
        );
        if ($insert) {
            $output = array('result'=>true, 'message'=>"");
        }
        else {
            $output = array('result'=>false, 'message'=>mysqli_error($connect));
        }
    }
}

else if ($input['api'] == 'save-holiday') {
    $holiday = $input['holiday'];
    $div_date = explode("-", $holiday);
    $d = intval($div_date[2]);
    $m = intval($div_date[1]);
    $y = intval($div_date[0]);
    $ok = 0;

    $sql_users = mysqli_query($connect, "SELECT * FROM tb_user WHERE 1");
    while ($row = mysqli_fetch_array($sql_users)) {
        $id = $row['id'];
        $sql_planned = mysqli_query($connect, "SELECT * FROM tb_plan WHERE id_user = '$id' AND datesave = '$holiday'");
        if (mysqli_num_rows($sql_planned) > 0) {
            $update = mysqli_query($connect, "UPDATE tb_plan SET options = 'H' WHERE id_user = '$id' AND datesave = '$holiday'");
            if ($update) {
                $ok++;
            }
        }
        else {
            $insert = mysqli_query($connect,
                "INSERT INTO tb_plan (id_user, datesave, options, date, month, year) 
                VALUES ('$id', '$holiday', 'H', '$d', '$m', '$y')"
            );
            if ($insert) {
                $ok++;
            }
        }
    }

    if (mysqli_num_rows($sql_users) == $ok) {
        $output = array('result'=>true);
    }
}

else if ($input['api'] == 'insert-user') {
    $username = $input['username'];
    $fullname = $input['fullname'];
    $nickname = $input['nickname'];
    $password = $input['password'];
    $team = $input['team'];
    $type = $input['type'];
    $sql = mysqli_query($connect,
        "INSERT INTO tb_user (username, fullname, nickname, password, team, usertype)
        VALUE ('$username', '$fullname', '$nickname', '$password', '$team', '$type')"
    );
    if ($sql) {
        $output = array('result'=>true, 'message'=>"");
    }
    else {
        $output = array('result'=>false, 'message'=>mysqli_error($connect));
    }
}

else if ($input['api'] == 'select-user') {
    $id = $input['id'];
    $sql = mysqli_query($connect, "SELECT * FROM tb_user WHERE id = '$id'");
    while ($row = mysqli_fetch_array($sql)) {
        $output = array(
            'id' => $row['id'],
            'username' => $row['username'],
            'fullname' => $row['fullname'],
            'nickname' => $row['nickname'],
            'password' => $row['password'],
            'team' => $row['team'],
            'usertype' => $row['usertype']
        );
    }
}

else if ($input['api'] == 'update-user') {
    $id = $input['id'];
    $username = $input['username'];
    $fullname = $input['fullname'];
    $nickname = $input['nickname'];
    $password = $input['password'];
    $team = $input['team'];
    $type = $input['type'];
    $sql = mysqli_query($connect, "UPDATE tb_user SET username = '$username', fullname = '$fullname', nickname = '$nickname', password = '$password', team = '$team', usertype = '$type' WHERE id = '$id'");
    if ($sql) {
        $output = array('result'=>true, 'message'=>"");
    }
    else {
        $output = array('result'=>false, 'message'=>mysqli_error($connect));
    }
}

else if ($input['api'] == 'delete-user') {
    $id = $input['id'];
    $sql = mysqli_query($connect, "DELETE FROM tb_user WHERE id = '$id'");
    if ($sql) {
        $output = array('result'=>true, 'message'=>"");
    }
    else {
        $output = array('result'=>false, 'message'=>mysqli_error($connect));
    }
}

else if ($input['api'] == 'insert-team') {
    $team = $input['team'];
    $sql = mysqli_query($connect, "SELECT * FROM tb_team WHERE team = '$team'");
    if (mysqli_num_rows($sql) > 0) {
        $output = array('result'=>false, 'message'=>"This Team Already Exist!");
    }
    else {
        $insert = mysqli_query($connect, "INSERT INTO tb_team (team) VALUES ('$team')");
        if ($insert) {
            $output = array('result'=>true, 'message'=>"");
        }
        else {
            $output = array('result'=>false, 'message'=>mysqli_error($connect));
        }
    }
}

else if ($input['api'] == 'update-team') {
    $teamID = $input['teamID'];
    $team = $input['team'];
    $sql = mysqli_query($connect, "SELECT * FROM tb_team WHERE team = '$team'");
    if (mysqli_num_rows($sql) > 0) {
        $output = array('result'=>false, 'message'=>"This Team Already Exist!");
    }
    else {
        $update = mysqli_query($connect, "UPDATE tb_team SET team = '$team' WHERE id = '$teamID'");
        if ($update) {
            $output = array('result'=>true, 'message'=>"");
        }
        else {
            $output = array('result'=>false, 'message'=>mysqli_error($connect));
        }
    }
}

else if ($input['api'] == 'delete-team') {
    $teamID = $input['teamID'];
    $delete = mysqli_query($connect, "DELETE FROM tb_team WHERE id = '$teamID'");
    if ($delete) {
        $output = array('result'=>true, 'message'=>"");
    }
    else {
        $output = array('result'=>false, 'message'=>mysqli_error($connect));
    }
}

echo json_encode($output);
exit();
?>
