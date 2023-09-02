<?php
    session_start();
    if(isset($_POST['username']) && isset($_POST['password']))
    {
        $user1=$_POST['username'];
        $pass1=$_POST['password'];
        $flag=0;
        try
        {
            $host="localhost";
            $dbname="myproject";
            $user="root";
            $pass="Amit@7282";
            $port=3307;
            $conn=new pdo("mysql:host=$host;port=$port;dbname=$dbname",$user,$pass);
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
            die();
        }
        $stm="select * from login where username='$user1'";
        $result=$conn->query($stm);
        $cnt=$result->rowCount();
        if($cnt==1)
        {
            foreach($result as $row)
            {
                if($row['password']==$pass1)
                {
                    $_SESSION['id']=$row['id'];
                    if($row['Type']=="customer")
                        header("Location:client_myprojects.php");
                    else if($row['Type']=="contractor")
                        header("Location:contractor_allprojects.php");
                    else if($row['Type']=="worker")
                        header("Location:worker_myprojects.php");
                }
                else
                    $flag=2;
            }
        }
        else
            $flag=1;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signin page</title>
    <link rel="stylesheet" href="../css/signin.css">
</head>
<body>
    <div class="login1">
        <h1>Welcome To Website</h1>
        <section class="sign">
            <div class="mini_header">Need an account</div>
            <div class="signup"><a href="choice.php">Signup</a></div>
        </section>
        <form action="<?= $_SERVER['PHP_SELF']?>" method="POST">
        <section class="login">
            <h2>Signin</h2>
            <label for="" class="user">Username :</label>
            <input type="text" class="userfield" name="username" required 
            <?php 
                if(isset($flag))
                {
                    echo "value=".$_POST['username'];
                    if($flag==1)
                    {
                        echo " style='border : red 2px solid;'";
                    } 
                } 
            ?>>
            <?php
            if(isset($flag))
                if($flag==1)
                    echo"<label class='user' style='color : red'>Invalid Username</label>";
            ?>
            <label for="" class="user">Password :</label>
            <input type="password" class="userfield" name="password" required <?php if(isset($flag))if($flag==2)echo "style='border : red 2px solid';"  ?>>
            <?php
            if(isset($flag))
                if($flag==2)
                    echo"<label class='user' style='color : red'>Invalid Passsword</label>"; 
            ?>
            <a href="forgot.php" class="forgot">Forgot Password?</a>
            <input type="submit" class="click" value="Signin">
        </form>
        </section>
    </div>
</body>
</html>