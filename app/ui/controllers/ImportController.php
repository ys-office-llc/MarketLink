<?php
class ImportController extends BasicController
{
  const _INDEX = 'import';
  const _LIST  = 'list';
  const _GET   = 'get';
  const _POST  = 'post';

  protected $_authentication = array(
    self::_LIST,
    self::_GET,
    self::_POST,
  );

  public function listAction()
  {
  }

  public function getAction($params)
  {
    $data = null;
    $user = $this->_session->get('user');

    return $this->render(
      array(
        self::_INDEX => $data,
        'view_path'  => $this->_view_path,
        '_token'     => $this->getToken($this->_view_path . '/' . self::_POST)
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

    $results = array();
    $data    = array();
    $this->_user = $this->_session->get('user');

    $authority_level = (int)$this->getAccount()[
      'account_authority_level_id'
    ];

    if (!$this->_request->isPost()) {

      $this->httpNotFound();
    }

    $token = $this->_request->getPost('_token');
    if (!$this->checkToken(
           $this->_view_path.'/'.self::_POST,
           $token
         )
    ) {

      return $this->redirect('/');
    }

    $type = $this->_request->getSubmitType();

    if (self::TYPE_CREATE === $type) {

      switch ($this->_request->getPosts()['_type']) {
        case self::TYPE_IMPORT_RESEARCH_YAHOO_AUCTIONS_SEARCH:

          $results = $this->reflectionYahooAuctionsSearch(
                       $this->_request->getPosts()
                     );
          break;
        case self::TYPE_IMPORT_RESEARCH_ANALYSIS:

          $results = $this->reflectionAnalysis(
                       $this->_request->getPosts()
                     );
          break;
        case self::TYPE_IMPORT_RESEARCH_NEW_ARRIVAL:

          $results = $this->reflectionNewArrival(
                       $this->_request->getPosts()
                     );
          break;
      }

      return $this->render(
        array(
        'errors'    => $results['errors'],
        'successes' => $results['successes'],
        'view_path' => $this->_view_path,
        '_token'    => $this->getToken(
                         $this->_view_path.'/'.self::_POST
                       ),
      ), self::_GET);
    } else {

      switch ($type) {
        case self::TYPE_IMPORT_RESEARCH_YAHOO_AUCTIONS_SEARCH:

          if ($authority_level > 1) {

            $results = $this->csvToArray(23);
          } else {

            $results = $this->csvToArray(20);
          }
          break;
        case self::TYPE_IMPORT_RESEARCH_ANALYSIS:

          $results = $this->csvToArray(15);
          break;
        case self::TYPE_IMPORT_RESEARCH_NEW_ARRIVAL:

          if ($authority_level > 1) {

            $results = $this->csvToArray(17);
          } else {

            $results = $this->csvToArray(16);
          }
          break;
      }

      return $this->render(
        array(
        'errors'      => $results['errors'],
        self::_INDEX  => $results['successes'],
        '_type'       => $type,
        'view_path'   => $this->_view_path,
        '_token'      => $this->getToken(
                           $this->_view_path.'/'.self::_POST
                         ),
      ), self::_GET);
    }
  }

  private function reflectionYahooAuctionsSearch($records)
  {

    $param   = array();
    $id      = null;
    $user_id = $this->_session->get('user')['id'];
    $limit   = $this->_session->get('resources')[
                 'research_new_arrival'
               ]['limit'];

    $results = array(
      'successes' => array(),
      'errors'    => array(),
    );

    $authority_level = (int)$this->getAccount()[
      'account_authority_level_id'
    ];

    try {

      foreach ($records['column'] as $record) {

        if ($authority_level > 1) {

          $param = array(
            'user_id' => $user_id,
            'name' => $record[0],
            'action' => $record[1],
            'chatwork_to' => $record[2],
            'stockless' => $record[3],
            'except_img_urls' => $record[4],
            'my_pattern_id' => $record[5],
            'query_include_everything' => $record[6],
            'query_include_either' => $record[7],
            'query_not_include' => $record[8],
            'category_id' => $record[9],
            'search_target' => $record[10],
            'aucminbids' => $record[11],
            'aucmaxbids' => $record[12],
            'aucminprice' => $record[13],
            'aucmaxprice' => $record[14],
            'aucmin_bidorbuy_price' => $record[15],
            'aucmax_bidorbuy_price' => $record[16],
            'item_status' => $record[17],
            'listing_category' => $record[18],
            'is_automatic_extension' => $record[19],
            'reserved' => $record[20],
            'seller' => $record[21],
            'seller_except' => $record[22],
            'created_at' => $this->_datetime->format('Y-m-d H:i:s'),
            'modified_at' => $this->_datetime->format('Y-m-d H:i:s'),
          );
        } else {

          $param = array(
            'user_id' => $user_id,
            'name' => $record[0],
            'action' => $record[1],
            'chatwork_to' => $record[2],
            'query_include_everything' => $record[3],
            'query_include_either' => $record[4],
            'query_not_include' => $record[5],
            'category_id' => $record[6],
            'search_target' => $record[7],
            'aucminbids' => $record[8],
            'aucmaxbids' => $record[9],
            'aucminprice' => $record[10],
            'aucmaxprice' => $record[11],
            'aucmin_bidorbuy_price' => $record[12],
            'aucmax_bidorbuy_price' => $record[13],
            'item_status' => $record[14],
            'listing_category' => $record[15],
            'is_automatic_extension' => $record[16],
            'reserved' => $record[17],
            'seller' => $record[18],
            'seller_except' => $record[19],
            'created_at' => $this->_datetime->format('Y-m-d H:i:s'),
            'modified_at' => $this->_datetime->format('Y-m-d H:i:s'),
          );
        }

        if ($this->_connect_model
                 ->relay('master', 'ResearchYahooAuctionsSearch')
                 ->counter($user_id) > $limit) {

          $results['errors'][] = "{$param['name']}は上限（{$limit}）を".
                                 "越えたため登録できませんでした";
        } else {

          $id = $this->_connect_model
                     ->relay('master', 'ResearchYahooAuctionsSearch')
                     ->getIdByName(
                         $this->_session->get('user')['id'],
                         $param['name']
                       );

          if ($id > 0) {

            $param['id'] = $id;
            $this->_connect_model
                 ->relay('master', 'ResearchYahooAuctionsSearch')
                 ->update($param);

            $results['successes'][] = "{$param['name']}を更新しました。";
          } else {

            unset($param['created_at']);
            $this->_connect_model
                 ->relay('master', 'ResearchYahooAuctionsSearch')
                 ->create($param);

            $results['successes'][] = "{$param['name']}を新規追加しました。";
          }
        }
      }

      return $results;
    } catch (Exception $e) {

        $results['errors'][] = $e->getMessage();

        return $results;
    }
  }

  private function reflectionNewArrival($records)
  {

    $param   = array();
    $id      = null;
    $user_id = $this->_session->get('user')['id'];
    $limit   = $this->_session->get('resources')[
                 'research_new_arrival'
               ]['limit'];

    $results = array(
      'successes' => array(),
      'errors'    => array(),
    );

    $authority_level = (int)$this->getAccount()[
      'account_authority_level_id'
    ];

    try {

      foreach ($records['column'] as $record) {

        if ($authority_level > 1) {

          $param = array(
            'user_id' => $user_id,
            'name' => $record[0],
            'action' => $record[1],
            'chatwork_to' => $record[2],
            'stock' => $record[3],
            'title_include_everything' => $record[4],
            'title_include_either' => $record[5],
            'title_not_include' => $record[6],
            'rank_kitamura' => $record[7],
            'rank_fujiya_camera' => $record[8],
            'rank_camera_no_naniwa' => $record[9],
            'rank_map_camera' => $record[10],
            'rank_champ_camera' => $record[11],
            'rank_hardoff' => $record[12],
            'min_price' => $record[13],
            'max_price' => $record[14],
            'remarks_include_either' => $record[15],
            'remarks_not_include' => $record[16],
            'created_at' => $this->_datetime->format('Y-m-d H:i:s'),
            'modified_at' => $this->_datetime->format('Y-m-d H:i:s'),
          );
        } else {

          $param = array(
            'user_id' => $user_id,
            'name' => $record[0],
            'action' => $record[1],
            'chatwork_to' => $record[2],
            'stock' => $record[3],
            'title_include_everything' => $record[4],
            'title_include_either' => $record[5],
            'title_not_include' => $record[6],
            'rank_kitamura' => $record[7],
            'rank_camera_no_naniwa' => $record[8],
            'rank_map_camera' => $record[9],
            'rank_champ_camera' => $record[10],
            'rank_hardoff' => $record[11],
            'min_price' => $record[12],
            'max_price' => $record[13],
            'remarks_include_either' => $record[14],
            'remarks_not_include' => $record[15],
            'created_at' => $this->_datetime->format('Y-m-d H:i:s'),
            'modified_at' => $this->_datetime->format('Y-m-d H:i:s'),
          );
        }

        if ($this->_connect_model
                 ->relay('master', 'ResearchNewArrival')
                 ->counter($user_id) > $limit) {

          $results['errors'][] = "{$param['name']}は上限（{$limit}）を".
                                 "越えたため登録できませんでした";
        } else {

          $id = $this->_connect_model
                     ->relay('master', 'ResearchNewArrival')
                     ->getIdByName(
                         $this->_session->get('user')['id'],
                         $param['name']
                       );

          if ($id > 0) {

            $param['id'] = $id;
            $this->_connect_model
                 ->relay('master', 'ResearchNewArrival')
                 ->update($param);

            $results['successes'][] = "{$param['name']}を更新しました。";
          } else {

            unset($param['created_at']);
            $this->_connect_model
                 ->relay('master', 'ResearchNewArrival')
                 ->create($param);

            $results['successes'][] = "{$param['name']}を新規追加しました。";
          }
        }
      }

      return $results;
    } catch (Exception $e) {

        $results['errors'][] = $e->getMessage();

        return $results;
    }
  }

  private function reflectionAnalysis($records)
  {

    $param = array();
    $id    = null;

    $results = array(
      'successes' => array(),
      'errors'    => array(),
    );

    try {

      foreach ($records['column'] as $record) {

        $param = array(
          'user_id' => $this->_session->get('user')['id'],
          'name' => $record[0],
          'action' => $record[1],
          'yahoo_auctions_query_include_everything' => $record[2],
          'yahoo_auctions_query_include_either' => $record[3],
          'yahoo_auctions_query_not_include' => $record[4],
          'yahoo_auctions_category_id' => $record[5],
          'yahoo_auctions_min_price' => $record[6],
          'yahoo_auctions_max_price' => $record[7],
          'ebay_us_query_include_everything' => $record[8],
          'ebay_us_query_include_either' => $record[9],
          'ebay_us_query_not_include' => $record[10],
          'ebay_us_category_id' => $record[11],
          'ebay_us_min_price' => $record[12],
          'ebay_us_max_price' => $record[13],
          'amazon_jp_asin' => $record[14],
          'created_at' => $this->_datetime->format('Y-m-d H:i:s'),
          'modified_at' => $this->_datetime->format('Y-m-d H:i:s'),
        );

        if ($this->_connect_model
                 ->relay('master', 'ResearchNewArrival')
                 ->counter($user_id) > $limit) {

          $results['errors'][] = "{$param['name']}は上限（{$limit}）を".
                                 "越えたため登録できませんでした";
        } else {

          $id = $this->_connect_model
                     ->get('ResearchAnalysis')
                     ->getIdByName(
                         $this->_session->get('user')['id'],
                         $param['name']
                       );
 
          if ($id > 0) {
 
            $param['id'] = $id;
            $this->_connect_model
                 ->relay('master', 'ResearchAnalysis')
                 ->update($param);
 
            $results['successes'][] = "{$param['name']}を更新しました。";
          } else {
 
            unset($param['created_at']);
            $this->_connect_model
                 ->relay('master', 'ResearchAnalysis')
                 ->create($param);
 
            $results['successes'][] = "{$param['name']}を新規追加しました。";
          }
        }
      }

      return $results;
    } catch (Exception $e) {

        $results['errors'][] = $e->getMessage();

        return $results;
    }
  }

}
