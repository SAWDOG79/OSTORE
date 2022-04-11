<!DOCTYPE html> 
<html>
<img src="img/Ostore.jpg"
  height= "400px">
<head>
    <title> Login Page </title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
    <form action ="login.php" method="post">
        <h2>Welcome to OSTORE!</h2>
        <?php if(isset($_GET['error'])) { ?>
            <p class="error"> <?php echo $_GET['error']; ?></p>
        <?php } ?>
        <h3>User's Name:</h3>
        <input type ="text" name="uname"  placeholder="Please enter username..."><br>
        <h3>Password:</h3>
        <input type ="password" name="password"  placeholder="Please enter passowrd..."><br>
        <a href="signup.php">New? Sign up here by clicking!</a><br>
        <button type="submit">Sign in</button>
    </form>
</body>
</html>
