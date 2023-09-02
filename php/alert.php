<?php
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