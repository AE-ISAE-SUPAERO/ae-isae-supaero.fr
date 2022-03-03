<?php
/*
  ********************************************************************************************
  CONFIGURATION
  ********************************************************************************************
*/
// destinataire est votre adresse mail. Pour envoyer à plusieurs à la fois, séparez-les par un point-virgule
$destinataire = 'claire.ferraton@student.isae-supaero.fr;theo.roucaute@student.isae-supaero.fr';

// copie ? (envoie une copie au visiteur)
$copie = 'oui'; // 'oui' ou 'non'

// Messages de confirmation du mail
$message_envoye = "Merci pour votre message. Nous y répondrons dans les plus brefs délais !";
$message_non_envoye = "L'envoi du mail a échoué, veuillez réessayer.";

// Messages d'erreur du formulaire
$message_erreur_formulaire = "Vous devez d'abord <a href=\"\contact\">envoyer le formulaire</a>.";
$message_formulaire_invalide = "Vérifiez que tous les champs soient bien remplis et que l'email soit sans erreur.";

/*
  ********************************************************************************************
  FIN DE LA CONFIGURATION
  ********************************************************************************************
*/

//Captcha

// Ma clé privée
$secret = "6LfC6qQZAAAAAC021QhQ82_mgkoExwI6aeoqrBd5";
// Paramètre renvoyé par le recaptcha
$response = $_POST['g-recaptcha-response'];
// On récupère l'IP de l'utilisateur
$remoteip = $_SERVER['REMOTE_ADDR'];

$api_url = "https://www.google.com/recaptcha/api/siteverify?secret="
  . $secret
  . "&response=" . $response
  . "&remoteip=" . $remoteip ;

$decode = json_decode(file_get_contents($api_url), true);

if ($decode['success'] == true) {
  // C'est un humain
  if (!isset($_POST['envoi']))
  {
    // formulaire non envoyé
    echo '<p>'.$message_erreur_formulaire.'</p>'."\n";
  }
  else
  {
    /*
    * cette fonction sert à nettoyer et enregistrer un texte
    */
    function Rec($text)
    {
      $text = htmlspecialchars(trim($text), ENT_QUOTES);
      if (1 === get_magic_quotes_gpc())
      {
        $text = stripslashes($text);
      }

      $text = nl2br($text);
      return $text;
    };

    /*
    * Cette fonction sert à vérifier la syntaxe d'un email
    */
    function IsEmail($email)
    {
      $value = preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $email);
      return (($value === 0) || ($value === false)) ? false : true;
    }

    // formulaire envoyé, on récupère tous les champs.
    $nom     = (isset($_POST['nom']))     ? Rec($_POST['nom'])     : '';
    $email   = (isset($_POST['email']))   ? Rec($_POST['email'])   : '';
    $objet   = (isset($_POST['objet']))   ? Rec($_POST['objet'])   : '';
    $message = (isset($_POST['message'])) ? Rec($_POST['message']) : '';

    // On va vérifier les variables et l'email ...
    $email = (IsEmail($email)) ? $email : ''; // soit l'email est vide si erroné, soit il vaut l'email entré

    if (($nom != '') && ($email != '') && ($objet != '') && ($message != ''))
    {
      // les 4 variables sont remplies, on génère puis envoie le mail
      $headers  = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'From:'.$nom.' <'.$email.'>' . "\r\n" .
          'Reply-To:'.$email. "\r\n" .
          'Content-Type: text/plain; charset="utf-8"; DelSp="Yes"; format=flowed '."\r\n" .
          'Content-Disposition: inline'. "\r\n" .
          'Content-Transfer-Encoding: 7bit'." \r\n" .
          'X-Mailer:PHP/'.phpversion();

      $message2 = "Votre message au BDE de Supaero : \r\n \r\n" ;
      $message = $message2 . $message ;

      // envoyer une copie au visiteur ?
      if ($copie == 'oui')
      {
        $cible = $destinataire.';'.$email;
      }
      else
      {
        $cible = $destinataire;
      };

      // Remplacement de certains caractères spéciaux
      $caracteres_speciaux     = array('&#039;', '&#8217;', '&quot;', '<br>', '<br />', '&lt;', '&gt;', '&amp;', '…',   '&rsquo;', '&lsquo;');
      $caracteres_remplacement = array("'",      "'",        '"',      '',    '',       '<',    '>',    '&',     '...', '>>',      '<<'     );

      $objet = "[ae-isae-supaero.fr] " . html_entity_decode($objet);
      $objet = str_replace($caracteres_speciaux, $caracteres_remplacement, $objet);

      $message = html_entity_decode($message);
      $message = str_replace($caracteres_speciaux, $caracteres_remplacement, $message);

      // Envoi du mail
      $num_emails = 0;
      $tmp = explode(';', $cible);
      foreach($tmp as $email_destinataire)
      {
        if (mail($email_destinataire, $objet, $message, $headers))
          $num_emails++;
      }

      if ((($copie == 'oui') && ($num_emails == 2)) || (($copie == 'non') && ($num_emails == 1)))
      {
        // echo '<p>'.$message_envoye.'</p>';
        print ("<script language = \"JavaScript\">");
        print ("location.href = '/contact-success';");
        print ("</script>");
      }
      else
      {
        echo '<p>'.$message_non_envoye.'</p>';
      };
    }
    else
    {
      // une des 3 variables (ou plus) est vide ...
      echo '<p>'.$message_formulaire_invalide.' <a href="/contact">Retour au formulaire</a></p>'."\n";
    };
  }; // fin du if (!isset($_POST['envoi']))

}
else {
  // C'est un robot ou le code de vérification est incorrecte
  echo '<p>Une erreur est survenue, veuillez réessayer.</p>' ;
} ;




?>