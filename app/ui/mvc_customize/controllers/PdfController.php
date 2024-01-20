<?php

use mikehaertl\wkhtmlto\Pdf;

class PdfController extends Controller
{

  public function __construct()
  {

  }

  public function htmlToPdf($html, $pdf_name)
  {

    $pdf = new Pdf(array(

      // バイナリの位置とエンコード形式
      'binary'   => '/usr/local/bin/wkhtmltox/bin/wkhtmltopdf',
      'encoding' => 'utf-8',

      // 以下の指定があるとPDFをページ端まで利用できる
//      'margin-top'    => 0,
//      'margin-right'  => 0,
//      'margin-bottom' => 0,
//      'margin-left'   => 0,
//      'no-outline',

    ));

    // ページを追加
    $pdf->addPage($html);

    // ブラウザにPDFを表示
    $pdf->send($pdf_name);
  }

}
