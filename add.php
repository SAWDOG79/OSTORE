<?php 
session_start();
include "database.php";
if (isset($_SESSION['id']) && isset($_SESSION['company_name'])) {
    $mu;
    $userID = $_SESSION['id'];
    $match = "SELECT MU FROM accounts WHERE id LIKE '%$userID%'";   
    $runQuery = mysqli_query($conn, $match);
    $run = mysqli_fetch_array($runQuery);
    if($run["MU"] == "YES"){
        $mu = "YES";
    }else{
        $mu = "NO";
    }
   ?>
<!DOCTYPE html> 
<html>
<head>
    <title>ADD NEW CUSTOMER PAGE</title>
    <link rel="stylesheet" type="text/css" href="add.css">
    </head>
        <body>
        <script>
            //determine which account page the user will go to
            //depending on if they are the owner of the table
            //of an employee of the company
            function Redirect(){
                var user = '<?=$mu?>';
                if(user === "YES"){
                    window.location = "ownerAccount.php";
                }else{
                    window.location = "employeeAccount.php";
                }
            }
        </script>  
        <header id="add">
            <h1 name="ostore">OSTORE</h1>
            <h1 name="add">Adding a  new Client</h1>
        </header>
        <hr class="line">
        <form name="logout" action="logout.php">
            <input name="logout" type="submit" value="LOGOUT" >
        </form>
        <button class="tablink" onclick="window.location='home.php'">Home</button>
        <button class="tablink" onclick="openPage('Add_Client', this, 'red')"id="defaultOpen">Add Client</button>
        <button class="tablink"onclick="window.location='edit.php'">Edit Client</button>
        <button class="tablink" onclick="window.location='search.php'">Search Client</button>
        <button class="tablink"  onclick = "Redirect();">Account Management</button>
        <button class="tablink" onclick="window.location='calendar.php'">Calendar</button>
        <div id="Home" class="tabcontent">
        </div>
        <div id="Add_Client" class="tabcontent">
        </div>
        <div id="Edit_Client" class="tabcontent">
        <br></br>
        </div>
        <div id="Search_Client" class="tabcontent">
        </div>
        <div id="Account_Management" class="tabcontent">
        </div>
        <script>
            function openPage(pageName, elmnt, color) {
            // Hide all elements with class="tabcontent" by default */
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            // Remove the background color of all tablinks/buttons
            tablinks = document.getElementsByClassName("tablink");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].style.backgroundColor = "";
            }
            // Show the specific tab content
            document.getElementById(pageName).style.display = "block";
            // Add the specific color to the button used to open the tab content
            elmnt.style.backgroundColor = color;
            }
            // Get the element with id="defaultOpen" and click on it
            document.getElementById("defaultOpen").click();
        </script>
        <form name="form2" method="post">
            Customer Name: <input type="text" placeholder="Customer Name...." name="name" required>
            <br></br>
            Job title: <input type="text" placeholder="Job title...." name="title" required>
            <br></br>
            Customer Phone number: <input type="text" placeholder="Phone number...." name="num" required>
            <br></br>
            Payment for Job: <input type="text" placeholder="Amount...." name="amount" required>
            <br></br>
            Priority <select name="priority">
                <option value="">Select...</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
            <br></br>
            Progress <select name="progress">
               <option value="">Select...</option>
                <option value="Pending">Pending</option>
                <option value="Ongoing">Ongoing</option>
               <option value="Completed">Completed</option>
            </select>
            Optional---------
            <br></br>
            Start Date: <input type="date" id="start" name="start">
            <br></br>
            End Date: <input type="date" id="start" name="end">
            <br></br>
            <button type="submit" name="add">Save Information</button>
        </form>
        <?php
            error_reporting(E_ERROR | E_PARSE);

            //table that that is connected to the customer
            $table = $_SESSION['company_name'];

            //variables from the user
            $name = $_POST['name'];
            $title = $_POST['title'];
            $num= $_POST['num'];
            $amount = "$".$_POST['amount'];
            $priority =$_POST['priority'];
            $progress = $_POST['progress'];
            $sDate =$_POST['start'];
            $eDate = $_POST['end'];

            if(isset($_POST["add"]))
            {
                if(empty($sDate) && empty($eDate)){
                    $sql = "INSERT INTO $table (priority, job_title, customer, payment, progress, phone_number) 
                    VALUES ('$priority', '$title', '$name', '$amount', '$progress', '$num')";
                    if(mysqli_query($conn, $sql)){
                        echo "<div class=\"add\">Records inserted successfully.</div>";
                        header("Location: home.php");
                    } else{
                        echo "<div class=\"error\">ERROR: Could not able to execute $sql. </div>" . mysqli_error($conn);
                    }
                }else{
                    if(!empty($sDate) && !empty($eDate)){
                        if ($eDate > $sDate) {
                            $sql = "INSERT INTO $table (priority, job_title, customer, payment, progress, phone_number, startDate, endDate) 
                            VALUES ('$priority', '$title', '$name', '$amount', '$progress', '$num', '$sDate', '$eDate')";
                            if(mysqli_query($conn, $sql)){
                                echo "<div class=\"add\">Records inserted successfully.</div>";
                                header("Location: home.php");
                            } else{
                                echo "<div class=\"error\">ERROR: Could not able to execute $sql. </div>" . mysqli_error($conn);
                            }
                        }else{
                            echo "<div class=\"error\">Please have end date be after start Date!</div>";  
                        }
                    }else{
                        echo "<div class=\"error\">Please have dates for both of the dates!</div>";  
                    }
                }
            }

        ?>
        </body>
</html>
<?php 
}else{
     header("Location: index.php");
     exit();
}
?>