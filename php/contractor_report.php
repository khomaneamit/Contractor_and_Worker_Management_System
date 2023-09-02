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

    $c_id=$_SESSION['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contractor Reports</title>
    <link rel="stylesheet" href="../css/contractor_report.css">
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
            <li><a href="#">Worker</a>
                <div class="sub-menu">
                    <ul class="menu1">
                        <li><a href="contractor_allworkers.php">All Worker</a></li>
                        <li><a href="contractor_addworker.php">Add New Worker</a></li>
                        <li><a href="contractor_allocateworker.php">Allocate Worker</a></li>
                    </ul>
                </div>
            </li>
            <li class="selected"><a href="contractor_report.php">Project Report</a></li>
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
            $stm1="select * from Project where project_id='$pid' and status='completed' and required_workers=total_workers and c_id='$id'";
            $result1=$conn->query($stm1);
            foreach($result1 as $row)
            {
                    $task=explode(",",$row['tasks']);
                    $cnt=count($task);
                    $per=($row['total_tasks']/$cnt)*100;
                    $p=round($per);
                    $ch=explode(",",$row['completed_tasks']);
                    echo"<div class='cont'>
                            <form>
                            <div class='sub-cont'>
                                <div class='sub-cont1'>
                                    <h3>Project ID : ".$row['project_id']."</h3>
                                    <h3>Project Type : ".$row['project_type']."</h3>
                                    <h3>Work Type : ".$row['work_type']."</h3>
                                    <h3>Location : ".$row['project_loc']."</h3>
                                    <h3>Amount : ".$row['amount']." </h3>
                                    <h3>Start Date : ".$row['start_date']."</h3>
                                    <h3>Complete Date : ".$row['completed_date']."</h3>
                                    <h3>Total Workers : ".$row['required_workers']."</h3>
                                </div>
                                <div class='sub-cont1'>
                                    <h3>Tasks:</h3>";
                                    for($i=1;$i<=count($task);$i++)
                                    {
                                        
                                            echo "<h3>".$i.". ".$task[$i-1]."</h3>";
                                    }
                            echo"</div>
                            </div>
                            </form>
                        </div>";
            }

            $sql="select worker.name from worker,project_worker where work_id='$pid' and worker.w_id=project_worker.w_id";
            $result2=$conn->query($sql);
            $i=1;
            echo"<div class='cont cont3'>
                    <form>
                        <h3>Workers:</h3>";
            foreach($result2 as $row)
            {
                    
                echo "<h3>".$i.". ".$row[0]."</h3>";
                $i++;
            }
            echo"</form>
            </div>";
        }
    ?>
</body>
</html>