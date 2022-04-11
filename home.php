

<?php 
//check the user to make sure that they have access
//to the database
session_start();
include "database.php";
if (isset($_SESSION['id']) && isset($_SESSION['company_name'])) {
    
    //check to see if the user is the owner of the table or 
    //an employee of the company
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
        <title>HOME</title>
        <link rel="stylesheet" type="text/css" href="home.css">
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
        <header id="background">
            <h1 name="ostore">OSTORE</h1>
            <h1 name="company">Welcome, <?php echo $_SESSION['company_name']; ?> inc.</h1>
        </header>
        <hr class="line">
        <form name="logout" action="logout.php">
            <input name="logout" type="submit" value="LOGOUT" >
        </form>
        <button class="tablink" onclick="openPage('Home', this, 'red')"id="defaultOpen">Home</button>
        <button class="tablink" onclick="window.location='add.php'">Add Client</button>
        <button class="tablink" onclick="window.location='edit.php'">Edit Client</button>
        <button class="tablink" onclick="window.location='search.php'">Search Client</button>
        <button class="tablink" onclick = "Redirect();">Account Management</button>
        <button class="tablink" onclick="window.location='calendar.php'">Calendar</button>
        <br></br>
        <br></br>
        <br></br>
        <form action="edit.php" method="get" name="priority">
        <h2>Priority list</h2>
        <?php
                //this will show a list of all the customer that are set 
                //to priority.
                $priority = "yes";
                $table = $_SESSION['company_name'];
                $sql = "SELECT * FROM $table WHERE priority LIKE '%$priority%'";  
                echo"<h5 class='card-title'>"."---------------------------------------------------------------------------------"."</h5>";
                $runQuery = mysqli_query($conn, $sql);
                while($run = mysqli_fetch_array($runQuery)){
                    echo"<h5 class='card-title'>"."ID: ".$run["id"]. "</h5>";
                    echo"<h5 class='card-title'>"."PRIORITY: ".$run["priority"]. "</h5>";
                    echo"<h5 class='card-title'>"."CUSTOMER NAME: ".$run["customer"]. "</h5>";
                    $name = $run["customer"];
                    echo"<h5 class='card-title'>"."JOB: ".$run["job_title"]. "</h5>";
                    echo"<h5 class='card-title'>"."PAYMENT: ".$run["payment"]. "</h5>";
                    echo"<h5 class='card-title'>"."Progess: ".$run["progress"]. "</h5>";
                    echo"<h5 class='card-title'>"."PHONE NUMBER: ".$run["phone_number"]. "</h5>";
                    if($run["startDate"] != NULL){
                        echo"<h5 class='card-title'>"."START DATE: ".$run["startDate"]. "</h5>";
                        echo"<h5 class='card-title'>"."END DATE: ".$run["endDate"]. "</h5>";
                    }
                    ?>
                        <button type='submit' value="<?php echo $name; ?>" name='edit'>Update</button>
                    <?php 
                    echo"<h5 class='card-title'>"."---------------------------------------------------------------------------------"."</h5>";
                }
        ?>
    </form>
    <div id="Home" class="tabcontent">
    </div>
    <div id="Edit_Client" class="tabcontent">
    <br></br>
    </div>
    <div id="Search_Client" class="tabcontent">
    <br></br>
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
    </body>
    </html>
    <?php 
}else{
     header("Location: index.php");
     exit();
}