<?php
    session_start();
    $custid=$_SESSION['id'];
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
    $custid=$_SESSION['id'];
    $stm="select name from customer where cust_id='$custid'";
    $result=$conn->query($stm);
    foreach($result as $row1)
        $name=$row1[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>client Allocate Project</title>
    <link rel="stylesheet" href="../css/client_allocateproject.css">
</head>
<body>
    <div class="navbar">
        <div class="user_name">
            <img src="../images/user_icon.jpg">
            <label class="name">Welcome<br><?php echo $name; ?></label>
        </div>
        <ul>
            <li><a href="client_myprojects.php">My Projects</a></li>
            <li><a href="client_addproject.php">Add New Project</a></li>
            <li><a href="client_allocateproject.php" class="selected">Allocate Project</a></li>
            <li><a href="client_report.php">Project Report</a></li>
            <li><a href="home.php">Log Out</a></li>
        </ul>
    </div>
    <form action="<?= $_SERVER['PHP_SELF']?>" method="POST">
        <div class="search-box">
            <input type="text" name="pid" placeholder="Enter Project id">
            <input type="submit" value="Click">
        </div>
    </form>
    <?php
        if(isset($_POST['pid']))
        {
            $pid=$_POST['pid'];
            $stm1="select * from Project where project_id='$pid' and c_id='0'";
            $result1=$conn->query($stm1);
            foreach($result1 as $row)
            {
                $task=explode(",",$row['tasks']);
                $cnt1=count($task);
                echo"<div class='cont'>
                        <div class='sub-cont1'>
                            <h3>Project id : ".$row['project_id']."</h3>
                            <h3>Project Type : ".$row['project_type']."</h3>
                            <h3>Work Type : ".$row['work_type']."</h3>
                            <h3>Location : ".$row['project_loc']."</h3>
                            <h3>Amount : ".$row['amount']." </h3>
                            <h3>Start Date : ".$row['exstart_date']."</h3>
                            <h3>End Date : ".$row['excompleted_date']."</h3>
                            <h3>Total Tasks : ".$cnt1."</h3>
                        </div>
                        <div class='sub-cont1'>
                            <h3>Tasks:</h3>";
                            for($i=1;$i<=count($task);$i++)
                            {
                                echo "<h3>$i. ".$task[$i-1]."</h3>";
                            }
                        echo"</div>
                    </div>";
            }

            $stm2="select * from project_bid where project_id='$pid'";
            $result2=$conn->query($stm2);
            foreach($result2 as $row)
            {
                $stm3="select name from contractor where c_id='".$row['c_id']."'";
                $result3=$conn->query($stm3);
                foreach($result3 as $row3)
                    $n=$row3[0];
                echo"<div class='cont1'>
                        <form action='client_allocateproject.php' method='POST'>
                            <a href='client_contractorinfo.php' target='_blank'><b>".$row['c_id']."</b></a>
                            <h3>".$n."</h3>
                            <h3>".$row['amount']."</h3>
                            <h3>".$row['bid_date']."</h3>
                            <h3>".$row['message']."</h3>
                            <input type='hidden' name='amt' value='".$row['amount']."'>
                            <input type='hidden' name='pid' value='".$row['project_id']."'>
                            <input type='hidden' name='cid' value='".$row['c_id']."'>
                            <input type='submit' value='Allocate'>
                        </form>
                    </div>";
            }
        }

        if(isset($_POST['cid']))
        {
            $amt=$_POST['amt'];
            $c_id=$_POST['cid'];
            $pid=$_POST['pid'];
            $sql="update project set c_id='$c_id', amount='$amt' where project_id='$pid'";
            $result4=$conn->prepare($sql);
            $result4->execute();
            $sql1="delete from project_bid where project_id='$pid'";
            $result5=$conn->prepare($sql1);
            $result5->execute();
            echo "<script type='text/javascript'>
                    ('Project Allocated successfully');
                </script>";
            echo "<script>
                    document.location='client_allocateproject.php';
                </script>";
        }
    ?>
</body>
</html>