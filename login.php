<?php
    session_start();
    include('connect.php');
    $test = "pphejlada1";
    // $test = "tnguyen366";
    if(isset($_POST['submit'])){
        $db = new mysqli("localhost", $test, $test, $test);
        $query = "SELECT * FROM users WHERE login = '" . $_POST['user'] . "' AND password = '" . $_POST['pass'] . "'";
        $sql = $db->query($query);
        $n = $sql->num_rows;
        if($n > 0){
            $_SESSION['signin'] = true;
            if($_SESSION['signin']){
                    $query1 = "SELECT * FROM users WHERE login = '" . $_POST['user'] . "' AND password = '" . $_POST['pass'] . "'";
                    $rs = mysql_query($query1);
                    if (!$rs) {
                        echo "Could not execute query: $query1";
                        trigger_error(mysql_error(), E_USER_ERROR); 
                    }
                    while ($row = mysql_fetch_assoc($rs)){
                        $_SESSION['user_id'] = $row['customerid'];
                        $_SESSION['user_first'] = $row['firstname'];
                        $_SESSION['user_last'] = $row['lastname'];
                        $_SESSION['user_adress'] = $row['address'];
                    }
                }
            header("Location: home.php");
        }    
        else {
            $error = "Invalid login username or password";
        }
    }
?>
<html>
    <head>
        <title>Login</title>
        <style>
            body{
                background-color: paleturquoise;
                text-align: center;
            }
            .login{
                text-align: center;
                background-color: white;
                width: 300px;
                border: solid 1px black;
                margin: 0 auto;
                padding: 10px;
            }
            .error{
                color: red;
            }
        </style>
    </head>
    <body>
        <h1>Login to TACOCAT COMPANY</h1>
        <form method = "POST">
            <div class="login">
              <label><b>Username</b></label>
              <input type="text" placeholder="Enter Username" name="user" required><br>
          
              <label><b>Password</b></label>
              <input type="password" placeholder="Enter Password" name="pass" required><br>
          
              <button type = "submit" name = "submit">Login</button>
              <div class = "error"><?php echo $error; ?></div>
            </div>
        </form>
    </body>
</html>