<?php
/**
 * generate-og-image.php — Generador de Open Graph image (1200×630 px)
 * Paleta idéntica al portafolio: fondo cream, violeta #7C65F6, texto noir.
 *
 * Ejecutar una sola vez desde CLI:
 *   php generate-og-image.php
 *
 * Salida: public/og-image.png
 */

$W   = 1200;
$H   = 630;
$out = __DIR__ . '/public/og-image.png';

$img = imagecreatetruecolor($W, $H);
imagesavealpha($img, true);

/* ── Paleta del portafolio ────────────────────────────────────── */
$cream       = imagecolorallocate($img, 242, 241, 237);  // #F2F1ED
$noir        = imagecolorallocate($img, 17,  17,  24);   // #111118
$violet      = imagecolorallocate($img, 124, 101, 246);  // #7C65F6
$violet_lt   = imagecolorallocate($img, 237, 233, 254);  // #EDE9FE
$violet_dark = imagecolorallocate($img, 91,  69,  212);  // #5B45D4
$muted       = imagecolorallocate($img, 107, 107, 120);  // #6B6B78
$border      = imagecolorallocate($img, 228, 227, 223);  // #E4E3DF
$white       = imagecolorallocate($img, 255, 255, 255);
$graphite    = imagecolorallocate($img, 12,  12,  15);   // #0C0C0F

/* ── Fondo cream ──────────────────────────────────────────────── */
imagefilledrectangle($img, 0, 0, $W, $H, $cream);

/* ── Dots grid (igual que el hero del portafolio) ─────────────── */
$dot = imagecolorallocate($img, 228, 227, 223); // #E4E3DF
for ($x = 0; $x <= $W; $x += 32) {
    for ($y = 0; $y <= $H; $y += 32) {
        imagefilledellipse($img, $x, $y, 2, 2, $dot);
    }
}

/* ── Blob violeta (esquina superior derecha) ──────────────────── */
for ($r = 340; $r >= 0; $r -= 6) {
    $alpha = (int) (120 + ($r / 340) * 7 - ($r / 340) * 127);
    $alpha = max(0, min(127, (int)(127 - ($r / 340) * 117)));
    $c = imagecolorallocatealpha($img, 124, 101, 246, $alpha);
    imagefilledellipse($img, $W + 20, -20, $r * 2, $r * 2, $c);
}

/* ── Borde exterior sutil ─────────────────────────────────────── */
imagerectangle($img, 0, 0, $W - 1, $H - 1, $border);

/* ── Línea decorativa vertical izquierda (violeta) ───────────── */
imagefilledrectangle($img, 80, 90, 84, 230, $violet);

/* ── Fuentes ──────────────────────────────────────────────────── */
$font_dir   = __DIR__ . '/assets/fonts/';
$font_syne  = $font_dir . 'Syne-ExtraBold.ttf';
$font_inter = $font_dir . 'Inter-Regular.ttf';

/* ── Badge "Disponible" ───────────────────────────────────────── */
$badge_x = 104;
$badge_y = 110;
$badge_w = 350;
$badge_h = 32;

// Fondo del badge (violet-lt #EDE9FE)
imagefilledroundedrect($img, $badge_x, $badge_y, $badge_x + $badge_w, $badge_y + $badge_h, 16, $violet_lt);
// Borde del badge
imageroundedrect($img, $badge_x, $badge_y, $badge_x + $badge_w, $badge_y + $badge_h, 16, $violet);

// Dot dentro del badge
imagefilledellipse($img, $badge_x + 18, $badge_y + 16, 10, 10, $violet);

// Texto del badge
if (file_exists($font_inter)) {
    imagettftext($img, 11, 0, $badge_x + 32, $badge_y + 22, $violet, $font_inter, 'Disponible para incorporacion inmediata');
} else {
    imagestring($img, 3, $badge_x + 32, $badge_y + 9, 'Disponible para incorporacion inmediata', $violet);
}

/* ── Nombre principal ─────────────────────────────────────────── */
if (file_exists($font_syne)) {
    imagettftext($img, 78, 0, 98, 270, $noir,   $font_syne, 'Roberto Carlos');
    imagettftext($img, 78, 0, 98, 372, $violet, $font_syne, 'Vazquez Antelo');
} else {
    imagestring($img, 5, 98, 200, 'Roberto Carlos Vazquez Antelo', $noir);
}

/* ── Rol / subtítulo ──────────────────────────────────────────── */
$rol = 'Desarrollador Full Stack   ·   Analisis de Sistemas';
if (file_exists($font_inter)) {
    imagettftext($img, 20, 0, 100, 422, $muted, $font_inter, $rol);
} else {
    imagestring($img, 4, 100, 340, $rol, $muted);
}

/* ── Tech tags ────────────────────────────────────────────────── */
$techs = ['PHP', 'JavaScript', 'MySQL', 'Tailwind CSS', 'REST APIs', 'Git'];
$tx = 100;
$ty = 462;
$th = 32;

foreach ($techs as $tech) {
    if (file_exists($font_inter)) {
        $bbox = imagettfbbox(11, 0, $font_inter, $tech);
        $tw = abs($bbox[2] - $bbox[0]) + 28;
    } else {
        $tw = strlen($tech) * 9 + 24;
    }

    // Fondo tag (violet-lt)
    imagefilledroundedrect($img, $tx, $ty, $tx + $tw, $ty + $th, 8, $violet_lt);
    // Borde tag
    imageroundedrect($img, $tx, $ty, $tx + $tw, $ty + $th, 8, $violet);

    if (file_exists($font_inter)) {
        imagettftext($img, 11, 0, $tx + 14, $ty + 22, $violet, $font_inter, $tech);
    } else {
        imagestring($img, 2, $tx + 8, $ty + 10, $tech, $violet);
    }
    $tx += $tw + 10;
}

/* ── Separador horizontal ─────────────────────────────────────── */
imagefilledrectangle($img, 100, $H - 95, $W - 80, $H - 94, $border);

/* ── URL del sitio ────────────────────────────────────────────── */
if (file_exists($font_inter)) {
    imagettftext($img, 15, 0, 100, $H - 55, $muted, $font_inter, 'rcvazquezz.com');
} else {
    imagestring($img, 3, 100, $H - 65, 'rcvazquezz.com', $muted);
}

/* ── Monograma RV (esquina inferior derecha) ──────────────────── */
$mx = $W - 130;
$my = $H - 115;
$ms = 84;
imagefilledroundedrect($img, $mx, $my, $mx + $ms, $my + $ms, 12, $graphite);
if (file_exists($font_syne)) {
    imagettftext($img, 28, 0, $mx + 14, $my + 55, $white, $font_syne, 'RV');
} else {
    imagestring($img, 5, $mx + 22, $my + 30, 'RV', $white);
}

/* ── Línea inferior violeta ───────────────────────────────────── */
imagefilledrectangle($img, 0, $H - 5, $W, $H, $violet);

/* ── Guardar ──────────────────────────────────────────────────── */
imagepng($img, $out, 6);
imagedestroy($img);
echo "og-image generada: $out\n";

/* ══════════════════════════════════════════════════════════════
   Helpers: rectángulo y borde redondeados
══════════════════════════════════════════════════════════════ */
function imagefilledroundedrect($img, $x1, $y1, $x2, $y2, $r, $color) {
    imagefilledrectangle($img, $x1 + $r, $y1,      $x2 - $r, $y2,      $color);
    imagefilledrectangle($img, $x1,      $y1 + $r, $x2,      $y2 - $r, $color);
    imagefilledellipse($img, $x1 + $r, $y1 + $r, $r * 2, $r * 2, $color);
    imagefilledellipse($img, $x2 - $r, $y1 + $r, $r * 2, $r * 2, $color);
    imagefilledellipse($img, $x1 + $r, $y2 - $r, $r * 2, $r * 2, $color);
    imagefilledellipse($img, $x2 - $r, $y2 - $r, $r * 2, $r * 2, $color);
}

function imageroundedrect($img, $x1, $y1, $x2, $y2, $r, $color) {
    imageline($img, $x1 + $r, $y1,      $x2 - $r, $y1,      $color);
    imageline($img, $x1 + $r, $y2,      $x2 - $r, $y2,      $color);
    imageline($img, $x1,      $y1 + $r, $x1,      $y2 - $r, $color);
    imageline($img, $x2,      $y1 + $r, $x2,      $y2 - $r, $color);
    imagearc($img, $x1 + $r, $y1 + $r, $r * 2, $r * 2, 180, 270, $color);
    imagearc($img, $x2 - $r, $y1 + $r, $r * 2, $r * 2, 270, 360, $color);
    imagearc($img, $x1 + $r, $y2 - $r, $r * 2, $r * 2,  90, 180, $color);
    imagearc($img, $x2 - $r, $y2 - $r, $r * 2, $r * 2,   0,  90, $color);
}
