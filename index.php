<?php

// AVAILABLE PAGES (allowed request => [html file to include, page title])
// all html files have to be in the includes directory
const PAGES = [
  '/' => ['home.html', ''],
  '/index.php' => ['home.html', ''],
  '/home' => ['home.html', ''],
  '/admissibles' => ['admissibles.html', 'Admissibles'],
  '/alpha' => ['alpha.html', 'Video alpha'],
  '/bde' => ['bde.html', 'Équipe BDE'],
  '/campus' => ['campus.html', 'Campus'],
  '/clubs' => ['clubs.html', 'Clubs'],
  '/contact-success' => ['contact-success.html', 'Contactez nous'],
  '/contact' => ['contact.html', 'Contactez nous'],
  '/english' => ['english.html', ''],
  '/galerie' => ['galerie.html', 'Galerie'],
  '/mot-des-eleves' => ['mot-des-eleves.html', 'Mot des élèves'],
  '/partenaires' => ['partenaires.html', 'Partenaires'],
  '/prez-international' => ['prez-international.html', 'Mot du prez international'],
  '/prez' => ['prez.html', 'Mot du prez'],
  '/clubs/clubs-aa' => ['clubs-aa.html', 'Clubs AA'],
  '/clubs/clubs-ae' => ['clubs-ae.html', 'Clubs AE'],
  '/clubs/clubs-as' => ['clubs-as.html', 'Clubs AS'],
  '/clubs/clubs-inde' => ['clubs-inde.html', 'Clubs indépendants'],
  '/mentions-legales' => ['mentions-legales.html', 'Mentions légales'],
];

// PARSE REQUESTED PAGE (404 elsewise)
$request_uri = rtrim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), '/');
if (empty($request_uri)) {
  $html = 'home.html';
} else if (array_key_exists($request_uri, PAGES)) {
  $html = PAGES[$request_uri][0];
  $page_title = PAGES[$request_uri][1];
} else {
  header('Location: /404.html');
  exit();
}
$is_home = $html == 'home.html' || $html == 'english.html';

?>

<!doctype html>
<html>

<head>
  <meta charset="utf-8" />
  <title>AE ISAE-SUPAERO <?php if (!empty($page_title)) {
                            echo (' - ' . $page_title);
                          } ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="icon" type="image/x-icon" href="favicon.ico" />

  <!-- STYLESHEET -->
  <?php if ($html == 'campus.html') { ?>
    <link rel="stylesheet" href="/assets/css/campus.css" />
  <?php } else if (in_array($html, ['clubs.html', 'clubs-aa.html', 'clubs-as.html', 'clubs-ae.html', 'clubs-inde.html'])) { ?>
    <link rel="stylesheet" href="/assets/css/clubs.css" />
  <?php } else { ?>
    <link rel="stylesheet" href="/assets/css/main.css" />
  <?php } ?>

  <?php if ($html == 'contact.html') { ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <?php } ?>

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-EJRXPNP7PM"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-EJRXPNP7PM');
  </script>
</head>

<body <?php if ($is_home) {
        echo 'class="landing"';
      } ?>>

  <!-- HEADER -->
  <header id="header" <?php if ($is_home) {
                        echo 'class="alt"';
                      } ?>>
    <h1>
      <a href="/">Association des élèves <strong>ISAE-Supaero</strong></a>
    </h1>
    <nav id="nav">
      <ul>
        <li>
          <a href="/admissibles"><span>Admissibles</span></a>
        </li>
        <li>
          <a href="/clubs"><span>Clubs</span></a>
        </li>
        <li>
          <a href="/campus"><span>Campus</span></a>
        </li>
        <li>
          <a href="/galerie"><span>Galerie</span></a>
        </li>
        <li>
          <a href="https://shop.ae-isae-supaero.fr">Boutique</a>
        </li>
        <li>
          <a href="https://student.ae-isae-supaero.fr/"><span>Espace Adhérents</span></a>
        </li>
      </ul>
    </nav>
  </header>

  <!-- Menu display button for small screens -->
  <a href="#menu" class="navPanelToggle"><span class="fa fa-bars"></span></a>
</body>

<!-- BODY -->
<?php include 'includes/' . $html; ?>

<!-- FOOTER -->
<footer id="footer">
  <div class="container">
    <ul>
      <span class="logo">
        <a href="bde">
          <img src="/images/logo_fond_blanc_transparent_250.png" />
        </a>
      </span>
    </ul>
    <ul class="icons">
      <li>
        <a href="https://www.facebook.com/BDE.Supaero/" class="icon fa-facebook"></a>
      </li>
      <li>
        <a href="https://www.instagram.com/bde_isae_supaero/" class="icon fa-instagram"></a>
      </li>
      <li>
        <a href="https://www.linkedin.com/company/bde-isae-supaero/" class="icon fa-linkedin"></a>
      </li>
    </ul>
    <ul class="assos">
      <li><a href="/contact">Nous contacter</a></li>
      <li><a href="/partenaires">Nos partenaires</a></li>
      <li><a href="https://www.as-isae-supaero.com/">Site de l'AS</a></li>
      <li><a href="https://bda-isae-supaero.fr/">Site de l'AA</a></li>
      <li><a href="/mentions-legales">Mentions Légales</a></li>
    </ul>
    <ul class="copyright">
      <li>
        <p>&#169; 2020-<?php echo (date('Y')); ?> AE ISAE-SUPAERO</p>
      </li>
    </ul>
  </div>
  <div class="english">
    <a href="./english" class="icon fa-globe"> English</a>
  </div>
</footer>

<!-- SCRIPTS -->
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/skel.min.js"></script>
<script src="/assets/js/util.js"></script>
<script src="/assets/js/main.js"></script>
<?php if ($is_home) {
  echo ('<script src="/assets/js/first_visit.js"></script>');
} ?>

</html>