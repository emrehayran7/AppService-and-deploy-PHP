<?php
session_start();

// Set default values for session variables
if (!isset($_SESSION['range'])) {
  $_SESSION['range'] = 20;
}
if (!isset($_SESSION['number'])) {
  $_SESSION['number'] = rand(0, $_SESSION['range'] - 1);
}
if (!isset($_SESSION['attempts'])) {
  $_SESSION['attempts'] = 0;
}

// Set default values for cookie variables
$default_bg_color = 'white';
$default_font_size = '12pt';
$default_player_name = 'Player';
if (!isset($_COOKIE['bg_color'])) {
  setcookie('bg_color', $default_bg_color, time() + 30); // validity period of 30 seconds
}
if (!isset($_COOKIE['font_size'])) {
  setcookie('font_size', $default_font_size, time() + 30); // validity period of 30 seconds
}
if (!isset($_COOKIE['player_name'])) {
  setcookie('player_name', $default_player_name, time() + 30); // validity period of 30 seconds
}

// Process form submissions
if (isset($_POST['range_submit'])) {
  $_SESSION['range'] = (int) $_POST['range'];
  $_SESSION['number'] = rand(0, $_SESSION['range'] - 1);
  $_SESSION['attempts'] = 0;
}
if (isset($_POST['draw_submit'])) {
  $_SESSION['number'] = rand(0, $_SESSION['range'] - 1);
  $_SESSION['attempts'] = 0;
}
if (isset($_POST['guess_submit'])) {
  $guess = (int) $_POST['guess'];
  $_SESSION['attempts']++;
  if ($guess < $_SESSION['number']) {
    $message = 'Your guess is too small';
  } elseif ($guess > $_SESSION['number']) {
    $message = 'Your guess is too large';
  } else {
    $message = 'You guessed correctly';
    
  }
}

// Set variables for page appearance
$bg_color = isset($_COOKIE['bg_color']) ? $_COOKIE['bg_color'] : $default_bg_color;
$font_size = isset($_COOKIE['font_size']) ? $_COOKIE['font_size'] : $default_font_size;
$player_name = isset($_COOKIE['player_name']) ? $_COOKIE['player_name'] : $default_player_name;

?>
<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            background-color: <?php echo $bg_color; ?>;
          font-size: <?php echo $font_size; ?>;
        }
    </style>
</head>

<body>
    <h1>Welcome to the Guessing Game,
        <?php echo $player_name; ?>!</h1>
    <p>In this game, you will try to guess a number between 0 and
        <?php echo $_SESSION['range'] - 1; ?>.</p>
    <p>Here is your guessing story so far:</p>
    <ul>
        <?php
if ($_SESSION['attempts'] == 0) {
  echo '<li>You have not made any guesses yet.</li>';
} else {
  if (isset($message) && $message == 'You guessed correctly') {
    echo '<li>It took you ' . $_SESSION['attempts'] . ' attempts to guess the number ' . $_SESSION['number'] . '.</li>';
    $_SESSION['attempts'] = 0;

  } else {
    echo '<li>You have made ' . $_SESSION['attempts'] . ' ' . ($_SESSION['attempts'] == 1 ? 'attempt' : 'attempts') . '.</li>';
    if (isset($message)) {
      echo '<li>' . $message . '</li>';
    }
  }
}
?>
    </ul>
    <form method="post">
        <h2>Set range</h2>
        <p>Enter a value for n:</p>
        <input type="number" name="range" min="1" value="<?php echo $_SESSION['range']; ?>">
        <input type="submit" name="range_submit" value="Set range">
    </form>
    <form method="post">
        <h2>Draw a number</h2>
        <input type="submit" name="draw_submit" value="Draw a number">
    </form>
    <form method="post">
        <h2>Make a guess</h2>
        <p>Enter your guess:</p>
        <input type="number" name="guess" min="0" max="<?php echo $_SESSION['range'] - 1; ?>">
        <input type="submit" name="guess_submit" value="Make a guess">
    </form>
    <hr>
    <form method="post">
        <h2>Control page appearance</h2>
        <p>Background color:</p>
        <input type="color" name="bg_color" value="<?php echo isset($_COOKIE['bg_color']) ? $_COOKIE['bg_color'] : $default_bg_color; ?>">
        <input type="submit" name="bg_color_submit" value="Set background color">
        <p>Font size:</p>
        <input type="range" name="font_size" min="10" max="20" step="2" value="<?php echo isset($_COOKIE['font_size']) ? str_replace('pt', '', $_COOKIE['font_size']) : $default_font_size; ?>">
        <input type="submit" name="font_size_submit" value="Set font size">
        <p>Player name:</p>
        <input type="text" name="player_name" value="<?php echo isset($_COOKIE['player_name']) ? $_COOKIE['player_name'] : $default_player_name; ?>">
        <input type="submit" name="player_name_submit" value="Set player name">
    </form>
</body>

</html>

<?php
if (isset($_POST['bg_color_submit'])) {
    setcookie('bg_color', $_POST['bg_color'], time() + 30);
    header('Location: '.$_SERVER['PHP_SELF']);
}
if (isset($_POST['font_size_submit'])) {
    setcookie('font_size', $_POST['font_size'] . 'pt', time() + 30);
    header('Location: '.$_SERVER['PHP_SELF']);
}
if (isset($_POST['player_name_submit'])) {
    setcookie('player_name', $_POST['player_name'], time() + 30);
    header('Location: '.$_SERVER['PHP_SELF']);
}
?>

<?php

// Print all cookies
print_r($_COOKIE);

// Print session cookie
print_r($_SESSION);

?>
<form method="post">
    <input type="submit" name="delete_cookies" value="Delete Cookies">
</form>

<?php

if (isset($_POST['delete_cookies'])) {
    // Delete all cookies
    foreach ($_COOKIE as $key => $value) {
        setcookie($key, '', time() - 3600);
    }
    // Destroy session
    session_destroy();
}

?>