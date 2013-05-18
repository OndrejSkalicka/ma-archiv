<?php
//http://localhost/skriptiky/grafy_gd/grafyGD.php?width=640&height=480&values=((-6,2,6,7,9),(5,3,6,1,8),(-2,-6,-4,0,2))&barvy=((255,0,255),(210,50,50),(200,200,200))&texty=(Dka,Zka,Nka)&carh=5&popisky=(br1na,br2na,br3na,br4na,br5na,br6na,br7na,br8na,%20mesto)

  function rozklad ($string) {
    if (preg_match ('/[^\d,()\s-]/', $string)) {
      trigger_error("Error - chybny format vstupu", 	E_USER_ERROR);
      return;
    }
    
    $string = preg_replace ('/([(,])\(/', '\1 array (', $string);
    
    eval ('$pole = array '.$string.';');
    
    return $pole;
  }
  
  function kresli ($img, $body, $max, $min, $prvku, $col, $text) {
    static $n = -1;
    
    $n ++;
    
    $i = 0;
    $lastX = null;
    $lastY = null;
    
    //legenda
    imagestring($img, 5, WIDTH - LEGENDA, BORDER_T + $n * 20, $text, $col);
    
    foreach ($body as $bod) {
      $x = (int)(PADDING + BORDER_L + $i / ($prvku - 1) * (WIDTH - BORDER_L - BORDER_R - 2 * PADDING - LEGENDA));
      
      $i ++;
      
      $y = (int)(BORDER_T + PADDING + ($bod - $min)/($max - $min)*(HEIGHT - BORDER_B - BORDER_T - 2 * PADDING));
      
      if (!is_null($lastX) && !is_null($lastY)) {
        imageline ($img, $lastX, $lastY, $x, $y, $col);
      }
      
      if (POPISHODNOTY) 
        imagestring($img, 1, $x + 3, $y - 15, $bod, $col);
      
      $lastX = $x;
      $lastY = $y;
    }    
  }
  
  
  define("WIDTH", $_GET['width'] ? $_GET['width'] : 640);
  define("HEIGHT", $_GET['height'] ? $_GET['height'] : 400);
  define("BORDER_L", $_GET['border_l'] ? $_GET['border_l'] : 50);
  define("BORDER_R", $_GET['border_r'] ? $_GET['border_r'] : 20);
  define("BORDER_T", $_GET['border_t'] ? $_GET['border_t'] : 20);
  define("BORDER_B", $_GET['border_b'] ? $_GET['border_b'] : 30);
  define("LEGENDA", $_GET['legenda'] ? $_GET['legenda'] : 30);
  define("PADDING", $_GET['padding'] ? $_GET['padding'] : 1);
  define("POPISHODNOTY", $_GET['popishodnoty'] ? 1 : 0);
  define("DECIMALS", $_GET['decimals'] ? $_GET['decimals'] : 0);
  

  $img = imagecreate(WIDTH, HEIGHT);
  $white = imagecolorallocate($img, 0xFF, 0xFF, 0xFF);
  $black = imagecolorallocate($img, 0, 0, 0);
    
  $body = rozklad ($_GET['values']);
  $barvy = rozklad ($_GET['barvy']);
  $popisky = explode (',', preg_replace ('/[()]/', '', $_GET['popisky']));
  $texty = explode (',', preg_replace ('/[()]/', '', $_GET['texty']));

  // mrizka
  imagerectangle($img, BORDER_L, BORDER_T , WIDTH - BORDER_R - LEGENDA , HEIGHT - BORDER_B , imagecolorallocate($img,0,0,0));
  
  // min max
  $max = null;
  $min = null;
  if (is_array($body[0]))
  {
    $prvku = 0;
    foreach ($body as $radka) {
      $prvku_ = 0;
      foreach ($radka as $bod) {
        $prvku_ ++;
        if ($bod > $max || is_null($max)) $max = $bod;
        if ($bod < $min || is_null($min)) $min = $bod;
      }
      $prvku = max ($prvku, $prvku_);
    }
  } else { 
    $prvku = 0;
    foreach ($body as $bod) {
      $prvku ++;
      if ($bod > $max || is_null($max)) $max = $bod;
      if ($bod < $min || is_null($min)) $min = $bod;
    }
  }
  if (isset($_GET['start'])) $min = $_GET['start'];
  
  //nadpis
  if (isset($_GET['caption'])) {
    imagestring($img, 3, round (WIDTH / 2 - strlen($_GET['caption']) * 3.5 ), 0, $_GET['caption'], imagecolorallocate($img,0,0,0));
  }
  
  // cary
  $car = $_GET['carh'];
  if ($car > 0) {
    for ($i = 0; $i <= $car + 1; $i ++) {
      //popisek cary
      
      $text = number_format(($car - $i  + 1) / ($car + 1) *($max - $min) + $min, DECIMALS);
      
      $y = $i/($car + 1) * (HEIGHT - BORDER_T - BORDER_B) + BORDER_T;
      imagestring($img, 1, 0, $y - 4, $text, imagecolorallocate($img,0,0,0));
      
      if ($i > 0 && $i <= $car) // nekreslim nultou a posledni
        imageline($img, BORDER_L, $y, WIDTH - BORDER_R - LEGENDA, $y, imagecolorallocate($img,0,0,0));
    }
  }
  
  //spodni hodnoty
  if ($popisky) {
    for ($i = 0; $i < $prvku; $i ++) {
      $x = (int)(PADDING + BORDER_L + $i / ($prvku - 1) * (WIDTH - BORDER_L - BORDER_R - 2 * PADDING - LEGENDA));
      imagestring($img, 1, $x, HEIGHT - BORDER_B + 2, $popisky[$i], imagecolorallocate($img,0,0,0));
    }
  }
  
  // vykresleni grafu
  if (is_array ($body[0])) {
    foreach ($body as $key => $radka) 
      kresli ($img, $radka, $min, $max, $prvku, imagecolorallocate($img, $barvy[$key][0], $barvy[$key][1], $barvy[$key][2]), $texty[$key]);
  } else
    kresli ($img, $body, $min, $max, $prvku);
  
  header("Content-type: image/png");
  imagepng($img);
?>
