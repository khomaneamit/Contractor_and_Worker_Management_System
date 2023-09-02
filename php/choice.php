<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select an Type</title>
    <link rel="stylesheet" href="../css/choice.css">
</head>
<body>
    <form action="<?= $_SERVER['PHP_SELF']?>" method="POST">
        <container class="outer">
            <label class="choice_label"><b>Contractor and Worker <br> Management System</b></label>
            <label class="choice_label2">Select Type :</label>
            <select name="choice" class="choice_select">
                <option value="Contractor" class="choice_option">Contractor</option>
                <option value="Customer" class="choice_option">Customer</option>
            </select>
            <input type="submit" value="Submit" class="choice_submit">
        </container>
    </form>
</body>
</html>
<?php
    if(isset($_POST['choice']))
    {
        $ch=$_POST['choice'];
        if($ch=="Contractor")
            header("Location:signup_contractor.php");
        elseif($ch=="Customer")
            header("Location:signup_customer.php");
    }
?>