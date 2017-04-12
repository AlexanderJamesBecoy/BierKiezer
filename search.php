<?php

session_start();
include_once('core/connection.php');
include_once('core/functions.php');

if(isset($_SESSION['gebruikerscode'])) {
  $userSelf = $_SESSION['gebruikerscode'];
}

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>BierKiezer - Zoeken</title>
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
            <li><a href='own_data.php?user=<?php echo $userSelf; ?>'>Data bekijken</a></li>
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
    <div class="col-md-offset-1 col-md-10">
      <div class="row">
      <?php
          if(!empty($_GET['name'])) {
            if(preg_match("/[A-Za-z]+/", $_GET['name'])) {
              $keyword = mysqli_real_escape_string($conn, $_GET['name']);

              if(isset($_GET['sortby']) && $_GET['sortby'] == "searchbeer") {
                $sql = "(SELECT * FROM bier WHERE naam LIKE '%" . $keyword . "%'
                        OR type LIKE '%" . $keyword . "%' OR stijl LIKE '%" . $keyword . "%'
                        OR alcohol LIKE '%" . $keyword . "%')";
                $res = mysqli_query($conn, $sql);

                if(mysqli_num_rows($res) > 0) {
                  foreach($res as $row) {
                    $beerCode = $row['biercode'];
                    $beerName = $row['naam'];
                    $beerType = $row['type'];
                    $beerStyle = $row['stijl'];
                    $beerAlcohol = $row['alcohol'];
                    $beerAuthor = $row['gebruikerscode'];
                    $brewCode = $row['brouwcode'];
                    $sql = "SELECT naam FROM brouwer WHERE brouwcode = '$brewCode'";
                    $res = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_array($res);
                    $brewName = $row['naam'];

                    if($beerStyle == NULL) {
                      $beerStyle = "Geen";
                    } ?>
                    <a href="view_beer.php?bid=<?php echo $beerCode; ?>" class="itemList">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="col-md-11">
                            <img src="upload/blank-item.jpg" class="img-thumbnail" alt="">
                          </div>
                        </div>
                        <div class="col-md-8">
                          <div class="row">
                            <div class="col-lg-12">
                              <h3><span class="itemType itemType-beer">BIER</span> <?php echo $beerName; ?></h3>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-2">
                              <h4>Brouwer</h4>
                            </div>
                            <div class="col-sm-offset-1 col-sm-8">
                              <h5><?php echo $brewName; ?></h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-2">
                              <h4>Type</h4>
                            </div>
                            <div class="col-sm-offset-1 col-sm-8">
                              <h5><?php echo $beerType; ?></h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-2">
                              <h4>Stijl</h4>
                            </div>
                            <div class="col-sm-offset-1 col-sm-8">
                              <h5><?php echo $beerStyle; ?></h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-2">
                              <h4>Alcholpercentage</h4>
                            </div>
                            <div class="col-sm-offset-1 col-sm-8">
                              <h5><?php echo $beerAlcohol; ?></h5>
                            </div>
                          </div>
                          <?php
                          //Haal gebruiker's informatie
                          $sql = "SELECT eerstenaam, laatstenaam FROM gebruiker WHERE gebruikerscode = '$beerAuthor'";
                          $res = mysqli_query($conn, $sql);
                          $row = mysqli_fetch_array($res);
                          $authorFName = $row['eerstenaam'];
                          $authorLName = $row['laatstenaam'];
                          ?>
                          <div class="row">
                            <div class="col-sm-2">
                              <h4>Auteur</h4>
                            </div>
                            <div class="col-sm-offset-1 col-sm-8">
                              <h5><?php echo $authorFName . " " . $authorLName; ?></h5>
                            </div>
                          </div>
                        </div>
                    </div>
                  </a>
                  <hr>
                <?php
                }
              } else {
                echo "<div class='row'>
                        <div class='col-md-offset-2 col-md-8'>
                          <h5>Helaas, er is niets gevonden.</h5>
                        </div>
                      </div>
                      <hr>";
              }
            } else if(isset($_GET['sortby']) && $_GET['sortby'] == "searchkroeg") {
              $sql = "(SELECT * FROM kroeg WHERE naam LIKE '%" . $keyword . "%'
                      OR adres LIKE '%" . $keyword . "%' OR plaats LIKE '%" . $keyword . "%')";
              $res = mysqli_query($conn, $sql);

              if(mysqli_num_rows($res) > 0) {
                foreach($res as $row) {
                  $kroegCode = $row['kroegcode'];
                  $kroegName = $row['naam'];
                  $kroegAdres = $row['adres'];
                  $kroegPlaats = $row['plaats'];
                  $kroegAuthor = $row['gebruikerscode'];

                      ?>
                      <a href="view_bar.php?bid=<?php echo $kroegCode; ?>" class="itemList">
                        <div class="row">
                          <div class="col-md-4">
                            <div class="col-md-11">
                              <img src="upload/blank-item.jpg" class="img-thumbnail" alt="">
                            </div>
                          </div>
                          <div class="col-md-8">
                            <div class="row">
                              <div class="col-lg-12">
                                <h3><span class="itemType itemType-bar">KROEG</span> <?php echo $kroegName; ?></h3>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-2">
                                <h4>Adres</h4>
                              </div>
                              <div class="col-sm-offset-1 col-sm-8">
                                <h5><?php echo $kroegAdres; ?></h5>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-2">
                                <h4>Plaats</h4>
                              </div>
                              <div class="col-sm-offset-1 col-sm-8">
                                <h5><?php echo $kroegPlaats; ?></h5>
                              </div>
                            </div>
                            <?php
                            //Haal gebruiker's informatie
                            $sql = "SELECT eerstenaam, laatstenaam FROM gebruiker WHERE gebruikerscode = '$kroegAuthor'";
                            $res = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_array($res);
                            $authorFName = $row['eerstenaam'];
                            $authorLName = $row['laatstenaam'];
                            ?>
                            <div class="row">
                              <div class="col-sm-2">
                                <h4>Auteur</h4>
                              </div>
                              <div class="col-sm-offset-1 col-sm-8">
                                <h5><?php echo $authorFName . " " . $authorLName; ?></h5>
                              </div>
                            </div>
                          </div>
                      </div>
                    </a>
                    <hr>
                <?php
                }
              } else {
                echo "<div class='row'>
                        <div class='col-md-offset-2 col-md-8'>
                          <h5>Helaas, er is niets gevonden.</h5>
                        </div>
                      </div>
                      <hr>";
              }
            } else if(isset($_GET['sortby']) && $_GET['sortby'] == "searchbrew") {
              $sql = "(SELECT * FROM brouwer WHERE naam LIKE '%" . $keyword . "%'
                      OR land LIKE '%" . $keyword . "%')";
              $res = mysqli_query($conn, $sql);

              if(mysqli_num_rows($res) > 0) {
                foreach($res as $row) {
                  $brewCode = $row['brouwcode'];
                  $brewName = $row['naam'];
                  $brewCountry = $row['land'];
                  $brewAuthor = $row['gebruikerscode'];

                  ?>
                  <a href="view_brewery.php?bid=<?php echo $brewCode; ?>" class="itemList">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="col-md-11">
                          <img src="upload/blank-item.jpg" class="img-thumbnail" alt="">
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="row">
                          <div class="col-lg-12">
                            <h3><span class="itemType itemType-brew">BROUWER</span> <?php echo $brewName; ?></h3>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-2">
                            <h4>Land</h4>
                          </div>
                          <div class="col-sm-offset-1 col-sm-8">
                            <h5><?php echo $brewCountry; ?></h5>
                          </div>
                        </div>
                        <?php
                        //Haal gebruiker's informatie
                        $sql = "SELECT eerstenaam, laatstenaam FROM gebruiker WHERE gebruikerscode = '$brewAuthor'";
                        $res = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_array($res);
                        $authorFName = $row['eerstenaam'];
                        $authorLName = $row['laatstenaam'];
                        ?>
                        <div class="row">
                          <div class="col-sm-2">
                            <h4>Auteur</h4>
                          </div>
                          <div class="col-sm-offset-1 col-sm-8">
                            <h5><?php echo $authorFName . " " . $authorLName; ?></h5>
                          </div>
                        </div>
                      </div>
                  </div>
                </a>
                <hr>
                <?php
                }
              } else {
                echo "<div class='row'>
                        <div class='col-md-offset-2 col-md-8'>
                          <h5>Helaas, er is niets gevonden.</h5>
                        </div>
                      </div>
                      <hr>";
              }
            } else if(isset($_GET['sortby']) && $_GET['sortby'] == "searchuser") {
              $sql = "(SELECT * FROM gebruiker WHERE eerstenaam LIKE '%" . $keyword . "%'
                      OR laatstenaam LIKE '%" . $keyword . "%' OR plaats LIKE '%" . $keyword . "%')";
              $res = mysqli_query($conn, $sql);

                if(mysqli_num_rows($res) > 0) {
                  foreach($res as $row) {
                    $gebruikerCode = $row['gebruikerscode'];
                    $gebruikerFName = $row['eerstenaam'];
                    $gebruikerLName = $row['laatstenaam'];
                    $gebruikerBirth = $row['geboortedatum'];
                    $gebruikerPlaats = $row['plaats'];
                    $gebruikerPic = $row['profielfoto'];

                    ?>
                    <a href="view_profile.php?user=<?php echo $gebruikerCode; ?>" class="itemList">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="col-md-11">
                            <?php if ($gebruikerPic === NULL || $gebruikerPic === '') {
                              echo "<img src='upload/blank-item.jpg' class='img-thumbnail' alt='' style='width: 250px; height: 200px;'>";
                            } else {
                              echo "<img src='$gebruikerPic' class='img-thumbnail' alt='' style='width: 250px; height: 200px;'>";
                            } ?>
                          </div>
                        </div>
                        <div class="col-md-8">
                          <div class="row">
                            <div class="col-lg-12">
                              <h3><span class="itemType itemType-user">GEBRUIKER</span> <?php echo $gebruikerFName . " " . $gebruikerLName; ?></h3>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-3">
                              <h4>Geboren op</h4>
                            </div>
                            <div class="col-sm-9">
                              <h5>
                                <?php
                                  if ($gebruikerBirth === NULL) {
                                    echo "Niet van toepassing";
                                  } else {
                                    echo $gebruikerBirth;}
                                ?>
                              </h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-3">
                              <h4>Woon in</h4>
                            </div>
                            <div class="col-sm-9">
                              <h5>
                                <?php
                                  if ($gebruikerPlaats === NULL) {
                                    echo "Niet van toepassing";
                                  } else {
                                    echo $gebruikerPlaats;
                                  }
                                ?>
                              </h5>
                            </div>
                          </div>
                        </div>
                    </div>
                  </a>
                <hr>
                <?php
                }
              } else {
                echo "<div class='row'>
                        <div class='col-md-offset-2 col-md-8'>
                          <h5>Helaas, er is niets gevonden.</h5>
                        </div>
                      </div>
                      <hr>";
              }
            } else {
              echo "<div class='row'>
                      <div class='col-md-offset-2 col-md-8'>
                        <h5>Kies wat voor soort u wilt zoeken, alsublieft.</h5>
                      </div>
                    </div>
                    <hr>";
            }
          } else {
            echo "<div class='row'>
                    <div class='col-md-offset-2 col-md-8'>
                      <h5>Er is niets gevonden.</h5>
                    </div>
                  </div>
                  <hr>";
          }
        } else {
          echo "<div class='row'>
                  <div class='col-md-offset-2 col-md-8'>
                    <h5>Voer het zoekformulier in, alsublieft.</h5>
                  </div>
                </div>
                <hr>";
        }
      ?>
      </div>
    </div>
  </div>
</div>

</body>

<!--Scripts-->
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-select.min.js"></script>
</html>
