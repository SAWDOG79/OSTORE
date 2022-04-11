<?php 
session_start();
include "database.php";
include "calendar_function.php";
$calendar = new Calendar();
if (isset($_SESSION['id']) && isset($_SESSION['company_name'])) {

    error_reporting(E_ERROR | E_PARSE);
    
    //popluate month for calendar
    //$calendar->add_event("Test",3-17-2022,3,'red');

    //get the last date and the first date of the current moth
    $lastdate = date('m-t-Y');
    $firstDate = date('m-01-Y');

    //get the start date of the job 
    $table = $_SESSION['company_name'];
    $match = "SELECT startDate FROM $table";   
    $results = mysqli_query($conn, $match);
    $found = mysqli_num_rows($results);  
    if($found == 0){ 
        echo "<div class=\"noClient\">"."Error!."."</div>";
    }else{
        $sql = "SELECT * FROM $table"; 
        $runQuery = mysqli_query($conn, $sql);
        while($run = mysqli_fetch_array($runQuery)){
            $startDate = $run["startDate"];
            $endDate = $run["endDate"];
            if(strtotime($startDate) > strtotime($firstDate)){
                if(strtotime($lastDate) < strtotime($startDate)){
                    $calendar->add_event("Start: ".$run["job_title"], $startDate);
                }
            }
            if(strtotime($endDate) > strtotime($firstDate)){
                if(strtotime($lastDate) < strtotime($endDate)){
                    $calendar->add_event("End: ".$run["job_title"], $endDate);
                }
            }
        }
    }





    //check to see if it users is master user or not
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
    <title>CALENDAR PAGE</title>
    <link rel="stylesheet" type="text/css" href="calendar.css">
    <link rel="stylesheet" type="text/css" href="calendar_function.css">
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
        <header id="calendar">
            <h1 name="ostore">OSTORE</h1>
            <h1 name="calendar">Calendar</h1>
        </header>
        <hr class="line">
        <form name="logout" action="logout.php">
            <input name="logout" type="submit" value="LOGOUT" >
        </form>

        <button class="tablink" onclick="window.location='home.php'">Home</button>
        <button class="tablink" onclick="window.location='add.php'">Add Client</button>
        <button class="tablink" onclick="window.location='edit.php'">Edit Client</button>
        <button class="tablink" onclick="window.location='search.php'">Search Client</button>
        <button class="tablink" onclick = "Redirect();">Account Management</button>
        <button class="tablink" onclick="openPage('Home', this, 'red')"id="defaultOpen">Calendar</button>
        <br></br>
        <br></br>
        <br></br>

        <head>
            <meta charset="utf-8">
            <title>Event Calendar</title>
            <link href="style.css" rel="stylesheet" type="text/css">
            <link href="calendar.css" rel="stylesheet" type="text/css">
	    </head>

        <body>
            <nav class="navtop">
                <div>
                    <h1>Event Calendar</h1>
                </div>
            </nav>
            <div class="content home">
                <?=$calendar?>
            </div>
        </body>
        
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

        <div id="Calendar" class="tabcontent">

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
?>