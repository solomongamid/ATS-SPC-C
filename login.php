<?php
session_start();

include 'config.inc.php';
include 'header.php';

echo "<title>$title - Admin Login</title>\n";
echo '<head>';
echo '<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">';
echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">';
echo '<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">';
echo '<link rel="stylesheet" href="dist/css/AdminLTE.min.css">';
echo '<link rel="stylesheet" href="plugins/iCheck/square/blue.css">';
echo '</head>';

$self = $_SERVER['PHP_SELF'];

if (isset($_POST['login_userid']) && (isset($_POST['login_password']))) {
    $login_userid = $_POST['login_userid'];
    $login_password = crypt($_POST['login_password'], 'xy');

    $query = "select empfullname, employee_passwd, admin, time_admin from " . $db_prefix . "employees
              where empfullname = '" . $login_userid . "'";
    $result = mysql_query($query);

    while ($row = mysql_fetch_array($result)) {

        $admin_username = "" . $row['empfullname'] . "";
        $admin_password = "" . $row['employee_passwd'] . "";
        $admin_auth = "" . $row['admin'] . "";
        $time_admin_auth = "" . $row['time_admin'] . "";
    }

    if (($login_userid == @$admin_username) && ($login_password == @$admin_password) && ($admin_auth == "1")) {
        $_SESSION['valid_user'] = $login_userid;
    } elseif (($login_userid == @$admin_username) && ($login_password == @$admin_password) && ($time_admin_auth == "1")) {
        $_SESSION['time_admin_valid_user'] = $login_userid;
    }

}

if (isset($_SESSION['valid_user'])) {
    echo "<script type='text/javascript' language='javascript'> window.location.href = 'admin/index.php';</script>";
    exit;
} elseif (isset($_SESSION['time_admin_valid_user'])) {
    echo "<script type='text/javascript' language='javascript'> window.location.href = 'admin/employees.php';</script>";
    exit;

} else {

    // build form
    echo "<div class='wrapper'>\n";
echo "<div class='login-logo'>\n";
echo "<img src='dist/img/accenture-Logo.png' alt='System Image' height='200' width='450' align='center'>\n";
echo "</div>\n";
    echo "<div class='login-box-body'>\n";

      echo "<p class='login-box-msg' font-size='24'>Sign in</p>\n";
      echo "<table align=center width=210 border=0 cellpadding=7 cellspacing=1>\n";
      echo "<form name='auth' method='post' action='$self'>\n";
      echo  "<div class='form-group has-feedback'\n>";
          echo "<input type='text'  name='login_userid' class='form-control' placeholder='Username'>\n";
          echo "<span class='glyphicon glyphicon-envelope form-control-feedback'></span>\n";
        echo "</div>\n";
      echo "<div class='form-group has-feedback'>\n";
          echo "<input type='password' name='login_password' class='form-control' placeholder='Password'>\n";
          echo "<span class='glyphicon glyphicon-lock form-control-feedback'></span>\n";
        echo "</div>\n";
        echo "<div class='row'>\n";
          echo "<div class='col-xs-8'>\n";
            echo "<div class='checkbox icheck'>\n";
              echo "<label>\n";
                echo "<input type='checkbox'> Remember Me\n";
              echo "</label>\n";
            echo "</div>\n";
          echo "</div>\n";
          echo "</div>\n";
          echo "<div class='col-xs-4'>\n";
            echo "<button type='submit' onClick='admin.php' class='btn btn-primary btn-block btn-flat'>Sign In</button>\n";
          echo "</div>\n";
        echo "</div>\n";
      echo "</form>\n";

    echo "</div>\n";
    echo "</div>\n";
echo "</div>\n";
    if (isset($login_userid)) {
        echo "  <tr class=right_main_text><td align=center colspan=2>Could not log you in. Either your username or password is incorrect.</td></tr>\n";
    }

    echo "</table>\n";
    echo "</form>\n";
    echo "<script language=\"javascript\">document.forms['auth'].login_userid.focus();</script>\n";
}

echo "</body>\n";
echo "</html>\n";
?>
