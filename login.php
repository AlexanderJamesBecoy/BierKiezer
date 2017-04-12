<?php

	session_start();
	include_once('core/functions.php');

	if(isset($_SESSION['gebruikerscode'])) {
		header("Location: index.php");
	}

	if(isset($_POST['login'])) {
		include_once('core/connection.php');
		$email = strip_tags($_POST['email']);
		$password = strip_tags($_POST['password']);

		$email = stripslashes($email);
		$password = stripslashes($password);

		$email = mysqli_real_escape_string($conn, $email);
		$password = mysqli_real_escape_string($conn, $password);

		$password = md5($password);

		$sql = "SELECT * FROM gebruiker WHERE email='$email' LIMIT 1";
		$query = mysqli_query($conn, $sql);
		$row = mysqli_fetch_array($query);
		$eerstenaam = $row['eerstenaam'];
		$gebruikerscode = $row['gebruikerscode'];
		$db_password = $row['password'];
		$admin = $row['admin'];

		if($password == $db_password) {
			$_SESSION['eerstenaam'] = $eerstenaam;
			$_SESSION['gebruikerscode'] = $gebruikerscode;
			if($admin == 2) {
				$_SESSION['admin'] = 2;}
			if($admin == 1) {
				$_SESSION['admin'] = 1;}
			header("Location: index.php");
		}
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>BierKiezer - Inloggen</title>
	<link rel="icon" type="image/png" href="img/beer-icon.png">

  <!--Styling-->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap-select.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>

<!--Navigation Menu-->
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <a href="index.php" class="navbar-brand">BierKiezer</a>
    </div>

    <ul class="nav navbar-nav">
			<li><a href="index.php">Home</a></li>
      <li><a href="beer.php">Bier</a></li>
      <li><a href="bar.php">Kroegen</a></li>
      <li><a href="brewery.php">Brouwers</a></li>
			<?php

      if(!isset($_SESSION['admin']) || $_SESSION['admin'] < 1) {
        echo "<li><a href='premium.php'>Ga Premium!</a></li>";
      }

      ?>
    </ul>

		<form class="navbar-form navbar-left" name="search" action="search.php?go>" method="get">
      <div class="input-group">
        <select name="sortby" class="selectpicker" data-width="auto">
          <option value="searchbeer">Bier</option>
          <option value="searchkroeg">Kroeg</option>
          <option value="searchbrew">Brouwer</option>
          <option value="searchuser">Gebruiker</option>
        </select>
        <input type="text" name="name" class="form-control" placeholder="Zoek op...">
        <div class="input-group-btn">
          <button class="btn btn-default input-btn" type="submit">
            <i class="glyphicon glyphicon-search"></i>
          </button>
        </div>
      </div>
    </form>
    <ul class="nav navbar-nav navbar-right">
			<?php
      if(isset($_SESSION['eerstenaam'])) {
        if(isset($_SESSION['admin']) && $_SESSION['admin'] == 2) {
          echo "<li><div class='navbar-text'>Goedendag, beheerder!</div></li>";
        } else {
          echo "<li><div class='navbar-text'>Goedendag, ".$_SESSION['eerstenaam']."!</div></li>";
        }
        ?>
        <li class='dropdown'>
          <a class='dropdown-toggle' data-toggle='dropdown' href='#'><?php echo $_SESSION['eerstenaam']; ?>
          <span class='caret'></span></a>
          <ul class='dropdown-menu'>
            <li><a href='view_profile.php?user=<?php echo $userSelf; ?>'>Profiel bekijken</a></li>
						<?php if(isset($_SESSION['admin'])) { ?>
              <li><a href='own_data.php'>Data bekijken</a></li>
            <?php } ?>
            <li><a href='edit_profile.php'>Profiel wijzigen</a></li>
          </ul>
        </li>
        <li><a href='logout.php'>Logout</a></li>
        <?php
      } else {
      echo "<li><div class='navbar-text'>Goedendag, bezoeker!</div></li>";

      ?>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Inloggen <span class="caret"></span></a>
          <ul id="login-dp" class="dropdown-menu">
            <li>
              <div class="row">
                <div class="col-md-12">
                  <form class="form" role="form" method="post" action="login.php" name="login" id="login-nav">
                    <div class="form-group">
                      <label class="sr-only" for="email">Email-adres</label>
                      <input type="email" class="form-control" name="email" placeholder="Email-adres" required>
                    </div>
                    <div class="form-group">
                      <label class="sr-only" for="password">Wachtwoord</label>
                      <input type="password" class="form-control" name="password" placeholder="Wachtwoord" required>
                    </div>
                    <div class="form-group">
                      <button type="submit" name="login" class="btn btn-primary btn-block">Inloggen</button>
                    </div>
                    <div class="checkbox">
                      <label>
                      <input type="checkbox"> houd me ingelogd
                      </label>
                    </div>
                  </form>
                </div>
              <div class="bottom text-center">
                Ben je nieuw hier? <a href="register.php"><b>Meld je aan!</b></a>
              </div>
            </div>
          </li>
        </ul>
      </li>
      <?php
    }
      ?>
    </ul>
  </div>
</nav>

<div class="container">
  <div class="row">
    <div class="col-md-12">
			<h1 class="text-center">Login</h1>
			<hr>
			<form class="form-horizontal" action="login.php" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label class="control-label col-sm-2" for="email">Email-adres: </label>
					<div class="col-sm-10">
						<input placeholder="Email Address" name="email" type="text" class="form-control" autofocus>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="password">Wachtwoord: </label>
					<div class="col-sm-10">
						<input placeholder="Password" name="password" type="password" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<div class="checkbox">
							<label><input type="checkbox"> Remember me</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button name="login" type="submit" class="btn btn-default">Login</button>
					</div>
				</div>
			</form>
			<br/>
				<?php

				if(isset($_POST['login'])) {
					if($password != $db_password) {
						echo "<h3 class='text-center text-danger'>Incorrect combination!</h3>";
					}
				}

				?>
    </div>
  </div>
</div>

</body>

<!--Scripts-->
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-select.min.js"></script>
</html>
