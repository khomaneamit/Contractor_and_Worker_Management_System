<?php
    include('valid.php');
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
    $stm1="select * from project where c_id='0'";
    $result1=$conn->query($stm1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contractor find project</title>
    <link rel="stylesheet" href="../css/contractor_findproject.css">
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
    <div class="project">
        <div class="new">
            <label class="head1">New Projects</label>
            <div class="blocks">
                <?php
                    foreach($result1 as $row)
                    {
                        $stm2="select * from project_bid where project_id='".$row['project_id']."' and c_id='".$id."'";
                        $result2=$conn->query($stm2);
                        $cnt=$result2->rowCount();
                        if($cnt==0)
                        {
                            echo"<div class='inner-div'>
                                <form action='contractor_findproject.php' method='POST'>
                                    <h3>Project Type : ".$row['project_type']."</h3>
                                    <h3>Work Type : ".$row['work_type']."</h3>
                                    <h3>Location : ".$row['project_loc']."</h3>
                                    <h3>Amount : ".$row['amount']." </h3>
                                    <h3>Start Date : ".$row['exstart_date']."</h3>
                                    <h3>End Date : ".$row['excompleted_date']."</h3>
                                    <h3>Total Tasks : ".$row['total_tasks']."</h3>
                                    <input type='text' name='amount' placeholder='Enter Amount'>
                                    <textarea name='desc' class='text'></textarea>
                                    <input type='hidden' name='pid' value='".$row['project_id']."'>
                                    <input type='submit' value='Submit' class='submit'> 
                                </form>
                            </div>";
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</body>
</html>


<?php
    if(isset($_POST['amount']))
    {
        $amt=$_POST['amount'];
        $i=validate_number($amt);
        if($i==1)
        {
            echo "<script type='text/javascript'>
                        alert('Enter Amount (Greater than 0)');
                    </script>";
        }
        else
        {
            $bid=rand(100000,1000000);
            $bid1="bid".$bid;
            while(1)
            {
                $stm3="select * from project_bid where bid_id='$bid1'";
                $result=$conn->query($stm3);
                $cnt=$result->rowCount();
                if($cnt==0)
                    break;
                else
                {
                    $bid=rand(100000,1000000);
                    $bid1="bid".$bid;
                }
            }
            $msg=$_POST['desc'];
            $bdate=date("Y-m-d");
            $status="pending";
            $pid=$_POST['pid'];
            
            $sql="insert into project_bid values('$bid1','$msg','$bdate','$status','$pid','$id','$amt')";
            $r=$conn->prepare($sql);
            $r->execute();
            echo "<script type='text/javascript'>
                        alert('request sent successfully');
                    </script>";
            echo "<script>
                    document.location='contractor_findproject.php';
                </script>";
        }
    }
?>