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
    <title>Contractor Update Project Progress</title>
    <link rel="stylesheet" href="../css/contractor_updateprogress.css">
</head>
<body>
    <div class="navbar">
        <div class="user_name">
            <img src="../images/user_icon.jpg">
            <label class="name">Welcome<br><?php echo $name; ?></label>
        </div>
        <ul class="menu">
            <li class="selected"><a href="#">Project</a>
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
        if(isset($_POST['cnt']))
        {
            $pid=$_POST['pid'];
            $cnt=$_POST['cnt'];
            $ch=array();
            $j=0;
            for($i=0;$i<$cnt;$i++)
            {
                $ch1="ch".$i+1;
                if(isset($_POST[$ch1]))
                {
                    $ch[$i]=1;
                    $j++;
                }
                else
                    $ch[$i]=0;
            }
            $ch2=implode(",",$ch);
            if($j!=$cnt)
                $sql="update project set completed_tasks='$ch2', total_tasks=$j where project_id='$pid'";
            else
            {
                $da=date("Y-m-d");
                $sql="update project set completed_tasks='$ch2', total_tasks=$j, status='completed', completed_date='$da' where project_id='$pid'";
                $sql1="update worker set status='available' where w_id IN (select w_id from project_worker where work_id='$pid')";
                $result7=$conn->prepare($sql1);
                $result7->execute();
            }
            $result6=$conn->prepare($sql);
            $result6->execute();
            echo "<script type='text/javascript'>
                    alert('Project progress updated');
                </script>";
            echo "<script>
                document.location='contractor_allprojects.php';
            </script>";
        }
    ?>
    <?php
        if(isset($_POST['pid']))
        {
            $pid=$_POST['pid'];
            $stm1="select * from Project where project_id='$pid' and status='not completed' and required_workers=total_workers and c_id='$id'";
            $result1=$conn->query($stm1);
            foreach($result1 as $row)
            {
                if($row['completed_tasks']=='0')
                {
                    $task=explode(",",$row['tasks']);
                    $cnt=count($task);
                    $per=($row['total_tasks']/$cnt)*100;
                    $p=round($per);
                    echo"<div class='cont'>
                            <form action='contractor_updateprogress.php' method='POST'>
                            <div class='sub-cont'>
                                <div class='sub-cont1'>
                                    <h3>Project ID : ".$row['project_id']."</h3>
                                    <h3>Project Type : ".$row['project_type']."</h3>
                                    <h3>Work Type : ".$row['work_type']."</h3>
                                    <h3>Location : ".$row['project_loc']."</h3>
                                    <h3>Amount : ".$row['amount']." </h3>
                                    <h3>Start Date : ".$row['start_date']."</h3>
                                    <h3>Total Workers : ".$row['total_workers']."/".$row['required_workers']."</h3>
                                    <h3>Total Tasks : $cnt</h3>
                                    <h3>Progress : $p%</h3>
                                </div>
                                <div class='sub-cont1'>
                                    <h3>Tasks:</h3>";
                                    for($i=1;$i<=count($task);$i++)
                                    {
                                        echo "<div class='task'><input type='checkbox' name='ch$i' class='checkbox' value='".$task[$i-1]."'><h3>".$task[$i-1]."</h3></div>";
                                    }
                            echo"</div>
                            </div>
                            <div class='sub-cont'>
                                <input type='hidden' name='cnt' value='$cnt'>
                                <input type='hidden' name='pid' value='$pid'>
                                <input type='submit' value='Submit' class='submit'>
                            </div>
                            </form>
                        </div>";
                }
                else
                {
                    $task=explode(",",$row['tasks']);
                    $cnt=count($task);
                    $per=($row['total_tasks']/$cnt)*100;
                    $p=round($per);
                    $ch=explode(",",$row['completed_tasks']);
                    echo"<div class='cont'>
                            <form action='contractor_updateprogress.php' method='POST'>
                            <div class='sub-cont'>
                                <div class='sub-cont1'>
                                    <h3>Project ID : ".$row['project_id']."</h3>
                                    <h3>Project Type : ".$row['project_type']."</h3>
                                    <h3>Work Type : ".$row['work_type']."</h3>
                                    <h3>Location : ".$row['project_loc']."</h3>
                                    <h3>Amount : ".$row['amount']." </h3>
                                    <h3>Start Date : ".$row['start_date']."</h3>
                                    <h3>Total Workers : ".$row['total_workers']."/".$row['required_workers']."</h3>
                                    <h3>Total Tasks : $cnt</h3>
                                    <h3>Progress : $p%</h3>
                                </div>
                                <div class='sub-cont1'>
                                    <h3>Tasks:</h3>";
                                    for($i=1;$i<=count($task);$i++)
                                    {
                                        if($ch[$i-1]==1)
                                            echo "<div class='task'><input type='checkbox' name='ch$i' class='checkbox' value='".$task[$i-1]."' checked><h3>".$task[$i-1]."</h3></div>";
                                        else
                                            echo "<div class='task'><input type='checkbox' name='ch$i' class='checkbox' value='".$task[$i-1]."'><h3>".$task[$i-1]."</h3></div>";
                                    }
                            echo"</div>
                            </div>
                            <div class='sub-cont'>
                                <input type='hidden' name='cnt' value='$cnt'>
                                <input type='hidden' name='pid' value='$pid'>
                                <input type='submit' value='Submit' class='submit'>
                            </div>
                            </form>
                        </div>";
                }
            }
        }
    ?>
</body>
</html>