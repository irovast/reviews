<?php
session_start();
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }

ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'config.php';
require_once 'fpdf/fpdf.php';
require_once 'fpdf/autoload.php';   // FPDI 2.x autoloader
require_once 'phpqrcode/qrlib.php';

use setasign\Fpdi\Fpdi;

/* ── Extend FPDI with simple rotation support (no traits) ───────── */
class PDF_Rotate extends Fpdi {
  protected $angle = 0;
  function Rotate($angle, $x = -1, $y = -1) {
    if ($x == -1) $x = $this->x;
    if ($y == -1) $y = $this->y;
    if ($this->angle != 0) $this->_out('Q');
    $this->angle = $angle;
    if ($angle != 0) {
      $angle *= M_PI/180;
      $c = cos($angle); $s = sin($angle);
      $cx = $x * $this->k; $cy = ($this->h - $y) * $this->k;
      $this->_out(sprintf(
        'q %.5F %.5F %.5F %.5F %.5F %.5F cm 1 0 0 1 %.5F %.5F cm',
        $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy
      ));
    }
  }
  function _endpage() {
    if ($this->angle != 0) {
      $this->angle = 0;
      $this->_out('Q');
    }
    parent::_endpage();
  }
}

/* ── Fetch brand row ─────────────────────────────────────────────── */
$id = intval($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM businesses WHERE id=?");
$stmt->execute([$id]);
$brand = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$brand) exit('Brand not found');

$slug = $brand['slug'];
$link = "https://review.card-v.com/index.html?b=" . urlencode($slug);

/* ── Generate local QR ───────────────────────────────────────────── */
$tmpQr = __DIR__ . "/qr_$id.png";
QRcode::png($link, $tmpQr, QR_ECLEVEL_H, 6);

/* ── Build PDF ───────────────────────────────────────────────────── */
$template = __DIR__ . '/template.pdf';
$pdf = new PDF_Rotate('L', 'pt', 'A4');   // 842×595 pt
$pdf->setSourceFile($template);

/* Page 1 */
$pdf->AddPage();
$pdf->useTemplate($pdf->importPage(1), 0, 0, 842, 595);

/* Rotate and place logo */
$pdf->Rotate(-90, 700, 180);                       // pivot at center of logo
$pdf->Image($brand['logo_url'], 700, 180, 260);    // x,y,w (square)
$pdf->Rotate(0);

/* Page 2 */
$pdf->AddPage();
$pdf->useTemplate($pdf->importPage(2), 0, 0, 842, 595);

/* QR centred inside white rounded square */
$pdf->Image($tmpQr, 365, 160, 280, 280);

/* No logo on page 2 (remove if you don’t want)  */
/* $pdf->Image($brand['logo_url'], 110, 185, 120); */

/* ── Output ──────────────────────────────────────────────────────── */
$filename = preg_replace('/[^A-Za-z0-9_-]/','_', $slug) . '_review_flyer.pdf';
$pdf->Output('D', $filename);

@unlink($tmpQr);
