<?php

session_start();
include_once('core/connection.php');
include_once('core/functions.php');

if(isset($_SESSION['gebruikerscode'])) {
  $userSelf = $_SESSION['gebruikerscode'];
}

$viewUser = $_GET['user'];
$sql = "SELECT * FROM gebruiker WHERE gebruikerscode= '$viewUser' ";
$query = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($query);
$firstName = $row['eerstenaam'];
$lastName = $row['laatstenaam'];
$profilePic = $row['profielfoto'];
$location = $row['plaats'];
$birthdate = $row['geboortedatum'];

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
    <nav class="col-md-3" id="scrollSpy">
      <?php
      if($profilePic == NULL) {
        echo "<img src='upload/blank-user.png' class='text-center img-thumbnail' alt='' width='250px' height='200px'>";
      } else {
        echo "<img src='$profilePic' class='img-thumbnail' style='width: 250px; height: 200px;'>";
      }
      ?>
      <h3><?php echo $firstName . " " . $lastName; ?></h3>
      <?php
      if($location == NULL) {
        echo "<p>Locatie is NVT</p>";
      } else {
        echo "<p>Woont in $location";
      }
      if($birthdate == NULL) {
        echo "<p>Geboortedatum is NVT</p>";
      } else {
        echo "<p>Geboren op $birthdate";
      }
      ?>
      <ul class="nav nav-pills nav-stacked">
        <li><a href="#section1">Mijn bier</a><li>
        <li><a href="#section2">Mijn kroeg</a><li>
        <li><a href="#section3">Mijn brouwer</a><li>
        <li><a href="#section4">Mijn reacties</a><li>
      </ul>
    </nav>
    <div class="col-md-9">
      <div id="section1">
        <!-- Beer -->
        <div class="row">
          <div class="col-sm-offset-2 col-sm-8">
            <h3>Mijn bieren</h3>
          </div>
          <div class="col-sm-2">
            <button class="btn btn-default btn-toggle" data-toggle="collapse" data-target="#tableBeer">Show/Hide</button>
          </div>
        </div>
        <div id="tableBeer" class="collapse row">
          <table class="table-hover col-md-12">
            <tr>
              <th>Naam</th>
              <th>Type</th>
              <th>Stijl</th>
              <th>Alcohol</th>
            </tr>
            <?php

            $sql = "SELECT * FROM bier WHERE gebruikerscode='$viewUser' ORDER BY biercode DESC";
            $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));

            if(mysqli_num_rows($res) > 0) {
              foreach($res as $row) {
                echo "<tr>";
                $unit = $row['biercode'];
                echo "<td><a href='view_beer.php?bid=" . $row['biercode'] . "'>" . $row['naam'] . "</a></td>";
                echo "<td>".$row['type']."</td>";
                echo "<td>".$row['stijl']."</td>";
                echo "<td>".$row['alcohol']."</td>";
                echo "</tr>";
              }
            } else {
              echo "<tr>";
              echo "<td><h4>Er is niets te vinden.</h4></td>";
              echo "</tr>";
            }
            ?>
          </table>
        </div>
      </div>

      <hr>

      <div id="section2">
        <!-- Kroeg -->
        <div class="row">
          <div class="col-sm-offset-2 col-sm-8">
            <h3>Mijn kroegen</h3>
          </div>
          <div class="col-sm-2">
            <button class="btn btn-default btn-toggle" data-toggle="collapse" data-target="#tableKroeg">Show/Hide</button>
          </div>
        </div>
        <div id="tableKroeg" class="collapse row">
          <table class="table-hover col-md-12">
            <tr>
              <th>Naam</th>
              <th>Adres</th>
              <th>Plaats</th>
            </tr>
            <?php

            $sql2 = "SELECT * FROM kroeg WHERE gebruikerscode='$viewUser' ORDER BY kroegcode DESC";
            $res2 = mysqli_query($conn, $sql2) or die(mysqli_error($conn));


            if(mysqli_num_rows($res2) > 0) {
              foreach($res2 as $row) {
                echo "<tr>";
                $unit = $row['kroegcode'];
                echo "<td><a href='view_bar.php?bid=" . $row['kroegcode'] . "'>" . $row['naam'] . "</a></td>";
                echo "<td>".$row['adres']."</td>";
                echo "<td>".$row['plaats']."</td>";
                echo "</tr>";
              }
            } else {
              echo "<tr>";
              echo "<td><h4>Er is niets te vinden.</h4></td>";
              echo "</tr>";
            }
            ?>
          </table>
        </div>
      </div>

      <hr>

      <div id="section3">
        <!-- Kroeg -->
        <div class="row">
          <div class="col-sm-offset-2 col-sm-8">
            <h3>Mijn brouwers</h3>
          </div>
          <div class="col-sm-2">
            <button class="btn btn-default btn-toggle" data-toggle="collapse" data-target="#tableBrouwer">Show/Hide</button>
          </div>
        </div>
        <div id="tableBrouwer" class="collapse row">
          <table class="table-hover col-md-12">
            <tr>
              <th>Naam</th>
              <th>Land</th>
            </tr>
            <?php

            $sql3 = "SELECT * FROM brouwer WHERE gebruikerscode='$viewUser' ORDER BY brouwcode DESC";
            $res3 = mysqli_query($conn, $sql3) or die(mysqli_error($conn));

            if(mysqli_num_rows($res3) > 0) {
              foreach($res3 as $row) {
                echo "<tr>";
                $unit = $row['brouwcode'];
                echo "<td><a href='view_brewery.php?bid=" . $row['brouwcode'] . "'>" . $row['naam'] . "</a></td>";
                echo "<td>".$row['land']."</td>";
                echo "</tr>";
              }
            } else {
              echo "<tr>";
              echo "<td><h4>Er is niets te vinden.</h4></td>";
              echo "</tr>";
            }
            ?>
          </table>
        </div>
      </div>

      <hr>

      <div id="section4">
        <!-- Kroeg -->
        <div class="row">
          <div class="col-sm-offset-2 col-sm-8">
            <h3>Mijn reacties</h3>
          </div>
          <div class="col-sm-2">
            <button class="btn btn-default btn-toggle" data-toggle="collapse" data-target="#tableReactie">Show/Hide</button>
          </div>
        </div>
        <div id="tableReactie" class="collapse row">
          <table class="table-hover col-md-12">
            <tr>
              <th>Type</th>
              <th>Naam</th>
              <th>Datum</th>
              <th>beoordeling</th>
            </tr>
            <?php

            $sql4 = "SELECT * FROM beoordeling WHERE gebruikerscode='$viewUser' ORDER BY beoordelingscode DESC";
            $res4 = mysqli_query($conn, $sql4) or die(mysqli_error($conn));

            if(mysqli_num_rows($res4) > 0) {
              foreach($res4 as $row) {
                echo "<tr>";
                $unit = $row['beoordelingscode'];
                $type = $row['type'];
                $date = $row['datum'];
                $productcode = $row['productcode'];
                if ($row['beoordeel'] == 1) {
                  $rating = "<span class='glyphicon glyphicon-thumbs-up'></span> Vind ik goed";
                } else {
                  $rating = "<span class='glyphicon glyphicon-thumbs-down'></span> Vind ik slecht";
                }
                if ($type === "bier") {
                  $sql = "SELECT * FROM bier WHERE biercode = '$productcode'";
                  $res = mysqli_query($conn, $sql);
                  $row = mysqli_fetch_array($res);
                  $beername = $row['naam'];
                  echo "<td>Bier</td>";
                  echo "<td><a href='view_beer.php?bid=$productcode'>$beername</a></td>";
                  echo "<td>$date</td>";
                  echo "<td>$rating</td>";
                }
                if ($type === "brouwer") {
                  $sql = "SELECT * FROM brouwer WHERE brouwcode = '$productcode'";
                  $res = mysqli_query($conn, $sql);
                  $row = mysqli_fetch_array($res);
                  $brewname = $row['naam'];
                  echo "<td>Brouwer</td>";
                  echo "<td><a href='view_brewery.php?bid=$productcode'>$brewname</a></td>";
                  echo "<td>$date</td>";
                  echo "<td>$rating</td>";
                }
                if ($type === "kroeg") {
                  $sql = "SELECT * FROM kroeg WHERE kroegcode = '$productcode'";
                  $res = mysqli_query($conn, $sql);
                  $row = mysqli_fetch_array($res);
                  $barname = $row['naam'];
                  echo "<td>Kroeg</td>";
                  echo "<td><a href='view_bar.php?bid=$productcode'>$barname</a></td>";
                  echo "<td>$date</td>";
                  echo "<td>$rating</td>";
                }
                echo "</tr>";
              }
            } else {
              echo "<tr>";
              echo "<td><h4>Er is niets te vinden.</h4></td>";
              echo "</tr>";
            }
            ?>
          </table>
        </div>
      </div>

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
