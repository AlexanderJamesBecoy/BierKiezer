<?php

	session_start();
	include_once('core/connection.php');
	include_once('core/functions.php');

	if(!isset($_SESSION['gebruikerscode']) || $_SESSION['admin'] < 1) {
	  header("Location: index.php");
	}

  if(isset($_SESSION['gebruikerscode'])) {
    $userSelf = $_SESSION['gebruikerscode'];
  }

$brewCode = $_GET['bid'];
$sql = "SELECT * FROM brouwer WHERE brouwcode = '$brewCode'";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($res);
$brewName = $row['naam'];
$brewCountry = $row['land'];

if(isset($_POST['submit'])) {
	if($_POST['naam'] === NULL || $_POST['naam'] === "") {
		$newBrewName = $brewName;
	} else {
		$newBrewName = strtoupper($_POST['naam']);
	}

	if($_POST['land'] === NULL || $_POST['land'] === "") {
		$newBrewCountry = $brewCountry;
	} else {
		$newBrewCountry = strtoupper($_POST['land']);
	}

	$sql = "UPDATE brouwer SET naam = '$newBrewName', land = '$newBrewCountry' WHERE brouwcode = '$brewCode'";
	$res = mysqli_query($conn, $sql);
	header("Location: view_brewery.php?bid=$brewCode");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>BierKiezer - <?php echo uppercase($brewName); ?> - Wijzigen</title>
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
            <li><a href='edit_profile.php?user=<?php echo $userSelf; ?>'>Profiel wijzigen</a></li>
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
        <table class="table-striped col-md-12">
					<tr>
						<th colspan="2"><h3 class="col-sm-offset-1 col-sm-10"><?php echo $brewName; ?> - Originele Gegevens</h3></th>
					</tr>
          <tr>
            <td><h4 class="col-sm-offset-2">Naam</h4></td>
						<td><h4><?php echo uppercase($brewName); ?></h4></td>
          </tr>
          <tr>
            <td><h4 class="col-sm-offset-2">Land</h4></td>
            <td><h4><?php echo uppercase($brewCountry); ?></h4></td>
          </tr>
        </table>
      </div>
      <div class="row">
				<form action="edit_brewery.php?bid=<?php echo $brewCode; ?>" method="post">
	        <table class="table-striped col-md-12">
						<tr>
							<th colspan="2"><h3 class="col-sm-offset-1 col-sm-10"><?php echo $brewName; ?> - Wijzigen</h3></th>
						</tr>
	          <tr>
	            <td><h4 class="col-sm-offset-2">Naam</h4></td>
							<td><input type="text" class="form-control" name="naam" value="<?php echo uppercase($brewName); ?>"></td>
	          </tr>
	          <tr>
	            <td><h4 class="col-sm-offset-2">Land</h4></td>
	            <td><input type="text" class="form-control" name="land" value="<?php echo $brewCountry; ?>"></td>
	          </tr>
						<tr>
							<td colspan="2"><input  class="form-control" type="submit" name="submit" value="Opslaan"></td>
						</tr>
	        </table>
				</form>
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
