<?php

session_start();
include_once('core/connection.php');
include_once('core/functions.php');

if(isset($_SESSION['gebruikerscode'])) {
  $userSelf = $_SESSION['gebruikerscode'];
}

if(isset($_POST['submit'])) {
  $sql = "UPDATE gebruiker SET admin = 1 WHERE gebruikerscode = '$userSelf'";
  $res = mysqli_query($conn, $sql);
  $_SESSION['admin'] = 1;

  header("Location: index.php?went_premium");
}

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>BierKiezer</title>
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
        echo "<li class='active'><a href='premium.php'>Ga Premium!</a></li>";
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
      <img src="img/banner.jpg" class="img-fluid banner" width="100%" height="320px">
    </div>
  </div>
  <div class="row">
    <div class="col-md-offset-5">
      <h1>Premium Gaan!</h1>
    </div>
  </div>
  <hr>
  <div class="row">
    <div class="col-md-offset-1 col-md-4">
      <div class="row">
        <h2>Wat is premium?</h2>
      </div>
      <hr>
      <div class="row">
        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui.</p>
      </div>
    </div>
    <div class="col-md-6">
      <?php if (isset($_SESSION['gebruikerscode'])) { ?>
        <form action="premium.php" class="form-horizontal" method="post" role="form">
          <fieldset>
            <legend>Payment</legend>
            <div class="form-group">
              <label class="col-sm-3 control-label" for="card-holder-name">Name on Card</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="card-holder-name" id="card-holder-name" placeholder="Card Holder's Name">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label" for="card-number">Card Number</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="card-number" id="card-number" placeholder="Debit/Credit Card Number">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label" for="expiry-month">Expiration Date</label>
              <div class="col-sm-9">
                <div class="row">
                  <div class="col-xs-3">
                    <select class="form-control col-sm-2" name="expiry-month" id="expiry-month">
                      <option>Month</option>
                      <option value="01">Jan (01)</option>
                      <option value="02">Feb (02)</option>
                      <option value="03">Mar (03)</option>
                      <option value="04">Apr (04)</option>
                      <option value="05">May (05)</option>
                      <option value="06">June (06)</option>
                      <option value="07">July (07)</option>
                      <option value="08">Aug (08)</option>
                      <option value="09">Sep (09)</option>
                      <option value="10">Oct (10)</option>
                      <option value="11">Nov (11)</option>
                      <option value="12">Dec (12)</option>
                    </select>
                  </div>
                  <div class="col-xs-3">
                    <select class="form-control" name="expiry-year">
                      <option value="13">2013</option>
                      <option value="14">2014</option>
                      <option value="15">2015</option>
                      <option value="16">2016</option>
                      <option value="17">2017</option>
                      <option value="18">2018</option>
                      <option value="19">2019</option>
                      <option value="20">2020</option>
                      <option value="21">2021</option>
                      <option value="22">2022</option>
                      <option value="23">2023</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label" for="cvv">Card CVV</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="cvv" id="cvv" placeholder="Security Code">
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-3 col-sm-9">
                <input type="submit" name="submit" class="btn btn-success" value="Pay Now">
              </div>
            </div>
          </fieldset>
          <p>Disclaimer: u hoeft niks in te vullen. Dit form is maar een dummy. Klik op 'Pay Now' om premium te gaan worden.</p>
        </form>
      <?php } else { ?>
        <p><a href='login.php'>Login</a> of <a href='register.php'>meld je aan</a> om premium te gaan.</p>
      <?php } ?>
    </div>
  </div>
</div>

</body>

<!--Scripts-->
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-select.min.js"></script>
</html>
