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
    <title> SEARCH PAGE </title>
    <link rel="stylesheet" type="text/css" href="search.css">
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
        <header id="search">
            <h1 name="ostore">OSTORE</h1>
            <h1 name="find">Searching for a Client</h1>
        </header>
        <hr class="line">
        <form name="logout" action="logout.php">
            <input name="logout" type="submit" value="LOGOUT" >
        </form>
        <button class="tablink" onclick="window.location='home.php'">Home</button>
        <button class="tablink" onclick="window.location='add.php'">Add Client</button>
        <button class="tablink" onclick="window.location='edit.php'">Edit Client</button>
        <button class="tablink" onclick="openPage('Search_Client', this, 'red')"id="defaultOpen">Search Client</button>
        <button class="tablink"  onclick = "Redirect();">Account Management</button>
        <button class="tablink" onclick="window.location='calendar.php'">Calendar</button>
        <div id="Home" class="tabcontent">
        </div>
        <div id="Add_Client" class="tabcontent">
        </div>
        <div id="Edit_Client" class="tabcontent">
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
        <form name = "searchForm" method="get" action="search.php">
            <input type="text" placeholder="Please enter client's name..." name="search">
            <br></br>
            <input type="submit" value="Search" name="submit"></input>
        </form>
        <br></br>
        <form action="edit.php" method="get" name="list">
            <?php
                if(empty($_GET['search'])){
                    $search = "";
                }else{
                    $search = $_GET['search'];
                }
                $table = $_SESSION['company_name'];
                $match = "SELECT * FROM $table WHERE customer LIKE '%$search%'";   
                $results = mysqli_query($conn, $match);
                $found = mysqli_num_rows($results);

                $name = "";

                if($found == 0){ 
                    echo"<h5 class='card-title'>"."Search Word: "."This name does not exist in the database!"."</h5>";
                }else{
                    $sql = "SELECT * FROM $table WHERE customer LIKE '%$search%'";
                    echo"<h5 class='card-title'>"."Search Word: ".$search."</h5>";
                    $runQuery = mysqli_query($conn, $sql);
                    echo"<h5 class='card-title'>"."---------------------------------------------------------------------------------"."</h5>";
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
                        $list[] = ($run["customer"]);
                        echo"<h5 class='card-title'>"."---------------------------------------------------------------------------------"."</h5>";
                    }
                }
            ?>
        </form>
</body>
</html>
<?php 
}else{
     header("Location: index.php");
     exit();
}
?>
