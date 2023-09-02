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
    $stm="select name from worker where w_id='$id'";
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
    <title>Contractor All Project</title>
    <link rel="stylesheet" href="../css/contractor_allproject.css">
</head>
<body>
    <div class="navbar">
        <div class="user_name">
            <img src="../images/user_icon.jpg">
            <label class="name">Welcome<br><?php echo $name; ?></label>
        </div>
        <ul class="menu">
            <li class="selected"><a href="#">MyProjects</a></li>
            <li><a href="home.php">Log Out</a></li>
        </ul>
    </div>
    <div class="project">
        <div class="new">
            <label class="head1">Projects In Progress</label>
            <div class="blocks">
            <?php
                $stm1="select * from project where project_id IN (select work_id from project_worker where w_id='$id') and total_workers>0";
                $result1=$conn->query($stm1);
                    foreach($result1 as $row)
                    {
                            $task=explode(",",$row['tasks']);
                            $cnt=count($task);
                            $per=($row['total_tasks']/$cnt)*100;
                            $p=round($per);
                            if($row['total_tasks']!=$cnt)
                            {
                                if($row['total_workers']==$row['required_workers'])
                                echo"<div class='inner-div'>
                                    <form>
                                        <h3>Project ID : ".$row['project_id']."</h3>
                                        <h3>Project Type : ".$row['project_type']."</h3>
                                        <h3>Work Type : ".$row['work_type']."</h3>
                                        <h3>Location : ".$row['project_loc']."</h3>
                                        <h3>Start Date : ".$row['start_date']."</h3>
                                        <h3>Progress : $p%</h3>
                                    </form>
                                </div>";
                            }
                    }
                ?>
            </div>
        </div>
        <div class="new">
            <label class="head1">Completed Projects</label>
            <div class="blocks">
            <?php
                $stm1="select * from project where project_id IN (select work_id from project_worker where w_id='$id') and total_workers>0";
                $result1=$conn->query($stm1);
                    foreach($result1 as $row)
                    {
                            $task=explode(",",$row['tasks']);
                            $cnt=count($task);
                            $per=($row['total_tasks']/$cnt)*100;
                            $p=round($per);
                            if($row['total_tasks']==$cnt)
                            {
                                echo"<div class='inner-div'>
                                    <form>
                                        <h3>Project ID : ".$row['project_id']."</h3>
                                        <h3>Project Type : ".$row['project_type']."</h3>
                                        <h3>Work Type : ".$row['work_type']."</h3>
                                        <h3>Location : ".$row['project_loc']."</h3>
                                        <h3>Start Date : ".$row['start_date']."</h3>
                                        <h3>Completed Date : ".$row['completed_date']."</h3>
                                        <h3>Progress : $p%</h3>
                                    </form>
                                </div>";
                            }
                    }
                ?>
            </div>
            </div>
        </div>
    </div>
</body>
</html>