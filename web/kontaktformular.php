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

  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;600&display=swap" rel="stylesheet">


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
      //Versendetnachrict ausblenden
      echo "<style>.versendet { display:none; }</style>";
      //Initialisierung der Variabeln
      $nameErr = $vornameErr = $emailErr = $nachrichtErr = "";
      $name = $vorname = $email = $nachricht = $ausgabe = "";
      $titel = "Kontakt";
      //Wenn senden gedrückt wurde
      if (isset($_POST['senden'])) {
        //Name überprüfung
        if (empty($_POST["name"])) {
          $nameErr = "Bitte geben sie einen Name ein.";
        } else {
          $name = test_input($_POST["name"]);
          //Auf RegEx kontrolieren
          if (!preg_match("/^[A-ZÀ-Ÿ][A-zÀ-ÿ']{2,}$/", $name)) {
            $nameErr = "Es sind nur Buchstaben erlaubt.";
          }
        }
        //Vorname überprüfung
        if (empty($_POST["vorname"])) {
          $vornameErr = "Bitte geben sie einen Vornamen ein.";
        } else {
          $vorname = test_input($_POST["vorname"]);
          //Auf RegEx kontrolieren
          if (!preg_match("/^[A-ZÀ-Ÿ][A-zÀ-ÿ']{2,}$/", $vorname)) {
            $vornameErr = "Nur Buchstaben sind erlaubt.";
          }
        }
        //Nachrichtenfeld überprüfung
        if (empty($_POST["nachricht"])) {
          $nachrichtErr = "Bitte geben sie eine Nachricht ein.";
        } else {
          $nachricht = test_input($_POST["nachricht"]);
        }
        //Email überprüfung
        if (empty($_POST["email"])) {
          $emailErr = "Bitte geben sie eine verfügbare Email ein.";
        } else {
          $email = test_input($_POST["email"]);
          //Auf RegEx kontrolieren
          if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Geben sie eine formal gerechte email ein.";
          }
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
       if ($nameErr === '' && $vornameErr === '' && $emailErr === '' && $nachrichtErr === '' && isset($_POST['senden'])) {
        $ausgabe = "Name: $name <br> Vorname: $vorname <br> Email: $email <br> Nachricht: $nachricht";
        $name = "";
        $vorname = "";
        $nachricht = "";
        $email = "";
      }
      //Änderung der Hintergrundfarbe nach Auswahl des Users

      ?>
      <div id="Mail">
        <?php echo "<h1>$titel</h1>"; ?>
        <p style="font-size: 12px; font-weight: bold;">*Pflichtfelder</p>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" accept-charset="utf-8">
          <!-- Erstellt die Einzelnen Eingaben -->
          <label>Name * <span class="error"><?php echo $nameErr; ?></span><br>
            <input  type="text" id="name"  name="name" value="<?php echo $name ?>" placeholder="" s>
          </label><br><br><br>
          <label>Vorname * <span class="error"><?php echo $vornameErr; ?></span><br>
            <input type="text" id="vorname" name="vorname" value="<?php echo $vorname ?>" placeholder="">
          </label><br><br><br>
          <label>Email * <span class="error"><?php echo $emailErr; ?></span><br>
            <input type="email" id="email" name="email" value="<?php echo $email ?>" placeholder="">
          </label><br><br><br>
          <label>Nachricht * <span class="error"><?php echo $nachrichtErr; ?></span>
            <textarea name="nachricht" id="nachricht" name="nachricht" cols="50" rows="6" value="<?php echo $nachricht ?>"></textarea>
          </label>
          <br><br>
          <!--Absenden-->
          <div style="display: flex; flex-direction: row; align-items: center;">
            <input id="submit" type="submit" name="senden" value="senden">
            <div class="versendet" style="font-size: 20px; margin-left: 10px;"><p>Die Nachricht wurde versendet</p></div>
            <br><br>
           </div>
        </form>
      </div>
    </div>
    <div class="bestellung">
      <?php
      if ($nameErr === '' && $vornameErr === '' && $emailErr === '' && isset($_POST['senden'])) {
        echo "<style>.versendet { display:block; }</style>"; //Versendet anzeigen
        mail('nick.camenisch@student.ksh.ch', 'Kontakt', $nachricht, "From: $email", "-f$email");
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