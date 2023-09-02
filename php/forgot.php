<?php
    include('valid.php');
    if(isset($_POST['username']))
    {
        $user1=$_POST['username'];
        $sque=$_POST['squestion'];
        $ans=$_POST['answer'];
        $npass1=$_POST['npassword'];
        $cpass1=$_POST['cpassword'];
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
                if($row['Type']=="customer")
                {
                    $sql="select * from customer where security_question='$sque'";
                }
                else if($row['Type']=="contractor")
                {
                    $sql="select * from contractor where security_question='$sque'";
                }
                else if($row['Type']=="worker")
                {
                    $sql="select * from worker where security_question='$sque'";
                }
                $result1=$conn->query($sql);
                $cnt1=$result1->rowCount();
                if($cnt1==1)
                {
                    foreach($result1 as $row1)
                    {
                        if($row1['answer']==$ans)
                        {
                            if(validate_password($npass1)==0)
                            {
                                if($npass1==$cpass1)
                                {
                                    if($row['Type']=="customer")
                                    {
                                        $sql1="update customer set password='$npass1' where cust_id='".$row1[0]."'";
                                    }
                                    else if($row['Type']=="contractor")
                                    {
                                        $sql1="update contractor set password='$npass1' where cust_id='".$row1[0]."'";
                                    }
                                    else if($row['Type']=="worker")
                                    {
                                        $sql1="update contractor set password='$npass1' where cust_id='".$row1[0]."'";
                                    }
                                    $rs=$conn->prepare($sql1);
                                    $rs->execute();
                                    $sql2="update login set password='$npass1' where id='".$row1[0]."'";
                                    $rs1=$conn->prepare($sql2);
                                    $rs1->execute();
                                    echo "<script type='text/javascript'>
                                                alert('Password Updated Successfully');
                                            </script>";
                                    echo "<script>
                                            document.location='home.php';
                                        </script>";
                                }
                                else
                                    $flag=5;
                            }
                            else
                                $flag=4;
                        }
                        else
                        $flag=3;
                    }
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
    <title>forgot password page</title>
    <link rel="stylesheet" href="../css/signin.css">
</head>
<body>
    <div class="login1">
        <form action="<?= $_SERVER['PHP_SELF']?>" method="POST">
        <section class="login">
            <h2>Forgot Password</h2>
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
            <label class="user">Security Question :</label>
                            <select name="squestion" class="userfield">
                                <option value="What is the name of your first pet?">What is the name of your first pet?</option>
                                <option value="What was your first car?">What was your first car?</option>
                                <option value="Who was your childhood hero?">Who was your childhood hero?</option>
                                <option value="what is the name of the town where you were born?">what is the name of the town where you were born?</option>
                            </select
            <?php 
                if(isset($flag))
                {
                    if($flag==2)
                    {
                        echo " style='border : red 2px solid;'";
                    } 
                } 
            ?>>
            <?php
            if(isset($flag))
                if($flag==2)
                    echo"<label class='user' style='color : red'>Invalid Security Question</label>";
            ?>
            <label for="" class="user">Answer :</label>
            <input type="text" class="userfield" name="answer" required 
            <?php 
                if(isset($flag))
                {
                    echo "value=".$_POST['answer'];
                    if($flag==3)
                    {
                        echo " style='border : red 2px solid;'";
                    } 
                } 
            ?>>
            <?php
            if(isset($flag))
                if($flag==3)
                    echo"<label class='user' style='color : red'>Invalid Answer</label>";
            ?>
            <label for="" class="user">New Password :</label>
            <input type="password" class="userfield" name="npassword" required <?php if(isset($flag))if($flag==4)echo "style='border : red 2px solid';"  ?>>
            <?php
            if(isset($flag))
                if($flag==4)
                    echo"<label class='user' style='color : red'>Password contain 1 upper, lower and number & lenght<8</label>"; 
            ?>
            <label for="" class="user">Confirm Password :</label>
            <input type="password" class="userfield" name="cpassword" required <?php if(isset($flag))if($flag==5)echo "style='border : red 2px solid';"  ?>>
            <?php
            if(isset($flag))
                if($flag==5)
                    echo"<label class='user' style='color : red'>Passsword cannot match</label>"; 
            ?>
            <input type="submit" class="click" value="Submit">
        </form>
        </section>
    </div>
</body>
</html>