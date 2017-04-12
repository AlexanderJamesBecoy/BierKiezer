<?php

session_start();
include_once('core/connection.php');
require_once('core/functions.php');

if(isset($_SESSION['gebruikerscode'])) {
  $userSelf = $_SESSION['gebruikerscode'];
  $sql = "SELECT admin, profielfoto FROM gebruiker WHERE gebruikerscode = '$userSelf'";
  $res = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($res);
  $userAdmin = $row['admin'];
  $userPic = $row['profielfoto'];
} else {
  $userSelf = "";
  $userAdmin = "";
}

//Haal informatie uit bier
$viewBeer = $_GET['bid'];
$sql = "SELECT * FROM bier WHERE biercode='$viewBeer'";
$query = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($query);
$beerName = $row['naam'];
$beerType = $row['type'];
$beerStyle = $row['stijl'];
$beerAlcohol = $row['alcohol'];
$beerAuthor = $row['gebruikerscode'];
$brewcode = $row['brouwcode'];

//Haal informatie uit brouwer
$sql2 = "SELECT naam, land FROM brouwer WHERE brouwcode='$brewcode'";
$query2 = mysqli_query($conn, $sql2);
$row2 = mysqli_fetch_array($query2);

$brewName = $row2['naam'];
$brewLand = $row2['land'];

//Haal informatie uit kroeg via schenkt
$sql3 = "SELECT kroeg.kroegcode as bid, naam FROM kroeg, schenkt WHERE biercode='$viewBeer' AND schenkt.kroegcode = kroeg.kroegcode";
$query3 = mysqli_query($conn, $sql3);

//Haal gebruikers informatie uit gebruiker
$sql4 = "SELECT eerstenaam, laatstenaam FROM gebruiker WHERE gebruikerscode = '$beerAuthor'";
$query4 = mysqli_query($conn, $sql4);
$row4 = mysqli_fetch_array($query4);
$firstName = $row4['eerstenaam'];
$lastName = $row4['laatstenaam'];
$wholeName = $firstName . " " . $lastName;

//Post reactie
if(isset($_POST['post-reaction'])) {
  $error = 0;
  $text = $_POST['text'];
  if ($_POST['beoordeel'] == "goed") {
    $choice = 1;
  } else {
    $choice = 0;
  }
  $rating = $_POST['rating'];
  $date = date("m/d/y");
  if(empty($text)) {
    $error = 1;
  }
  if(empty($rating)) {
    $error = 1;
  }

  if($error == 0) {
    $sql = "INSERT INTO beoordeling (productcode, commentaar, beoordeel, sterren, datum, type, gebruikerscode)
    VALUES ('$viewBeer', '$text', '$choice', '$rating', '$date', 'bier', '$userSelf')";
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
  <title>BierKiezer - <?php echo uppercase($beerName); ?></title>
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
            <th colspan="2">
              <h3 class="col-sm-offset-1 col-sm-10"><?php echo $beerName; ?></h3>
              <?php
                if (isset($_SESSION['admin'])) {
                  if($_SESSION['admin'] == 2 || $userSelf === $beerAuthor) { ?>
                    <a href="edit_beer.php?bid=<?php echo $viewBeer; ?>" class="editBtn">
                      <button type="button" class="btn editBtn"><span class="glyphicon glyphicon-pencil"></span></button>
                    </a>
              <?php }
                }
              ?>
            </th>
          </tr>
          <tr>
            <td><h4 class="col-sm-offset-2">Brouwer </h4></td>
            <td><a href="view_brewery.php?bid=<?php echo $brewcode; ?>"><h4><?php echo uppercase($brewName); ?></h4></a></td>
          </tr>
          <tr>
            <td><h4 class="col-sm-offset-2">Land </h4></td>
            <td><h4><?php echo $brewLand; ?></h4></td>
          </tr>
          <tr>
            <td><h4 class="col-sm-offset-2">Type </h4></td>
            <td><h4><?php echo $beerType; ?></h4></td>
          </tr>
          <tr>
            <td><h4 class="col-sm-offset-2">Stijl </h4></td>
            <td><h4><?php echo $beerStyle; ?></h4></td>
          </tr>
          <tr>
            <td><h4 class="col-sm-offset-1">Alcoholpercentage </h4></td>
            <td><h4><?php echo $beerAlcohol; ?></h4></td>
          </tr>
          <tr>
            <td><h4 class="col-sm-offset-2">Auteur </h4></td>
            <td><a href="view_profile.php?user=<?php echo $beerAuthor; ?>"><h4><?php echo $wholeName; ?></h4></a></td>
          </tr>
        </table>
      </div>
      <hr>
      <div class="row">
        <table class="table-striped col-md-12">
          <tr>
            <th><h3 class="col-sm-offset-2 col-sm-12">
              Te vinden in</h3>
            </th>
          </tr>
          <?php
          if(mysqli_num_rows($query3) > 0) {
            while($row3 = mysqli_fetch_assoc($query3)) {
              echo "<tr><td><a href='view_bar.php?bid=".$row3['bid']."'><p>".$row3['naam']."</p></a></td></tr>";
            }
          } else {
            echo "<tr class='warning'><td><h4>Dit bier wordt nergens geschonken.</h4></td></tr>";
          }
          ?>
        </table>
      </div>
    </div>
  </div>

  <div class="row">
    <table class="table-striped col-md-offset-1 col-md-8">
      <tr>
        <th><h2 class="col-sm-offset-2 col-md-10">Beoordelingen</h2></th>
      </tr>
      <?php
      $sql = "SELECT * FROM beoordeling WHERE productcode = '$viewBeer' AND type = 'bier' ORDER BY beoordelingscode DESC";
      $res = mysqli_query($conn, $sql);


        if (isset($_SESSION['eerstenaam'])) {
          if (mysqli_num_rows($res) > 0) {
            foreach($res as $row) {
              $commentUser = $row['gebruikerscode'];
              $commentText = $row['commentaar'];
              $commentRate = $row['beoordeel'];
              $commentStar = $row['sterren'];
              $commentDate = $row['datum'];

              $sql = "SELECT * FROM gebruiker WHERE gebruikerscode = '$commentUser'";
              $res = mysqli_query($conn, $sql);
              $row = mysqli_fetch_array($res);
              $commentPic = $row['profielfoto'];
              $commentFName = $row['eerstenaam'];
              $commentLName = $row['laatstenaam'];
              ?>
                <tr>
                  <td rowspan="3"><img src="<?php echo $commentPic; ?>" class="col-sm-offset-2" style="width: 150px; height: 100px;"></td>
                  <td><h4><?php echo $commentFName . " " . $commentLName; ?></h4></td>
                  <td>
                    <div>
                      <?php if($commentRate == 1) { ?>
                        <h6><span class="glyphicon glyphicon-thumbs-up"></span> Dit vind ik goed!</h6>
                      <?php } else { ?>
                        <h6><span class="glyphicon glyphicon-thumbs-down"></span> Dit vind ik slecht!</h6>
                      <?php } ?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td colspan="3">
                    <p>
                      <?php echo $commentDate; ?> | star rating van <?php echo $commentStar; ?></p>
                  </td>
                </tr>
                <tr>
                  <td colspan="3"><p><?php echo $commentText; ?></p></td>
                </tr>
                <tr>
                  <td colspan="3"><hr></td>
                </tr>
              </div>
            <?php } ?>
          <?php } else { ?>
            <tr>
              <td colspan="3"><p class="emptyBox">Er is nog geen reacties gemaakt.</p></td>
            </tr>
          <?php } ?>
          <form action="view_beer.php?bid=<?php echo $viewBeer; ?>" method="post">
            <tr>
              <td colspan="3"><h4>Reactie toevoegen</h4></td>
            </tr>
            <tr>
              <td class="reactImage"><img src="<?php echo $userPic; ?>" class="col-sm-offset-2" style="width: 150px; height: 100px;"></td>
              <td colspan="2" class="reactText"><textarea class="form-control" name="text" rows="5" required></textarea></td>
            </tr>
            <tr>
            </tr>
            <tr>
              <td colspan="3">
                <input type="submit" class="btn btn-default react" name="post-reaction" value="Post">
                <input type="number" class="react react-rating" name="rating" min="1" max="5" placeholder="&#9734;" required>
                <select name="beoordeel" class="react react-choice" data-show-icon="true" required>
                  <option value="goed"><i class="glyphicon glyphicon-thumbs-up"></i> Goed</option>
                  <option value="niet-goed"><i class="glyphicon glyphicon-thumbs-down"></i> Niet goed</option>
                </select>
              </td>
            </tr>
          </form>
        <?php } else { ?>
          <tr>
            <td class="reactionBox">
              <p>Er is nog geen reacties gemaakt. <a href='login.php'>Login</a> of <a href='register.php'>meld je aan</a> om een reactie toe te voegen.</p>
            </td>
          </tr>
        <?php
        }
      ?>
    </table>
  </div>
</div>

</body>

<!--Scripts-->
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-select.min.js"></script>
</html>
