<?php

session_start();
include_once('core/connection.php');
include_once('core/functions.php');

if(!isset($_SESSION['gebruikerscode']) || $_SESSION['admin'] < 1) {
  header("Location: index.php");
} else {
  $userSelf = $_SESSION['gebruikerscode'];
  $sql = "SELECT * FROM gebruiker WHERE gebruikerscode= '$userSelf' ";
  $query = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($query);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>BierKiezer - Data Wijzigen</title>
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
    <nav class="col-md-3" id="scrollSpy">
      <ul class="nav nav-pills nav-stacked">
        <li><a href="#section1">Mijn bier</a><li>
        <li><a href="#section2">Mijn kroeg</a><li>
        <li><a href="#section3">Mijn brouwer</a><li>
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
              <td colspan="6">
                <a href="add_beer.php" class="addData">
                  <h5 class="col-md-offset-1">Nieuwe bier toevoegen <span class="	glyphicon glyphicon-plus-sign"></span></h5>
                </a>
              </td>
            </tr>
            <tr>
              <th>Naam</th>
              <th>Type</th>
              <th>Stijl</th>
              <th colspan="3">Alcohol</th>
            </tr>
            <?php

            $sql = "SELECT * FROM bier WHERE gebruikerscode='$userSelf' ORDER BY biercode DESC";
            $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));

            if(mysqli_num_rows($res) > 0) {
              foreach($res as $row) {
                echo "<tr>";
                $unit = $row['biercode'];
                echo "<td><a href='view_beer.php?bid=" . $row['biercode'] . "'>" . $row['naam'] . "</a></td>";
                echo "<td>".$row['type']."</td>";
                echo "<td>".$row['stijl']."</td>";
                echo "<td>".$row['alcohol']."</td>";
                echo "<td><a href='edit_beer.php?bid=$unit' class='dataAnchor'>
                            <button type='button' class='btn dataBtn'><span class='glyphicon glyphicon-pencil'></span></button>
                          </a></td>";
                echo "<td><a href='delete_beer.php?bid=$unit' class='dataAnchor'>
                            <button type='button' class='btn dataBtn'><span class='	glyphicon glyphicon-trash'></span></button>
                          </a></td>";
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
              <td colspan="6">
                <a href="add_bar.php" class="addData">
                  <h5 class="col-md-offset-1">Nieuwe kroeg toevoegen <span class="	glyphicon glyphicon-plus-sign"></span></h5>
                </a>
              </td>
            </tr>
            <tr>
              <th colspan="2">Naam</th>
              <th>Adres</th>
              <th colspan="3">Plaats</th>
            </tr>
            <?php

            $sql2 = "SELECT * FROM kroeg WHERE gebruikerscode='$userSelf' ORDER BY kroegcode DESC";
            $res2 = mysqli_query($conn, $sql2) or die(mysqli_error($conn));


            if(mysqli_num_rows($res2) > 0) {
              foreach($res2 as $row) {
                echo "<tr>";
                $unit = $row['kroegcode'];
                echo "<td colspan='2'><a href='view_bar.php?bid=" . $row['kroegcode'] . "'>" . $row['naam'] . "</a></td>";
                echo "<td>".$row['adres']."</td>";
                echo "<td>".$row['plaats']."</td>";
                echo "<td><a href='edit_bar.php?bid=$unit' class='dataAnchor'>
                            <button type='button' class='btn dataBtn'><span class='glyphicon glyphicon-pencil'></span></button>
                          </a></td>";
                echo "<td><a href='delete_bar.php?bid=$unit' class='dataAnchor'>
                            <button type='button' class='btn dataBtn'><span class='	glyphicon glyphicon-trash'></span></button>
                          </a></td>";
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
              <td colspan="6">
                <a href="add_brewery.php" class="addData">
                  <h5 class="col-md-offset-1">Nieuwe brouwer toevoegen <span class="	glyphicon glyphicon-plus-sign"></span></h5>
                </a>
              </td>
            </tr>
            <tr>
              <th>Naam</th>
              <th colspan="4">Land</th>
            </tr>
            <?php

            $sql3 = "SELECT * FROM brouwer WHERE gebruikerscode='$userSelf' ORDER BY brouwcode DESC";
            $res3 = mysqli_query($conn, $sql3) or die(mysqli_error($conn));

            if(mysqli_num_rows($res3) > 0) {
              foreach($res3 as $row) {
                echo "<tr>";
                $unit = $row['brouwcode'];
                echo "<td><a href='view_brewery.php?bid=" . $row['brouwcode'] . "'>" . $row['naam'] . "</a></td>";
                echo "<td colspan='2'>".$row['land']."</td>";
                echo "<td><a href='edit_brewery.php?bid=$unit' class='dataAnchor'>
                            <button type='button' class='btn dataBtn'><span class='glyphicon glyphicon-pencil'></span></button>
                          </a></td>";
                echo "<td><a href='delete_brewery.php?bid=$unit' class='dataAnchor'>
                            <button type='button' class='btn dataBtn'><span class='	glyphicon glyphicon-trash'></span></button>
                          </a></td>";
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
