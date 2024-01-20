<?php
class UseResearchAnalysisArchiveController extends BasicController
{
  const _INDEX = 'use_research_analysis_archive';
  const _LIST  = 'list';
  const _GET   = 'get';
  const _POST  = 'post';

  protected $_authentication = array(
    self::_LIST,
    self::_GET,
    self::_POST,
  );

  private function getExchangeUSDJPY()
  {

    $html = file_get_contents('http://info.finance.yahoo.co.jp/fx/');
    $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
    $dom = new DOMDocument();
    @$dom->loadHTML($html);

    return (int)$dom->getElementById('USDJPY_top_bid')->nodeValue;
  }

  public function listAction()
  {

    $user  = $this->_session->get('user');

    if ($user['market_screening'] !== 'enable') {

      $this->httpForbidden();
    }

    return $this->render(
      array(
        self::_INDEX . 's' => $this->_connect_model
                                   ->get($this->_controller)
                                   ->getsByDate(
                                       $this->_session->get('user')['id'],
                                       $this->_datetime->format('Y-m-d')
                                     ),
        'ym'        => $this->_datetime->format('Y/m'),
        'view_path' => $this->_view_path,
        '_token'    => $this->getToken(
                         $this->_view_path.'/'.self::_POST
                       ),
      )
    );
  }

  private function createGraphData1M($records)
  {

    $data = array();
    $exchange_rate = $this->getExchangeUSDJPY();

    foreach ($records as $key => $value) {

      $ymd = str_replace(' 00:00:00', '', $value['created_at']);

      list($y, $m, $d) = explode('-', $ymd);

      $data[$y][$m]['date'][] = $m.'/'.$d;
      $data[$y][$m]['index']['yahoo']['auctions'][] = $value['yahoo_auctions_index']; 
      $data[$y][$m]['index']['ebay'][] = $value['ebay_us_index'];
      $data[$y][$m]['numof']['yahoo']['auctions']['selling'][] = $value['yahoo_auctions_numof_selling']; 
      $data[$y][$m]['numof']['yahoo']['auctions']['sold']['m1'][] = $value['yahoo_auctions_numof_sold_m1']; 
      $data[$y][$m]['numof']['ebay']['active'][] = $value['ebay_us_numof_active']; 
      $data[$y][$m]['numof']['ebay']['sold']['m1'][] = $value['ebay_us_numof_sold_m1']; 
      $data[$y][$m]['price']['ebay']['min'][] = $value['ebay_us_min_price'] * $exchange_rate;
      $data[$y][$m]['price']['ebay']['avg'][] = $value['ebay_us_avg_price'] * $exchange_rate;
      $data[$y][$m]['price']['ebay']['max'][] = $value['ebay_us_max_price'] * $exchange_rate;
      $data[$y][$m]['price']['yahoo']['auctions']['min'][] = $value['yahoo_auctions_min_price'];
      $data[$y][$m]['price']['yahoo']['auctions']['avg'][] = $value['yahoo_auctions_avg_price'];
      $data[$y][$m]['price']['yahoo']['auctions']['max'][] = $value['yahoo_auctions_max_price'];
      $data[$y][$m]['price']['amazon']['jp']['min'][] = $value['amazon_jp_lowest_offer_listing_price'];
    }

    return $data;
  }

  private function createGraphData1Y($records)
  {

    $data = array();
    $exchange_rate = $this->getExchangeUSDJPY();

    foreach ($records as $key => $value) {

      $ymd = str_replace(' 00:00:00', '', $value['created_at']);

      list($y, $m, $d) = explode('-', $ymd);

      $data[$y]['date'][] = $m.'/'.$d;
      $data[$y]['index']['yahoo']['auctions'][] = $value['yahoo_auctions_index'];
      $data[$y]['index']['ebay'][] = $value['ebay_us_index'];
      $data[$y]['numof']['yahoo']['auctions']['selling'][] = $value['yahoo_auctions_numof_selling'];
      $data[$y]['numof']['yahoo']['auctions']['sold']['m1'][] = $value['yahoo_auctions_numof_sold_m1'];
      $data[$y]['numof']['ebay']['active'][] = $value['ebay_us_numof_active'];
      $data[$y]['numof']['ebay']['sold']['m1'][] = $value['ebay_us_numof_sold_m1'];
      $data[$y]['price']['ebay']['min'][] = $value['ebay_us_min_price'] * $exchange_rate;
      $data[$y]['price']['ebay']['avg'][] = $value['ebay_us_avg_price'] * $exchange_rate;
      $data[$y]['price']['ebay']['max'][] = $value['ebay_us_max_price'] * $exchange_rate;
      $data[$y]['price']['yahoo']['auctions']['min'][] = $value['yahoo_auctions_min_price'];
      $data[$y]['price']['yahoo']['auctions']['avg'][] = $value['yahoo_auctions_avg_price'];
      $data[$y]['price']['yahoo']['auctions']['max'][] = $value['yahoo_auctions_max_price'];
      $data[$y]['price']['amazon']['jp']['min'][] = $value['amazon_jp_lowest_offer_listing_price'];
    }

    return $data;
  }

  private function getDateTimeXMonthsAgo($period)
  {

    $datetime = clone $this->_datetime;

    return $datetime->modify(sprintf("-%s months", $period));
  }

  public function getAction($params)
  {

    $period        = null;
    $id            = null;
    $year_current  = null;
    $month_current = null;
    $year_past     = null;
    $month_past    = null;
    $records       = array();
    $graph_data    = array();

    $user  = $this->_session->get('user');

    if ($user['market_screening'] !== 'enable') {

      $this->httpForbidden();
    }

    $id            = $params['id'];
    $year_current  = $params['year_current'];

    if (isset($params['month_current'])) {

      $month_current = $params['month_current'];
    }

    if (isset($params['year_past'])) {

      $year_past = $params['year_past'];
    }

    if (isset($params['month_past'])) {

      $month_past = $params['month_past'];
    }

    if (isset($year_current) and
        isset($month_current) and
        isset($year_past) and
        isset($month_past)) {

      $records = $this->_connect_model
                      ->get($this->_controller)
                      ->getsByResearchAnalysisIdYMtoYM(
                          $this->_session->get('user')['id'],
                          $params['id'],
                          $year_current,
                          $month_current,
                          $year_past,
                          $month_past
                        );
      $graph_data = $this->jsonSafeEncode(
                             $this->createGraphData1Y(
                               $records
                             )[$year_current]
                           );
      $period = '3m';
    } else if (isset($year_current) and
               isset($month_current)) {


      $records = $this->_connect_model
                      ->get($this->_controller)
                      ->getsByResearchAnalysisId1Y1M(
                          $this->_session->get('user')['id'],
                          $params['id'],
                          $year_current,
                          $month_current
                        );

      $graph_data = $this->jsonSafeEncode(
                             $this->createGraphData1M(
                               $records
                             )[$year_current][$month_current]
                           );

      $period     = '1m';
      $year_past  = $this->getDateTimeXMonthsAgo(3)->format('Y');
      $month_past = $this->getDateTimeXMonthsAgo(3)->format('m');
    } else if (isset($year_current)) {

      $records = $this->_connect_model
                      ->get($this->_controller)
                      ->getsByResearchAnalysisId1Y(
                          $this->_session->get('user')['id'],
                          $params['id'],
                          $year_current
                        );

      $graph_data = $this->jsonSafeEncode(
                             $this->createGraphData1Y(
                               $records
                             )[$year_current]
                           );

      $period        = '1y';
      $month_current = $this->_datetime->format('m');
      $year_past     = $this->getDateTimeXMonthsAgo(3)->format('Y');
      $month_past    = $this->getDateTimeXMonthsAgo(3)->format('m');
    }

    return $this->render(

      array(
        self::_INDEX . 's' => $records,
        'graph_data'       => $graph_data,
        'period'           => $period,
        'id'               => $id,
        'y'                => $year_current,
        'ym'               => $year_current.'/'.$month_current,
        'y3m'              => $year_current.'/'.
                              $month_current.'/'.
                              $year_past.'/'.
                              $month_past,
        'view_path'        => $this->_view_path,
        '_token'           => $this->getToken(
                                $this->_view_path.'/'.self::_POST
                              ),
      )
    );
  }

  private function verify($data)
  {
    $errors = array();

    return $errors;
  }

  public function postAction()
  {

    $data = array();
    $this->_user = $this->_session->get('user');

    if (!$this->_request->isPost()) {

      $this->httpNotFound();
    }

    $token = $this->_request->getPost('_token');
    if (!$this->checkToken($this->_view_path . '/' . self::_POST, $token)) {
      return $this->redirect('/');
    }

    $type = $this->_request->getSubmitType();

    if (preg_match(
          "/(display_pdf)/",
          $type,
          $matches
        )
    ) {

      return $this->batchProcessing($type);
    }


    $data = $this->_connect_model
                 ->get($this->_controller)
                 ->desc();
    $data = $this->fillValue($data);

    $this->set();
    $successes = array();
    $render = array(
      'errors'       => $this->verify($data),
      'successes'    => $successes,
      self::_INDEX   => $data,
      'table_values' => $this->_connect_model
                             ->get($this->_controller)
                             ->getTableValues(),
      'view_path'    => $this->_view_path,
      '_token'       => $this->getToken($this->_view_path . '/' . self::_POST),
    );

    return $this->commit(
      $type,
      $render,
      self::_INDEX,
      $this->_controller,
      self::_GET
    );
  }

  private function annotationNotFound($price)
  {

    if ((int)$price > 0) {

      return number_format($price);
    } else {

      return '<font color="#ff0000">※販売履歴がない商品です</font>';
    }
  }

  public function batchProcessing($type)
  {

    $pdf = new PdfController();

    $results     = array();
    $html        = array();
    $display_pdf = null;
    $i           = 'implode';
    $a           = function($asin, $price)
    {

      if ((int)$price > 0) {

        return sprintf("
          <a href=\"https://www.amazon.co.jp/gp/offer-listing/%s/ref=dp_olp_used?ie=UTF8&condition=used\"
             target=\"_blank\">
             %s
          </a>円",
          $asin,
          number_format($price)
        );
      } else {

        return '<font color="#ff0000">販売履歴なし</font>';
      }
    };

    $this->_user = $this->_session->get('user');
    $display_pdf = $this->_request->getPost('display_pdf');
    $created_at  = $this->_datetime->format('Y/m/d H:i:s');

    if ($display_pdf) {

      foreach ($this->_request->getPosts()['id'] as $id => $value) {

        $data = $this->_connect_model
                     ->get($this->_controller)
                     ->get(
                         $id,
                         $this->_user['id']
                       );

        $html[] = sprintf("
          <tr>
            <td align=\"center\">
              <img src=\"%s\"
                   width=\"122\"
                   height=\"75\" />
            </td>
            <td align=\"center\">%s</td>
            <td align=\"center\">%s円</td>
            <td align=\"center\">
              <a href=\"%s\"
                 target=\"_blank\">
                %s
              </a>円
            </td>
            <td align=\"center\">
              <a href=\"%s\"
                 target=\"_blank\">
                %s
              </a>ドル
            </td>
            <td align=\"center\">
              %s
            </td>
          </tr>",
          $data['ebay_us_img_uri_sold_highest'],
          $data['name'],
          number_format($data['yahoo_auctions_min_price']),
          $data['yahoo_auctions_uri_sold_highest'],
          number_format($data['yahoo_auctions_max_price']),
          $data['ebay_us_uri_sold_highest'],
          $data['ebay_us_max_price'],
          $a(
            $data['amazon_jp_asin'],
            $data['amazon_jp_lowest_offer_listing_price']
          )
        );
      }

$html = <<<EOF

  <!DOCTYPE html>
  <html>
    <head>

    <style type="text/css">
    <!--

    -->
    </style>

    </head>
    <body>
      <h1>
        人工知能型カメラ転売ツール マーケットリンクカメラ版 自動生成リスト
      </h1>

      <h2>
        作成日時：{$created_at}
      </h2>

      <center>

      <table border="1"
             bordercolor="#dddddd" 
             cellspacing="0"
             bgcolor="#f5f5f5">
        <thead>

          <tr bgcolor="#d9edf7">
            <th rowspan="2"></th>
            <th rowspan="2"><font color="#337ab7">商品名</font></th>
            <th rowspan="2"><font color="#337ab7">ネットショップ仕入価格</font></th>
            <th colspan="3"><font color="#337ab7">販売価格</font></th>
          </tr>

          <tr bgcolor="#d9edf7">
            <th rowspan="1"><font color="#337ab7">ヤフオク</font></th>
            <th rowspan="1"><font color="#337ab7">eBay</font></th>
            <th rowspan="1"><font color="#337ab7">Amazon</font></th>
          </tr>

        </thead>

        <tbody>

          {$i("\n", $html)}

        </tbody>
      </table>

      <footer>
        <p>
          <small>
            &copy; Copyright 2017 YS office LLC. Powered by Market Link for CAMERA &trade;
          </small>
        </p>
      </footer>
      </center>
    </body>
  </html>

EOF;

      $pdf->htmlToPdf(
        $html,
        'purchase_list_'.
        $this->_datetime
             ->format('Y-m-d').
        '.pdf'
      );
      exit;
    }
  }

}
