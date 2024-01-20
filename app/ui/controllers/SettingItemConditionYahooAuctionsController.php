<?php
class SettingItemConditionYahooAuctionsController extends BasicController
{
  const _INDEX = 'condition';
  const _LIST  = 'list';
  const _GET   = 'get';
  const _POST  = 'post';

  protected $_authentication = array(
    self::_LIST,
    self::_GET,
    self::_POST,
  );

  private function barrier()
  {

    if (!preg_match(
          "/^enable$/",
          $this->_session->get('user')['merchandise_management']
        )) {

      $this->httpForbidden();
    }
  }

  public function listAction()
  {

    $this->barrier();
    return $this->render(
      array(
        self::_INDEX . 's' => $this->_connect_model
                                   ->get($this->_controller)
                                   ->gets($this->_session->get('user')['id']),
        'view_path' => $this->_view_path,
        '_token' => $this->getToken($this->_view_path . '/' . self::_POST),
      )
    );
  }

  public function getAction($params)
  {
    $data = null;

    $this->barrier();
    if (isset($params['id'])) {
      $data = $this->_connect_model
                   ->get($this->_controller)
                   ->get(
                     $params['id'],
                     $this->_session
                          ->get('user')['id']
                   );

      if (!$data) {
        $this->httpNotFound();
      }

    }

    $this->set();
    return $this->render(
      array(
        self::_INDEX   => $this->unserializer($this->_controller, $data),
        'table_values' => $this->_connect_model
                               ->get($this->_controller)
                               ->getTableValues(),
        'view_path'    => $this->_view_path,
        '_token'       => $this->getToken($this->_view_path . '/' . self::_POST)
      )
    );
  }

  private function verify($data)
  {
    $errors = array();

    $shipping_methods = array_merge(
                          (array)unserialize($data['yahuneko']),
                          (array)unserialize($data['hacoboon']),
                          (array)unserialize($data['hacoboonmini'])
                        );

    if (!strlen($data['name'])) {

      $errors[] = '条件名を入力してください';
    }

    if ($data['select_category_id'] < 1) {

      $errors[] = 'カテゴリー選択を選択してください';
    }

    if ($data['exhibits_style_id'] < 1) {

      $errors[] = '販売形式を選択してください';
    }

    if ($data['sales_period_id'] < 1) {

      $errors[] = '開催期間を選択してください';
    }

    if ($data['shipping_origin_id'] < 1) {

      $errors[] = '都道府県を選択してください';
    }

    if ($data['item_status_id'] < 1) {

      $errors[] = '商品の状態を選択してください';
    }

    if ($data['accept_returns_id'] < 1) {

      $errors[] = '返品可否を選択してください';
    }

    if ($data['endtime_id'] < 1) {

      $errors[] = '終了時間を選択してください';
    }

    if ($data['shipname_standard_id'] < 1 and
        count($shipping_methods) === 0) {

      $errors[] = '配送方法を選択してください';
    }
/*
    } else if (count($shipping_methods) > 1 or
               count($shipping_methods) > 0 and
               $data['shipname_standard_id'] > 0) {

      $errors[] = sprintf(
                    "配送方法が複数選択されています（%s）",
                    implode('／', $shipping_methods)
                  );
    }
*/

    if (in_array('宅急便', $shipping_methods)) {

      if ($data['yahuneko_total_lwh_id'] < 1) {

        $errors[] = '[配送方法] > [ヤフネコ！パック] > [宅急便（60～160サイズ）] > [縦、横、高さの合計（こん包後）：] を選択してください';
      }

      if ($data['yahuneko_weight_id'] < 1) {

        $errors[] = '[配送方法] > [ヤフネコ！パック] > [宅急便（60～160サイズ）] > [重さ（こん包後）：] を選択してください';
      }
    }

    if (in_array('はこBOON', $shipping_methods)) {

      if ($data['hacoboon_total_lwh_id'] < 1) {

        $errors[] = '[配送方法] > [はこBOON] > [縦、横、高さの合計（こん包後）：] を選択してください';
      }

      if ($data['hacoboon_weight_id'] < 1) {

        $errors[] = '[配送方法] > [はこBOON] > [重さ（こん包後）：] を選択してください';
      }
    }

    if (in_array('はこBOON mini', $shipping_methods)) {

      if ($data['hacoboonmini_shipment_source_store_id'] < 1) {

        $errors[] = '[配送方法] >[はこBOON mini] >  [発送元店舗]を指定してください';
      }
    }

    if ($data['shipname_standard_id'] > 0) {

      if (!strlen($data['delivery_cost'])) {

        $errors[] = '配送料金を入力してください';
      }

      if (!strlen($data['delivery_additional_cost'])) {

        $errors[] = '配送追加料金を入力してください';
      }
    }

    return $errors;
  }

  public function postAction()
  {
    $data    = array();
    $this->_user = $this->_session->get('user');

    if (!$this->_request->isPost()) {
      $this->httpNotFound();
    }

    $token = $this->_request->getPost('_token');
    if (!$this->checkToken($this->_view_path . '/' . self::_POST, $token)) {
      return $this->redirect('/');
    }

    $type = $this->_request->getSubmitType();
    $data = $this->_connect_model
                 ->get($this->_controller)
                 ->desc();
    $data = $this->fillValue($data);
    $data = $this->serializer($this->_controller, $data);

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

}
