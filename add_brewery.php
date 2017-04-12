<?php

	session_start();
	include_once('core/connection.php');
	include_once('core/functions.php');

	if(!isset($_SESSION['gebruikerscode']) || $_SESSION['admin'] < 1) {
	  header("Location: index.php");
	} else {
		$userSelf = $_SESSION['gebruikerscode'];
	}

$comment = "";

if(isset($_POST['submit'])) {
		$brewName = $_POST['naam'];
		$brewCountry = $_POST['land'];

		$sql = "INSERT INTO brouwer (naam, land, gebruikerscode) VALUES ('$brewName','$brewCountry','$userSelf')";
		$res = mysqli_query($conn, $sql);

		$sql = "SELECT brouwcode FROM brouwer WHERE naam = '$brewName' AND land = '$brewCountry' AND gebruikerscode = '$userSelf'";
		$res = mysqli_query($conn, $sql);
		$row = mysqli_fetch_array($res);
		$brewCode = $row['brouwcode'];
		$comment = "Uw brouwer is toegevoegd";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>BierKiezer - Kroeg Toevoegen</title>
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
    <div class="col-md-6">
      <!-- Foto's zijn toch niet belangrijk -->
      <div class="well well-lg">
        <img src="upload/blank-item.jpg" alt="">
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
				<?php if(!isset($_POST['submit'])) { ?>
					<form action="add_brewery.php" method="post">
		        <table class="table-striped col-md-12">
							<tr>
								<th colspan="2"><h3 class="col-sm-offset-1 col-sm-10">Nieuwe brouwer toevoegen</h3></th>
							</tr>
		          <tr>
		            <td><h4 class="col-sm-offset-2">Naam</h4></td>
								<td><input type="text" class="form-control" name="naam" placeholder="Naam"></td>
		          </tr>
		          <tr>
		            <td><h4 class="col-sm-offset-2">Land</h4></td>
		            <td><input type="text" class="form-control" name="land" placeholder="Land"></td>
		          </tr>
							<tr>
								<td colspan="2"><input  class="form-control" type="submit" name="submit" value="Opslaan"></td>
							</tr>
		        </table>
					</form>
				<?php } else { ?>
					<table class="table-striped col-md-12">
						<tr>
							<th colspan="2"><h3 class="col-sm-offset-1 col-sm-10">Nieuwe brouwer toevoegen</h3></th>
						</tr>
	          <tr>
	            <td><h4 class="col-sm-offset-2">Naam</h4></td>
							<td><h4><?php echo $brewName; ?></h4></td>
	          </tr>
						<tr>
	            <td><h4 class="col-sm-offset-2">ID</h4></td>
							<td><h4><?php echo $brewCode; ?></h4></td>
	          </tr>
						<tr>
	            <td><h4 class="col-sm-offset-2">Adres</h4></td>
							<td><h4><?php echo $brewCountry; ?></h4></td>
	          </tr>
						<tr>
							<td><h4 class="col-sm-offset-2">Uw brouwer is toegevoegd!</h4></td>
							<td>
								<a href="view_brewery.php?bid=<?php echo $brewCode; ?>" class="dataAnchor">
									<button type="button" class="btn dataBtn">Bekijk</button>
								</a>
								<a href="own_data.php" class="dataAnchor">
									<button type="button" class="btn dataBtn">Terug</button>
								</a>
							</td>
						</tr>
	        </table>
				<?php } ?>
      </div>
      <hr>
    </div>
  </div>
</div>

</body>

<!--Scripts-->
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-select.min.js"></script>
</html>
