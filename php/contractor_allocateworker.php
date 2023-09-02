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
    <title>Contractor Allocate workers to projects</title>
    <link rel="stylesheet" href="../css/contractor_allocateworker.css">
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
    <form action="<?= $_SERVER['PHP_SELF']?>" method="POST">
        <div class="search-box">
            <input type="text" name="pid" placeholder="Enter Project id">
            <input type="submit" value="Click">
        </div>
    </form>
    <?php
        if(isset($_POST['no']))
        {
            $no=$_POST['no'];
            $skill=$_POST['skill'];
            $pid=$_POST['pid'];
            $sql="update project set required_workers=$no, skill='$skill' where project_id='$pid'";
            $result4=$conn->prepare($sql);
            $result4->execute();
        }
        if(isset($_POST['wid']))
        {
            $wid=$_POST['wid'];
            $cid=$_POST['cid'];
            $pid=$_POST['pid'];
            $date=date("Y-m-d");
            $lid=rand(100000,1000000);
            $lid1="PW".$lid;
            while(1)
            {
                $stm="select * from project_worker where alloc_id='$lid1'";
                $result=$conn->query($stm);
                $cnt=$result->rowCount();
                if($cnt==0)
                    break;
                else
                {
                    $lid=rand(100000,1000000);
                    $lid1="PW".$lid;
                }
            }
            $sql="insert into project_worker values('$lid1','$pid','$wid','$cid','$date')";
            $result5=$conn->prepare($sql);
            $result5->execute();

            $sql="update worker set status='not available' where w_id='$wid'";
            $result6=$conn->prepare($sql);
            $result6->execute();
        
            $cnt=100;
            $stm="select total_workers from project where project_id='$pid'";
            $result7=$conn->query($stm);
            foreach($result7 as $row1)
                $cnt=$row1[0];
            if($cnt==0)
            {
                $sql="update project set start_date='$date' where project_id='$pid'";
                $result6=$conn->prepare($sql);
                $result6->execute();
            }

            $sql="update project set total_workers=total_workers+1 where project_id='$pid'";
            $result6=$conn->prepare($sql);
            $result6->execute();
            echo "<script type='text/javascript'>
                    alert('Worker Allocated successfully');
                </script>";
        }
    ?>
    <?php
        if(isset($_POST['pid']))
        {
            $pid=$_POST['pid'];
            $stm1="select * from Project where project_id='$pid'";
            $result1=$conn->query($stm1);
            foreach($result1 as $row)
            {
                if($row['required_workers']==0)
                {
                    $task=explode(",",$row['tasks']);
                    $cnt=count($task);
                    echo"<div class='cont'>
                            <form action='contractor_allocateworker.php' method='POST'>
                            <div class='sub-cont'>
                                <div class='sub-cont1'>
                                    <h3>Project ID : ".$row['project_id']."</h3>
                                    <h3>Project Type : ".$row['project_type']."</h3>
                                    <h3>Work Type : ".$row['work_type']."</h3>
                                    <h3>Location : ".$row['project_loc']."</h3>
                                    <h3>Amount : ".$row['amount']." </h3>
                                    <h3>Start Date : ".$row['exstart_date']."</h3>
                                    <h3>End Date : ".$row['excompleted_date']."</h3>
                                    <h3>Total Tasks : $cnt</h3>
                                </div>
                                <div class='sub-cont1'>
                                    <h3>Tasks:</h3>";
                                    for($i=1;$i<=count($task);$i++)
                                    {
                                        echo "<h3>$i. ".$task[$i-1]."</h3>";
                                    }
                            echo"</div>
                            </div>
                            <div class='sub-cont'>
                                <input type='text' name='no' placeholder='No. of workers'>
                                <input type='text' name='skill' placeholder='workers skill'>
                                <input type='hidden' name='pid' value='$pid'>
                                <input type='submit' value='Submit' class='submit'>
                            </div>
                            </form>
                        </div>";
                }
                else if($row['required_workers'] > $row['total_workers'])
                {
                    $task=explode(",",$row['tasks']);
                    $cnt=count($task);
                    echo"<div class='cont'>
                            <form action='contractor_allocateworker.php' method='POST'>
                            <div class='sub-cont'>
                                <div class='sub-cont1'>
                                    <h3>Project ID : ".$row['project_id']."</h3>
                                    <h3>Project Type : ".$row['project_type']."</h3>
                                    <h3>Work Type : ".$row['work_type']."</h3>
                                    <h3>Location : ".$row['project_loc']."</h3>
                                    <h3>Amount : ".$row['amount']." </h3>
                                    <h3>Start Date : ".$row['exstart_date']."</h3>
                                    <h3>End Date : ".$row['excompleted_date']."</h3>
                                    <h3>Total Tasks : $cnt</h3>
                                    <h3>Allocated Workers : ".$row['total_workers']."</h3>
                                    <h3>Required Workers : ".$row['required_workers']."</h3>
                                </div>
                                <div class='sub-cont1'>
                                    <h3>Tasks:</h3>";
                                    for($i=1;$i<=count($task);$i++)
                                    {
                                        echo "<h3>$i. ".$task[$i-1]."</h3>";
                                    }
                            echo"</div>
                            </div>
                            <div class='sub-cont'>
                                <input type='text' name='no' placeholder='No. of workers' value=".$row['required_workers'].">
                                <input type='text' name='skill' placeholder='workers skill' value=".$row['skill'].">
                                <input type='hidden' name='pid' value='$pid'>
                                <input type='submit' value='Submit' class='submit'>
                            </div>
                            </form>
                        </div>";
                        $skill=$row['skill'];
                        $stm2="select * from worker where c_id='$id' and status='available' and skill='$skill'";
                        $result2=$conn->query($stm2);
                        foreach($result2 as $row)
                        {
                            echo"<div class='cont2'>
                                    <form action='contractor_allocateworker.php' method='POST'>
                                        <a href='contractor_workerinfo.php' target='_blank'><b>".$row['w_id']."</b></a>
                                        <h3>".$row['name']."</h3>
                                        <h3>".$row['skill']."</h3>
                                        <input type='hidden' name='wid' value='".$row['w_id']."'>
                                        <input type='hidden' name='pid' value='".$pid."'>
                                        <input type='hidden' name='cid' value='".$row['c_id']."'>
                                        <input type='submit' value='Allocate'>
                                    </form>
                                </div>";
                        }
                }
            }
        }
    ?>
</body>
</html>