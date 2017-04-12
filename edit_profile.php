<?php

session_start();
include_once('core/connection.php');
include_once('core/functions.php');

if(isset($_SESSION['gebruikerscode'])) {
  $userSelf = $_SESSION['gebruikerscode'];
} else {
  header("Location: index.php");
}

$sql = "SELECT * FROM gebruiker WHERE gebruikerscode= '$userSelf' ";
$query = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($query);
$firstName = $row['eerstenaam'];
$lastName = $row['laatstenaam'];
$profilePic = $row['profielfoto'];
$location = $row['plaats'];
$birthdate = $row['geboortedatum'];

if(isset($_POST['submit'])) {
  $eerstenaam = $_POST['eerstenaam'];
  $laatstenaam = $_POST['laatstenaam'];
  $geboortedatum = $_POST['geboortedatum'];
  $woonplaats = $_POST['woonplaats'];

  $sql = "UPDATE gebruiker SET eerstenaam = '$eerstenaam', laatstenaam = '$laatstenaam', geboortedatum = '$geboortedatum', plaats = '$woonplaats' WHERE gebruikerscode = '$userSelf'";
  $res = mysqli_query($conn, $sql);
  $_SESSION['eerstenaam'] = $eerstenaam;
}

if(isset($_FILES['file_upload']['name'])) {
  $fotonaam = basename($_FILES['file_upload']['name']);
  $t_fotonaam = $_FILES['file_upload']['tmp_name'];
  $dir = 'upload';

  $target_pf = $dir . '/' . $fotonaam;
  if(move_uploaded_file($t_fotonaam, $target_pf)) {
    $sql = "UPDATE gebruiker SET profielfoto = 'upload/$fotonaam' WHERE gebruikerscode = '$userSelf'";
    $res = mysqli_query($conn, $sql);
  }
}

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>BierKiezer - <?php echo $firstName . " " . $lastName; ?></title>
  <link rel="icon" type="image/png" href="img/beer-icon.png">

  <!--Styling-->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap-select.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body data-spy="scroll" data-target="#scrollSpy" data-offset="20">

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
    <div class="col-md-offset-1">
      <h1>Edit Profile</h1>
    </div>
  </div>
  <hr>
  <div class="row">
    <form action="edit_profile.php" method="post" enctype="multipart/form-data">
      <div class="col-md-4">
        <div class="col-md-offset-1 col-md-10">
          <div class="row">
            <?php if($profilePic === NULL || $profilePic === '') {
              echo "<img src='upload/blank-user.png' class='img-thumbnail' id='imgTest' style='width: 300px; height: 300px;'>";
            } else { ?>
              <img src='<?php if (isset($_POST['submit']) && $profilePic != $_FILES['file_upload']['name'] && $_FILES['file_upload']['name'] === NULL) {
                  echo 'upload/' . $fotonaam;
                }else{
                  echo $profilePic;} ?>' class='img-thumbnail' id='imgTest' style='width: 300px; height: 300px;'>
          <?php } ?>
          </div>
          <div class="row">
            <div class="form-group col-sm-offset-1">
              <label for="profielfoto">Verander profielfoto</label>
              <input type="file" id="imgInp" name="file_upload" accept="image/*">
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <table class="table-striped col-md-12">
          <tr>
            <th colspan="2"><h2><?php echo $firstName . " " . $lastName; ?></h2></th>
          </tr>
          <tr>
            <th><h4>Eerste naam<h4></th>
            <td><input type="text" class="form-control" name="eerstenaam" value="<?php if (isset($_POST['submit'])) {echo $_POST['eerstenaam'];}else{ echo $firstName;} ?>"></td>
          </tr>
          <tr>
            <th><h4>Laatste naam<h4></th>
            <td><input type="text" class="form-control" name="laatstenaam" value="<?php if (isset($_POST['submit'])) {echo $_POST['laatstenaam'];}else{ echo $lastName;} ?>"></td>
          </tr>
          <tr>
            <th><h4>Geboortedatum<h4></th>
            <td><input type="date" class="form-control" name="geboortedatum" value="<?php if (isset($_POST['submit'])) {echo $_POST['geboortedatum'];}else{ echo $birthdate;} ?>"></td>
          </tr>
          <tr>
            <th><h4>Woonplaats<h4></th>
            <td><input type="text" class="form-control" name="woonplaats" value="<?php if (isset($_POST['submit'])) {echo $_POST['woonplaats'];}else{echo $location;} ?>"></td>
          </tr>
          <tr>
          <?php if (!isset($_POST['submit']))
            { echo "<td colspan='2'>"; } else {
              echo "<td><p>Uw nieuwe gegevens is opgeslagen!</p></td>";
              echo "<td>";
            }
          ?>
            <div class="col-md-offset-8 col-md-4">
              <input type="submit" class="form-control" name="submit" value="Opslaan">
            </div>
          </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>

</body>

<!--Scripts-->
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-select.min.js"></script>
<script src="js/app.js"></script>
</html>
