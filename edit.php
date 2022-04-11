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
    <title>EDIT CUSTOMER PAGE</title>
    <link rel="stylesheet" type="text/css" href="edit.css">
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
        <header id="edit">
            <h1 name="ostore">OSTORE</h1>
            <h1 name="edit">Editing a Client</h1>
        </header>
        <hr class="line">
        <form name="logout" action="logout.php">
            <input name="logout" type="submit" value="LOGOUT" >
        </form>
        <button class="tablink" onclick="window.location='home.php'">Home</button>
        <button class="tablink" onclick="window.location='add.php'">Add Client</button>
        <button class="tablink" onclick="openPage('Search_Client', this, 'red')"id="defaultOpen">Edit Client</button>
        <button class="tablink"onclick="window.location='search.php'">Search Client</button>
        <button class="tablink"  onclick = "Redirect();">Account Management</button>
        <button class="tablink" onclick="window.location='calendar.php'">Calendar</button>
        <div id="Home" class="tabcontent">
        <br></br>
        </div>
        <div id="Add_Client" class="tabcontent">
        <br></br>
        </div>
        <div id="Edit_Client" class="tabcontent">
        <br></br>
        </div>
        <div id="Search_Client" class="tabcontent">
        <br></br>
        </div>
        <div id="Account_Management" class="tabcontent">
        <br></br>
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
        <form name = "editForm" method="get" >
            <input type="text" placeholder="Please enter client's name..." name="edit">
            <br></br>
            <input type="submit" value="edit" name="submit"></input>
        </form>
        <?php
            //showing the customer info to the user

            if(empty($_GET['edit'])){
                $search = "";
            }else{
                $search = $_GET['edit'];
            }
            $table = $_SESSION['company_name'];
            $match = "SELECT * FROM $table WHERE customer LIKE '%$search%'";   
            $results = mysqli_query($conn, $match);
            $found = mysqli_num_rows($results);

            $name = "Enter Customer";
            $title = "Enter Customer";
            $num = "Enter Customer";
            $pay = "Enter Customer";
            $priority = "Enter Customer";
            $progress = "Enter Customer";
            $id = 0;
            $true = 0;
            $sDate = null;
            $eDate = null;
            
            if($found == 0){ 
                echo "<div class=\"noClient\">"."This Customer does not exist in the database! Go back, and try again."."</div>";
                $true = 1;
            }else if($search == ""){
                echo "<div class=\"noClient\">"."Please enter a client's name to edit."."</div>";
                $true = 1;
            }else{
                $sql = "SELECT * FROM $table WHERE customer LIKE '%$search%'";
                $runQuery = mysqli_query($conn, $sql);
                while($run = mysqli_fetch_array($runQuery)){
                    $name = $run["customer"];//<--- had bug; replaced from "$name = $search" -Sawyer
                    $title = $run["job_title"];
                    $num = $run["phone_number"];
                    $pay = $run["payment"];
                    $priority = $run["priority"];
                    $progress = $run["progress"];
                    $id = $run["id"];
                    $sDate = $run["startDate"];
                    $eDate = $run["endDate"];
                }
            }

            if ($priority == "yes") {
               $firstshown = "yes";
               $secondshown = "no";
            }elseif($priority == "no") {
                $firstshown = "no";
                $secondshown = "yes";
            }else{
                $firstshown = "Enter customer";
                $secondshown = "Enter customer";
            }

            if ($progress == "Pending") {
                $firstprogress = "Pending";
                $secondprogress = "Ongoing";
                $thirdprogress = "Completed";
             }elseif($progress == "Ongoing") {
                $firstprogress = "Ongoing";
                $secondprogress = "Completed";
                $thirdprogress = "Pending";
             }elseif($progress == "Completed"){
                $firstprogress = "Completed";
                $secondprogress = "Pending";
                $thirdprogress = "Ongoing";
            }else{
                $firstprogress = "Enter customer";
                $secondprogress = "Enter customer";
                $thirdprogress = "Enter customer";
            }
            
        ?>
        <form name = "form2" method="post">
            Customer Name: <input type="text" value="<?php echo $name; ?>"  name="name" required>
            <br></br>
            Job title: <input type="text"  value="<?php echo $title; ?>" name="title" required>
            <br></br>
            Customer Phone number: <input type="text"  value="<?php echo $num; ?>" name="num" required>
            <br></br>
            Payment for Job: <input type="text" value="<?php echo $pay; ?>" name="amount" required>
            <br></br>
            Priority <select name="priority" required>
                <option value=<?php echo $firstshown; ?>><?php echo $firstshown; ?></option>
                <option value=<?php echo $secondshown; ?>><?php echo $secondshown; ?></option>
            </select>
            <br></br>
            Progress <select name="progress" required>
                <option value=<?php echo $firstprogress; ?>><?php echo $firstprogress; ?></option>
                <option value=<?php echo $secondprogress; ?>><?php echo $secondprogress; ?></option>
                <option value=<?php echo $thirdprogress; ?>><?php echo $thirdprogress; ?></option>
            </select>
            <br></br>
            <br></br>
            Optional----------
            <br></br>
            Start Date: <input type="date" id="start" name="start" value=<?php echo $sDate; ?>>
            <br></br>
            End Date: <input type="date" id="start" name="end" value=<?php echo $eDate; ?>>
            <br></br>
            <button type="submit" name="update">Update Information</button>
        </form>
        <?php
           //update the information when the users hits the update button

           error_reporting(E_ERROR | E_PARSE);
            
            $nameUpdate = $_POST['name'];
            $titleUpdate = $_POST['title'];
            $numberUpdate = $_POST['num'];
            $amountUpdate = $_POST['amount'];
            $priority =$_POST['priority'];
            $progressUpdate = $_POST['progress'];
            $sDate =$_POST['start'];
            $eDate = $_POST['end'];

            if(isset($_POST["update"])) 
            {
                if($true == 0){
                    if(empty($sDate) && empty($eDate)){
                        $sql = "UPDATE $table SET customer='$nameUpdate', job_title='$titleUpdate', phone_number='$numberUpdate',
                        payment='$amountUpdate', priority='$priority', progress='$progressUpdate' WHERE id =$id";
                        if(mysqli_query($conn, $sql)){
                            ?>
                                <meta http-equiv="refresh" content="0">
                            <?php 
                        } else{
                            echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
                        }
                    }else{
                        if(!empty($sDate) && !empty($eDate)){
                            if ($eDate > $sDate) {
                                $sql = "UPDATE $table SET customer='$nameUpdate', job_title='$titleUpdate', phone_number='$numberUpdate',
                                payment='$amountUpdate', priority='$priority', progress='$progressUpdate', startDate='$sDate', 
                                endDate='$eDate' WHERE id =$id";
                                if(mysqli_query($conn, $sql)){
                                    ?>
                                        <meta http-equiv="refresh" content="0">
                                    <?php 
                                } else{
                                    echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
                                }
                            }else{
                                echo "<div class=\"message\">Please have end date be after start Date!</div>";  
                            }
                        }else{
                            echo "<div class=\"message\">Please have dates for both of the dates!</div>";  
                        }
                    }
                }else{
                    echo "<div class=\"message\">Can't update due do to no current client was search!</div>"; 
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