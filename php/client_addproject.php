<?php
    include('valid.php');
    session_start();
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
    if(isset($_POST['project_type']))
    {
        $name=$_POST['project_type'];
        $add=$_POST['work_type'];
        $mail=$_POST['location'];
        $contact=$_POST['sdate'];
        $bdate=$_POST['edate'];
        $amount=$_POST['amount'];
        $lino=$_POST['task'];
        $exdate=$_POST['desc'];

        $f=validate_expire($contact);
        $g=validate_expire($bdate);
        $h=validate_compare($contact,$bdate);
        $i=validate_number($amount);

        

        if($f==0 && $g==0 && $h==0 && $i==0)
        {
            $custid=$_SESSION['id'];
            $status="not completed";
            $id=rand(1000,10000);
            $id1="W".$id;
            while(1)
            {
                $stm="select * from project where project_id='$id1'";
                $result=$conn->query($stm);
                $cnt=$result->rowCount();
                if($cnt==0)
                    break;
                else
                {
                    $id=rand(1000,10000);
                    $id1="W".$id;
                }
            }
            $stm1="insert into project(project_id,project_type,work_type,project_loc,exstart_date,excompleted_date,tasks,description,status,cust_id,c_id,amount,total_workers,required_workers,completed_tasks,total_tasks) values('$id1','$name','$add','$mail','$contact','$bdate','$lino','$exdate','$status','$custid','0','$amount',0,0,'0',0)";
            $result1=$conn->prepare($stm1);
            $result1->execute();
            echo "<script type='text/javascript'>
                        alert('Project Uploaded successfully');
                    </script>";
            echo "<script>
                    document.location='client_addproject.php';
                </script>";
        }
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
    <title>client addproject</title>
    <link rel="stylesheet" href="../css/client_addproject.css">
</head>
<body>
    <div class="navbar">
        <div class="user_name">
            <img src="../images/user_icon.jpg">
            <label class="name">Welcome<br><?php echo $name; ?></label>
        </div>
        <ul>
            <li><a href="client_myprojects.php">My Projects</a></li>
            <li><a href="client_addproject.php" class="selected">Add New Project</a></li>
            <li><a href="client_allocateproject.php">Allocate Project</a></li>
            <li><a href="client_report.php">Project Report</a></li>
            <li><a href="home.php">Log Out</a></li>
        </ul>
    </div>
    <div class="project">
        <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
            <container class="container1">
                <div class="label1">New Project</div>
                <container class="container2">
                    <container class="container3">
                        <container class="first_container">
                            <label class="container_label">Type of Project:</label>
                            <select name="project_type" class="container_input">
                                <option value="Private">Private</option>
                                <option value="Industrial">Industrial</option>
                            </select>
                        </container>
                        <conatainer class="second_container">
                        <label class="container_label">Type of Work:</label>
                            <select name="work_type" class="container_input">
                                <option value="Mechanical">Mechanical</option>
                                <option value="Electrical">Electrical</option>
                                <option value="Civil">Civil</option>
                                <option value="Chemical">Chemical</option>
                                <option value="Software">Software</option>
                            </select>
                        </conatainer>
                    </container>
                    <container class="container3">
                        <container class="first_container">
                            <label class="container_label">Work Location :</label>
                            <input type="text" name="location" class="container_input" required value="<?php if(isset($_POST['location']))echo $add;
                            ?>">
                        </container>
                        <conatainer class="second_container">
                            <label class="container_label">Start Date</label>
                            <input type="Date" name="sdate" class="container_input" required
                            <?php 
                                if(isset($f))
                                {
                                    echo "value=".$_POST['sdate'];
                                    if($f==1)
                                    {
                                        echo " style='border : red 2px solid;'";
                                    }
                                } 
                            ?>>
                            <?php
                            if(isset($f))
                                if($f==1)
                                    echo"<label class='user' style='color : red'>Select Proper start date</label>";
                            ?>
                        </conatainer>
                    </container>
                    <container class="container3">
                        <container class="first_container">
                            <label class="container_label">End Date :</label>
                            <input type="date" name="edate" class="container_input" required
                            <?php 
                                if(isset($g))
                                {
                                    echo "value=".$_POST['edate'];
                                    if($g==1 || $h==1)
                                    {
                                        echo " style='border : red 2px solid;'";
                                    }
                                } 
                            ?>>
                            <?php
                            if(isset($g))
                                if($g==1 || $h==1)
                                    echo"<label class='user' style='color : red'>Select Proper end date</label>";
                            ?>
                        </container>
                        <container class="first_container">
                            <label class="container_label">Amount :</label>
                            <input type="text" name="amount" class="container_input" required
                            <?php 
                                if(isset($i))
                                {
                                    echo "value=".$_POST['amount'];
                                    if($i==1)
                                    {
                                        echo " style='border : red 2px solid;'";
                                    }
                                } 
                            ?>>
                            <?php
                            if(isset($i))
                                if($i==1)
                                    echo"<label class='user' style='color : red'>Enter Amount (>0)</label>";
                            ?>
                        </container>
                    </container>
                    <container class="container4">
                            <label class="container_label">Tasks :</label>
                            <textarea name="task" class="text" placeholder="Enter Tasks seperated by comma(,)" required></textarea>
                    </container>
                    <container class="container4">
                            <label class="container_label">Description :</label>
                            <textarea name="desc" class="text"></textarea>
                    </container>
                </container>
                <container class="submit_reset">
                    <input type="submit" value="Submit" class="submit">
                    <input type="reset" value="Reset" class="submit">
                </container>
            </container>
        </form>
    </div>
</body>
</html>
