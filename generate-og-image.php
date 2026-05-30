<?php
/**
 * generate-og-image.php — Generador de Open Graph image (1200×630 px)
 *
 * Ejecutar una sola vez desde CLI:
 *   php generate-og-image.php
 *
 * Salida: public/og-image.png
 */

$W = 1200;
$H = 630;
$out = __DIR__ . '/public/og-image.png';

$img = imagecreatetruecolor($W, $H);
imagesavealpha($img, true);

/* ── Colores ──────────────────────────────────────────────────── */
$noir        = imagecolorallocate($img, 12,  12,  15);
$violet      = imagecolorallocate($img, 124, 101, 246);
$violet_dim  = imagecolorallocate($img, 40,  30,  90);
$violet_mid  = imagecolorallocate($img, 80,  60,  180);
$white       = imagecolorallocate($img, 255, 255, 255);
$white_dim   = imagecolorallocate($img, 180, 180, 190);
$muted       = imagecolorallocate($img, 107, 107, 120);
$graphite    = imagecolorallocate($img, 26,  26,  36);

/* ── Fondo noir ───────────────────────────────────────────────── */
imagefilledrectangle($img, 0, 0, $W, $H, $noir);

/* ── Blob violeta (esquina superior derecha) ──────────────────── */
for ($r = 320; $r >= 0; $r -= 8) {
    $alpha = (int) (127 - ($r / 320) * 100);
    $c = imagecolorallocatealpha($img, 124, 101, 246, $alpha);
    imagefilledellipse($img, $W, 0, $r * 2, $r * 2, $c);
}

/* ── Blob violeta secundario (esquina inferior izquierda) ─────── */
for ($r = 200; $r >= 0; $r -= 8) {
    $alpha = (int) (127 - ($r / 200) * 115);
    $c = imagecolorallocatealpha($img, 124, 101, 246, $alpha);
    imagefilledellipse($img, 0, $H, $r * 2, $r * 2, $c);
}

/* ── Línea decorativa vertical izquierda ─────────────────────── */
imagefilledrectangle($img, 80, 100, 84, 260, $violet);

/* ── Dot pulsante (disponibilidad) ───────────────────────────── */
$dot_x = 104;
$dot_y = 120;
imagefilledellipse($img, $dot_x, $dot_y, 12, 12, $violet);

/* ── Badge "Disponible para incorporación inmediata" ──────────── */
$badge_text  = ' Disponible para incorporacion inmediata ';
$badge_x     = 120;
$badge_y     = 112;
$badge_w     = 340;
$badge_h     = 28;
// fondo del badge
$badge_bg = imagecolorallocate($img, 25, 20, 55);
imagefilledroundedrectangle_manual($img, $badge_x - 8, $badge_y, $badge_x + $badge_w, $badge_y + $badge_h, $badge_bg);
imagestring($img, 3, $badge_x + 20, $badge_y + 7, 'Disponible para incorporacion inmediata', $violet);

/* ── Nombre (líneas grandes) ──────────────────────────────────── */
$font_paths = [
    'C:/Windows/Fonts/arialbd.ttf',
    'C:/Windows/Fonts/Arial Bold.ttf',
    '/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf',
    '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
    '/usr/share/fonts/dejavu/DejaVuSans-Bold.ttf',
];

$font = null;
foreach ($font_paths as $path) {
    if (file_exists($path)) { $font = $path; break; }
}

if ($font) {
    /* Con TTF: texto de alta calidad */
    imagettftext($img, 72, 0, 100, 265, $white,     $font, 'Roberto Carlos');
    imagettftext($img, 72, 0, 100, 360, $violet,    $font, 'Vazquez Antelo');
    imagettftext($img, 22, 0, 100, 415, $white_dim,  $font, 'Desarrollador Full Stack  ·  Analisis de Sistemas');
} else {
    /* Fallback: fuente interna (más pixelada pero funcional) */
    imagestring($img, 5, 100, 210, 'Roberto Carlos  Vazquez Antelo', $white);
    imagestring($img, 4, 100, 250, 'Desarrollador Full Stack | Analisis de Sistemas', $white_dim);
}

/* ── Tech tags ────────────────────────────────────────────────── */
$techs = ['PHP', 'JavaScript', 'MySQL', 'Tailwind CSS', 'REST APIs', 'Git'];
$tx = 100;
$ty = 460;
foreach ($techs as $tech) {
    $tw = strlen($tech) * 9 + 24;
    // fondo del tag
    imagefilledrectangle($img, $tx, $ty, $tx + $tw, $ty + 28, $violet_dim);
    // borde del tag
    imagerectangle($img, $tx, $ty, $tx + $tw, $ty + 28, $violet_mid);

    if ($font) {
        imagettftext($img, 11, 0, $tx + 12, $ty + 20, $violet, $font, $tech);
    } else {
        imagestring($img, 2, $tx + 8, $ty + 8, $tech, $violet);
    }
    $tx += $tw + 10;
}

/* ── Monograma RV (esquina inferior derecha) ──────────────────── */
$mx = $W - 120;
$my = $H - 110;
imagefilledrectangle($img, $mx, $my, $mx + 80, $my + 80, $graphite);
imagerectangle($img, $mx, $my, $mx + 80, $my + 80, $violet_mid);
if ($font) {
    imagettftext($img, 26, 0, $mx + 14, $my + 52, $white, $font, 'RV');
} else {
    imagestring($img, 5, $mx + 20, $my + 28, 'RV', $white);
}

/* ── URL del sitio ────────────────────────────────────────────── */
if ($font) {
    imagettftext($img, 16, 0, 100, $H - 38, $muted, $font, 'rcvazquezz.com');
} else {
    imagestring($img, 3, 100, $H - 50, 'rcvazquezz.com', $muted);
}

/* ── Línea inferior decorativa ────────────────────────────────── */
imagefilledrectangle($img, 0, $H - 5, $W, $H, $violet);

/* ── Guardar ──────────────────────────────────────────────────── */
imagepng($img, $out, 8);
imagedestroy($img);

echo "og-image generada en: $out\n";

/* ── Helper: rectángulo redondeado ───────────────────────────── */
function imagefilledroundedrectangle_manual($img, $x1, $y1, $x2, $y2, $color, $r = 6) {
    imagefilledrectangle($img, $x1 + $r, $y1, $x2 - $r, $y2, $color);
    imagefilledrectangle($img, $x1, $y1 + $r, $x2, $y2 - $r, $color);
    imagefilledellipse($img, $x1 + $r, $y1 + $r, $r * 2, $r * 2, $color);
    imagefilledellipse($img, $x2 - $r, $y1 + $r, $r * 2, $r * 2, $color);
    imagefilledellipse($img, $x1 + $r, $y2 - $r, $r * 2, $r * 2, $color);
    imagefilledellipse($img, $x2 - $r, $y2 - $r, $r * 2, $r * 2, $color);
}
