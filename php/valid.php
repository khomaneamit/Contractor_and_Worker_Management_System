<?php

    function validate_password($pass)
    {
        $upper=preg_match('@[A-Z]@',$pass);
        $lower=preg_match('@[a-z]@',$pass);
        $number=preg_match('@[0-9]@',$pass);
        if(!$upper || !$lower || !$number || strlen($pass)<8)
            return 1;
        else
            return 0;
    }

    function validate_name($n)
    {
        $a=0;
        $name=explode(" ",$n);
        for($z=0;$z<count($name);$z++)
            if(!preg_match("/^[a-zA-Z]*$/",$name[$z]))
            {    
                $a=1;
                break;
            }
        if($a==1)
            return 1;
        else
            return 0;
    }

    function validate_mail($n)
    {
        if(!filter_var($n,FILTER_VALIDATE_EMAIL))
            return 1;
        else
            return 0;
    }

    function validate_phone($n)
    {
        $a=0;
        if(!preg_match("/^[0-9]*$/",$n))
        {    
            $a=1;
        }
        if($a==1 || strlen($n)!=10)
            return 1;
        else
            return 0;
    }

    function validate_birth($n)
    {
        $date1=date_create(date("Y-m-d"));
        $date2=date_create($n);
        $date=date_diff($date1,$date2);
        if($date->invert==0 || $date->y<18)
            return 1;
        else
            return 0;
    }

    function validate_expire($n)
    {
        $date1=date_create(date("Y-m-d"));
        $date3=date_create($n);
        $date=date_diff($date1,$date3);
        if($date->invert==1)
            return 1;
        else
            return 0;
    }

    function validate_username($n)
    {
        if(!preg_match("/^[a-zA-Z]/",$n))
            return 1;
        else
            return 0;
    }

    function validate_confirmp($n,$c)
    {
        if($c!=$n)
            return 1;
        else
            return 0;
    }

    function validate_compare($n,$c)
    {
        $date1=date_create($c);
        $date3=date_create($n);
        $date=date_diff($date1,$date3);
        if($date->invert==0)
            return 1;
        else
            return 0;
    }

    function validate_number($n)
    {
        $a=0;
        if(!preg_match("/^[0-9]*$/",$n))
        {    
            $a=1;
        }
        if($a==1)
            return 1;
        else
            return 0;
    }

    function account_created()
    {
        echo "<script type='text/javascript'>
                    alert('Account created successfully');
                </script>";
        echo "<script>
                document.location='home.php';
            </script>";
    }

?>