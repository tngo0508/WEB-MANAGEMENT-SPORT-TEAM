<?php
     session_start();
     if ($_SESSION['type'] !== 'M') {
       $_SESSION = array();
       session_destroy();
       header("location: welcome.php");
       exit;
     }
     if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
         header("location: welcome.php");
         exit;
     }
     if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
         header("location: welcome.php");
         exit;
     }
     $user_id = $_SESSION['id'];
     require_once 'config.php';
     $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

     // Check connection
     if ($link === false) {
         die("ERROR: Could not connect. " . mysqli_connect_error());
     }

     $query = "SELECT TEAM_NAME FROM MANAGER, TEAMS WHERE MUSER_ID = ? AND MANAGER_ID = TMANAGER_ID";
     $query = $link->prepare($query);
     $query->bind_param('i', $user_id);
     $query->execute();
     $query->store_result();
     $query->bind_result($team_name);
     $query->fetch();
     $_SESSION['team'] = $team_name;
     $query->free_result();
     $link->close();
?>

  <head>
    <meta charset="utf-8">
    <title>Manager Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Tangerine:bold,bolditalic|Inconsolata:italic|Droid+Sans|Oxygen|Passion+One|Alfa+Slab+One|Monoton|Ubuntu">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Rancho&effect=shadow-multiple|3d-float|fire-animation|fire|neon">
    <link rel="stylesheet" href="css/manager.css">
  </head>
  <?php require "header.php"; ?>



  <body>
    <nav id="navbar-manager" class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
      <a class="navbar-brand" href="manager_page.php">TEAM MANAGEMENT</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span>
  </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
          <!-- <li class="nav-item active">
            <a class="nav-link" href="#TOP">TOP<span class="sr-only">(current)</span></a>
          </li> -->
          <li class="nav-item">
            <a class="nav-link" href="#schedule-game">Schedule Game</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#game-played">Games Played</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#upcoming-game">Upcoming Games</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#view-player">View Player</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#add-player">Add Player</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#game-playing">Add/Remove</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#view-stats">View Stats</a>
          </li>
        </ul>
        <ul class="navbar-nav my-2 my-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="#">Team <?php echo $_SESSION['team']; ?> </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
              <?php echo $_SESSION['email']; ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="check_profileEmpty.php">Profile</a>
              <a class="dropdown-item" href="logout.php">Sign out</a>
              <a class="dropdown-item" href="#">Something else here</a>
            </div>
          </li>
        </ul>

      </div>
    </nav>
    <div data-spy="scroll" data-target="#navbar-manager" data-offset="0">
      <h1 id="schedule-game">SCHEDULE ALL GAMES</h1>
      <div class="container">
        <?php require 'schedule_game.php'; ?>
      </div>

      <h1 id="game-played">GAMES PLAYED</h1>
      <div class="container">
        <?php require 'view_game_played.php' ?>
      </div>

      <!-- use for anchor tag -->
      <a name="form-add-player"></a>

      <h1 id="upcoming-game">UPCOMING GAMES</h1>
      <div class="container">
        <?php require 'view_upcoming_games.php'; ?>
      </div>

      <h1 id="add-player">ADD PLAYER</h1>
      <div class="container">
        <?php require 'addingplayer.php'; ?>
      </div>

      <form class="form-inline" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"].'#form-add-player'); ?>" method="post">
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text">FIRST NAME</div>
          </div>
            <!-- <label>First Name:<sup>*</sup></label> -->
          <input type="text" name="firstname" class="form-control" value="" placeholder="Player's first name" required>
        </div>
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text">LAST NAME</div>
          </div>
          <!-- <label>Last Name:<sup>*</sup></label> -->
          <input type="text" name="lastname" class="form-control" value="" placeholder="Player's last name" required>
        </div>

        <div class="form-check">
          <input id="submit" type="submit" class="btn btn-primary" value="Add">
          <input type="reset" class="btn btn-default" value="Reset">
        </div>
      </form>
      <h1 id="view-player">TEAM PLAYERS</h1>
      <div class="container">
        <table class="table table-bordered table-hover">
<thead class="thead-dark">
  <tr class="info">
    <th scope="col">PLAYER ID</th>
    <th scope="col">FIRST NAME</th>
    <th scope="col">LAST NAME</th>
    <!-- <th scope="col">ACTION</th> -->
    </tr>
</thead>
<?php require 'vrplayer_bymanager.php'; ?>
</table>

      </div>
      <h1 id="game-playing">ADD AND REMOVE PLAYER FROM GAME</h1>
      <div class="container">
        <?php require 'game_playing.php'; ?>
      </div>
      <h1 id="view-stats">VIEW STATS</h1>
      <div class="container">
        <?php require 'view_stats.php'; ?>
      </div>
    </div>
</div>

    </section>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>

  <?php
  require "footer.php";
  ?>
