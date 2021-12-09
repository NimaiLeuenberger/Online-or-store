<?php
/*
Anmeldeformular versenden über php
23.06.2021, Nick Camenisch

Besonderheiten
-Validierung der Namen, Email Adressen
-Sticky Notes
-Pflichtfelder mit Fehlermeldungen
-Wechsel zwischen HTML und PHP
-Externes stylesheet


*/
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en" class="html">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kontakt • E-Commerce</title>

  <link rel="stylesheet" href="styles/header.css">
  <link rel="stylesheet" href="styles/main.css">
  <link rel="stylesheet" href="styles/footer.css">
  <link rel="stylesheet" href="styles/kontakt.css">

  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">

  <script src="script.js" defer></script>
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>

<body>
  <header>
    <div id="nav-placeholder"></div>
    <script>
      $.get("nav.html", function(data){
      $("#nav-placeholder").replaceWith(data);
      });
    </script>
  </header>
  <main>
    <div class="formular">
      <?php
      //Initialisierung der Variabeln
      $nameErr = $vornameErr = $emailErr = $newsletterErr = $infoErr = $zahlungErr = "";
      $name = $vorname = $email = $newsletter = $info = $zahlung = $ausgabe = "";
      $titel = "Bestellformular";
      //Wenn senden gedrückt wurde
      if (isset($_POST['senden'])) {
        //Name überprüfung
        if (empty($_POST["name"])) {
          $nameErr = "Bitte geben sie einen Name ein.";
        } else {
          $name = test_input($_POST["name"]);
          //Auf RegEx kontrolieren
          if (!preg_match("/^[a-zA-Z-' ]{2,}$/", $name)) {
            $nameErr = "Es sind nur Buchstaben erlaubt.";
          }
        }
        //Vorname überprüfung
        if (empty($_POST["vorname"])) {
          $vornameErr = "Bitte geben sie einen Vornamen ein.";
        } else {
          $vorname = test_input($_POST["vorname"]);
          //Auf RegEx kontrolieren
          if (!preg_match("/^[a-zA-Z-' ]{2,}$/", $vorname)) {
            $vornameErr = "Nur Buchstaben sind erlaubt.";
          }
        }
        //Email überprüfung
        if (empty($_POST["email"])) {
          $emailErr = "Eine Email wird benötigt.";
        } else {
          $email = test_input($_POST["email"]);
          //Auf RegEx kontrolieren
          if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Geben sie eine formal gerechte email ein.";
          }
        }
        //Wenn leer dann fehlerausgabe sonst input verändern um cross script zu verhindern
        if (empty($_POST["newsletter"])) {
          $newsletterErr = "Bitte eine Eingabe betätigen";
        } else {
          $newsletter = test_input($_POST["newsletter"]);
        }
        if (empty($_POST["info"])) {
          $infoErr = "Bitte eine Eingabe betätigen";
          $info = '';
        } else {
          $info = test_input(implode(($_POST["info"]))); //Implode, da Array
        }
        if ($_POST['zahlung'] === '') {
          $zahlungErr = "Bitte wählen sie eine Zahlungsmöglichkeit aus.";
        } else {
          $zahlung = test_input($_POST['zahlung']);
        }
      }
      //Testet auf Cross Scripting
      function test_input($data)
      {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }
       //Bestellung ausgeben
       if ($nameErr === '' && $vornameErr === '' && $emailErr === '' && $infoErr === '' && $newsletterErr === '' && $zahlungErr === '' && isset($_POST['senden'])) {
        $ausgabe = "Name: $name <br> Vorname: $vorname <br> Email: $email <br> Info: $info <br> Zahlungsart: $zahlung <br> Newsletter: $newsletter <br>";
        $name = "";
        $vorname = "";
        $email = "";
        $info = "";
        $newsletter = "";
        $zahlung = "";
      }
      //Änderung der Hintergrundfarbe nach Auswahl des Users

      ?>
      <div id="Mail">
        <?php echo "<h1>$titel</h1>"; ?>
        <p>*Pflichtfelder</p>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" accept-charset="utf-8">
          <!-- Erstellt die Einzelnen Eingaben -->
          <label>Name
            <input  type="text" id="name" class="name" name="name" value="<?php echo $name ?>" placeholder="" s><span class="error">* <?php echo $nameErr; ?></span>
          </label><br><br><br>
          <label>Vorname
            <input type="text" id="vorname" name="vorname" value="<?php echo $vorname ?>" placeholder=""><span class="error">* <?php echo $vornameErr; ?></span>
          </label><br><br><br>
          <label>Email
            <input type="email" id="email" name="email" value="<?php echo $email ?>" placeholder=""><span class="error">* <?php echo $emailErr; ?></span>
          </label><br><br><br>
          <fieldset>
            <legend>Ich bestelle Informationen zu<span class="error">* <?php echo $infoErr; ?></span></legend>
            <label>
              <input type="checkbox" name="info[]" value="PHP" <?php if (strstr($info, 'PHP')) { ?> checked <?php } ?>>PHP
            </label><br>
            <label>
              <input type="checkbox" name="info[]" value="JavaScript" <?php if (strstr($info, 'JavaScript')) { ?> checked <?php } ?>>JavaScript
            </label><br>
            <label>
              <input type="checkbox" name="info[]" value="CSS" <?php if (strstr($info, 'CSS')) { ?> checked <?php } ?>>CSS
            </label>
          </fieldset><br>
          <fieldset>
            <legend>Zahlungsmethode<span class="error">* <?php echo $zahlungErr; ?></span></legend>
            <select name="zahlung">
              <option value="">Bitte auswählen</option>
              <option value="Barzahlung" <?php if ($zahlung == "Barzahlung") { ?> selected <?php  } ?>>Barzahlung</option>
              <option value="Rechnung" <?php if ($zahlung == "Rechnung") { ?> selected <?php  } ?>>Rechnung</option>
              <option value="Sofortüberweisung" <?php if ($zahlung == "Sofortüberweisung") { ?> selected <?php  } ?>>Sofortüberweisung</option>
              <option value="VISA" <?php if ($zahlung == "VISA") { ?> selected <?php  } ?>>VISA</option>
              <option value="PayPal" <?php if ($zahlung == "PayPal") { ?> selected <?php  } ?>>PayPal</option>
              <option value="PaySafeCard" <?php if ($zahlung == "PaySafeCard") { ?> selected <?php  } ?>>PaySafeCard</option>
              <option value="Bitcoin" <?php if ($zahlung == "Bitcoin") { ?> selected <?php  } ?>>Bitcoin</option>
            </select>
          </fieldset><br>
          <fieldset>
            <legend>Ich abboniere den Newsletter<span class="error">* <?php echo $newsletterErr; ?></span></legend>
            <label>
              <input type="radio" id="ja" <?php if (isset($newsletter) && $newsletter == "Ja") echo "checked"; ?> name="newsletter" value="Ja">Ja
            </label><br>
            <label>
              <input type="radio" id="search" <?php if (isset($newsletter) && $newsletter == "Nein") echo "checked"; ?> name="newsletter" value="Nein">Nein
            </label>
          </fieldset>
          <!--
          <fieldset>
            <legend>File</legend>
            <label>
              <form action="/action_page.php">
                <input type="file" id="myFile" name="filename">
                <input type="submit">
              </form>
            </label>
          </fieldset> -->
          <br><br>
          <!--Absenden-->
          <input type="submit" name="senden" value="senden">
          <div class="loader"></div>
        </form>
      </div>
    </div>
    <div class="bestellung">
      <?php
      if ($nameErr === '' && $vornameErr === '' && $emailErr === '' && $infoErr === '' && $newsletterErr === '' && $zahlungErr === '' && isset($_POST['senden'])) {
        echo "<style>.formular { display:none; }</style>"; //Formular ausblenden
        echo "<h2>Bestellung</h2>";
        echo $ausgabe;
        //$msg = "";
        //mail('nick.camenisch@student.ksh.ch', 'Bestellung', $msg, "From: $email", "-f$email");
      }
      ?>
    </div>
  </main>
  <footer>
        <div id="footer-placeholder"></div>
        <script>
            $.get("footer.html", function(data){
                $("#footer-placeholder").replaceWith(data);
            });
        </script>
    </footer>
</body>

</html>