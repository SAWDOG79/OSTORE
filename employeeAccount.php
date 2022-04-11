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
    <title>ACCOUNT CUSTOMER PAGE</title>
    <link rel="stylesheet" type="text/css" href="employeeAccount.css">
    </head>
        <body>
        <script>
            //determine which account page the user will go to
            //depending on if they are the owner of the table
            //of an employee of the company
            function check(){
                var user = '<?=$mu?>';
                if(user === "YES"){
                    window.location = "ownerAccount.php";
                }else{
                    window.location = "employeeAccount.php";
                }
            }
        </script> 
        <header id="account">
            <h1 name="ostore">OSTORE</h1>
            <h1 name="account">Manage Account</h1>
        </header>
        <hr class="line">
        <form name="logout" action="logout.php">
            <input name="logout" type="submit" value="LOGOUT" >
        </form>
        <button class="tablink" onclick="window.location='home.php'">Home</button>
        <button class="tablink" onclick="window.location='add.php'">Add Client</button>
        <button class="tablink"onclick="window.location='edit.php'">Edit Client</button>
        <button class="tablink" onclick="window.location='search.php'">Search Client</button>
        <button class="tablink"  onclick ="openPage('Search_Client', this, 'red')"id="defaultOpen">Account Management</button>
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

            function Redirect(){
                window.location = "index.php";
            }
        </script>
        <?php
            //showing the customer info to the user

            //$id = $_SESSION['id'];

            if(empty($_GET['edit'])){
                $id = $_SESSION['id'];
            }else{
                $id = $_GET['edit'];
            }
            
            $match = "SELECT * FROM accounts WHERE id LIKE '%$id%'";   
            $results = mysqli_query($conn, $match);
            $found = mysqli_num_rows($results);

            $userId = 0;
            $email = "No email";
            $fname = "No first name";
            $lname = "No last name";
            $username = "No username";
            $password = "No password";
            $rPassword = "No password";

            
            if($found == 0){ 
                echo "<div class=\"noUser\">"."This User does not exist in the database! Go back, and try again."."</div>";
            }else if($id == ""){
                echo "<div class=\"noUser\">"."This User does not exist in the database! Go back, and try again."."</div>";
            }else{
                $sql = "SELECT email, fname, lname, username, password FROM accounts WHERE id LIKE '$id'";
                $result = $conn->query($sql);
                while($run = $result->fetch_assoc()){
                    $userId = $id;
                    $email = $run["email"];
                    $fname = $run["fname"];
                    $lname = $run["lname"];
                    $username = $run["username"];
                    $password = $run["password"];
                    $rPassword = $run["password"];
                }
            }
        ?>
        <form name = "form2" method="post">
                <label for="email"><b>Email:</b></label>
                <input type="text" value="<?php echo $email; ?>" placeholder="Enter Email" name="email" required>
                <br></br>
                <label for="fname"><b>First Name:</b></label>
                <input type="text" value="<?php echo $fname; ?>" placeholder="Enter first Name" name="fname" required>
                <br></br>
                <label for="lname"><b>Last Name:</b></label>
                <input type="text" value="<?php echo $lname; ?>" placeholder="Enter last Name" name="lname" required>
                <br></br>
                <label for="uname"><b>Username:</b></label>
                <input type="text" value="<?php echo $username; ?>" placeholder="Enter Username" name="username" required>
                <h2>------------------------------------------------</h2>
                <h5>To change password please enter old password, and a new password twice.</h5>
                <label for="psw"><b>Old Password:</b></label>
                <input type="text"  placeholder="Enter Password" name="oldPsw">
                <br></br>
                <label for="psw"><b>New Password (8 characters long):</b></label>
                <input type="text"  placeholder="Enter Password" name="psw">
                <br></br>
                <label for="psw-repeat"><b>Repeat  New Password (8 characters long):</b></label>
                <input type="text"  placeholder="Repeat Password" name="psw-repeat">
            <br></br>
            <button type="submit" name="update">Update Information</button>
            <br></br>
            <button type="submit" name="remove">Delete Account</button>
        </form>
        <?php
           //update the information when the users hits the update button

           error_reporting(E_ERROR | E_PARSE);
            
            $emailUpdate = $_POST['email'];
            $fnameUpdate = $_POST['fname'];
            $lnameUpdate = $_POST['lname'];
            $unameUpdate = $_POST['username'];
            $passwordUpdate =$_POST['psw'];
            $rpwd = $_POST['psw-repeat'];
            $oldPassword = $_POST['oldPsw'];


            if(isset($_POST["update"])) 
            {
                if($true == 0){
                    $matchpassword = "SELECT * FROM accounts WHERE password LIKE '%$oldPassword%'";
                    $resultsPassword = mysqli_query($conn, $matchpassword);
                    $foundPassword = mysqli_num_rows($resultsPassword);
                    if($passwordUpdate == ""){
                        $sql = "UPDATE accounts SET email = '$emailUpdate', fname = '$fnameUpdate', lname='$lnameUpdate',
                        username ='$unameUpdate' WHERE id =$userId";
                        if(mysqli_query($conn, $sql)){
                            header("Refresh:0");
                            echo '<script type="text/javascript">check();</script>';
                        } else{
                            echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
                        }
                    }else if($foundPassword == 0){
                        echo "<div class=\"message\">Password does not match! Please try again</div>";
                    }else if(strlen($passwordUpdate) < 7 ){
                        echo "<div class=\"message\">New Pasword is to short! Please make new password 8  characters long or more.</div>"; 
                    }else if($passwordUpdate == $rpwd){
                        $sql = "UPDATE accounts SET email = '$emailUpdate', fname = '$fnameUpdate', lname='$lnameUpdate',
                        username ='$unameUpdate', password='$passwordUpdate' WHERE id =$userId";
                        if(mysqli_query($conn, $sql)){
                            header("Refresh:0");
                        } else{
                            echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
                        }
                    }else{
                        echo "<div class=\"message\">Please enter new password twice!</div>"; 
                    }
                }else{
                    echo "<div class=\"message\">Can't update due do to no current client was search!</div>"; 
                }
            }
            if(isset($_POST["remove"])){
                $sql = "DELETE FROM accounts WHERE id LIKE '$id'";
                if(mysqli_query($conn, $sql)){
                    echo '<script type="text/javascript">Redirect();</script>';
                } else{
                    echo "<div class=\"error\">ERROR: Could not able to execute $sql. </div>" . mysqli_error($conn);
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