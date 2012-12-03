<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Nachricht gesendet</title>
</head>

<body style="background-color: #808080">
<?PHP

if ($_POST[nachricht]) {

// hier findet man die mail Zeile mit der Adresse an die der Inhalt des Formular gesendet wird.
   mail("j_witt10@uni-muenster.de","Nachricht von $_POST[EMail]",$_POST[nachricht]);

// hier findet man schreibt man die Ausgabemeldungen die der Benutzer nach dem senden der Mail in seinem Browser angezeigt werden.    
   echo "Danke für ihre Angaben!<br>";

   echo "Ihre Angaben wurden per Mail an den Administrator übermittelt:<br>";
   echo "Betreff: $_POST[Betreff]
   echo "Email: $_POST[EMail]<br>";
   echo "Nachricht: $_POST[nachricht]<br>";	 

} else {

// hier findet die Fehlerbehandlung statt, falls das Formular nicht korrekt gesendet werden konnte. Meldung ausgeben usw...
   echo "Fehler bei der Übermittlung ihrer Angaben, wenden sie sich bitte per Email an j_witt10@uni-muenster.de<br>";
   
}

?>

</body>
</html>
