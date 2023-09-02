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
    $id=$_SESSION['id'];
    $stm="select name from contractor where c_id='$id'";
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
    <title>Contractor all workers</title>
    <link rel="stylesheet" href="../css/contractor_allworkers.css">
</head>
<body>
    <div class="navbar">
        <div class="user_name">
            <img src="../images/user_icon.jpg">
            <label class="name">Welcome<br><?php echo $name; ?></label>
        </div>
        <ul class="menu">
            <li><a href="#">Project</a>
                <div class="sub-menu">
                    <ul class="menu1">
                        <li><a href="contractor_allprojects.php">All Projects</a></li>
                        <li><a href="contractor_updateprogress.php">Update Project</a></li>
                        <li><a href="contractor_findproject.php">Find Project</a></li>
                    </ul>
                </div>
            </li>
            <li class="selected"><a href="#">Worker</a>
                <div class="sub-menu">
                    <ul class="menu1">
                        <li><a href="contractor_allworkers.php">All Worker</a></li>
                        <li><a href="contractor_addworker.php">Add New Worker</a></li>
                        <li><a href="contractor_allocateworker.php">Allocate Worker</a></li>
                    </ul>
                </div>
            </li>
            <li><a href="contractor_report.php">Project Report</a></li>
            <li><a href="home.php">Log Out</a></li>
        </ul>
    </div>
    <div class="blocks">
                <?php
                    $stm1="select * from worker where c_id='$id'";
                    $result1=$conn->query($stm1);
                    foreach($result1 as $row)
                    {
                        $cnt=0;
                        $sql="select * from project_worker where w_id='".$row['w_id']."'";
                        $result2=$conn->query($sql);
                        $cnt=$result2->rowCount();
                            echo"<div class='inner-div'>
                                    <h3>Worker ID : ".$row['w_id']."</h3>
                                    <h3>Worker Name : ".$row['name']."</h3>
                                    <h3>skill : ".$row['skill']."</h3>
                                    <h3>address : ".$row['address']."</h3>
                                    <h3>status : ".$row['status']." </h3>
                                    <h3>Total Works : ".$cnt." </h3>
                            </div>";
                    }
                ?>
            </div>
</body>
</html>