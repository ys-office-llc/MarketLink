<?php
class SupportAccountController extends BasicController
{
  const _INDEX = 'account';
  const _LIST  = 'list';
  const _GET   = 'get';
  const _POST  = 'post';

  protected $_authentication = array(
    self::_LIST,
    self::_GET,
    self::_POST,
  );

  public function getAction($params)
  {

    $data = null;
    $user = $this->_session->get('user');

    if ($params['id'] !== $user['id']) {

      $this->httpNotFound();
    }

    $data = $this->_connect_model
                 ->relay('m', $this->_controller)
                 ->get($user['id']);

    if (!$data) {

      $this->httpNotFound();
    }

    return $this->render(
      array(
        self::_INDEX   => $data,
        'table_values' => $this->_connect_model
                               ->relay('m', $this->_controller)
                               ->getTableValues(),
        'in_progress'  => $this->inProgress($data),
        'is_migration' => $this->isMigration(),
        'view_path'    => $this->_view_path,
        '_token'       => $this->getToken(
                                   $this->_view_path.'/'.self::_POST
                                 )
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

    $result = array();
    $data   = array();
    $this->_user = $this->_session->get('user');

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
    $data = $this->_connect_model
                 ->relay('m', $this->_controller)
                 ->desc();
    $data = $this->fillValue($data);

    if (preg_match("/migration_plans/", $type)) {

      $result = $this->requestMigrationPlans($data);
    } else if (preg_match("/migration_packages/", $type)) {

      $result = $this->requestMigrationPackages($data);
    }

    return $this->render(
      array(
        'errors'       => $result['errors'],
        'successes'    => $result['successes'],
        self::_INDEX   => $result['data'],
        'is_migration' => $this->isMigration(),
        'in_progress'  => $this->inProgress($data),
        'table_values' => $this->_connect_model
                               ->relay('m', $this->_controller)
                               ->getTableValues(),
        'view_path'    => $this->_view_path,
        '_token'       => $this->getToken(
                            $this->_view_path.'/'.self::_POST
                          ),
      ), self::_GET
    );
  }

  private function requestMigrationPlans($data)
  {

    $successes = array();
    $errors    = array();
    $values    = $this->_connect_model
                      ->relay('m', $this->_controller)
                      ->getTableValues();

    if ((int)$data[
               'account_contract_id'
             ] === (int)$data['migration_contract_id']) {

      $errors[] = "プランが変更されていません";

    }

    if (count($errors) === 0) {

      try {

        $this->_connect_model
             ->relay('m', $this->_controller)
             ->update(
                 array(
                   'id' => $this->_user['id'],
                   'request_migration' => 1,
                   'migration_contract_id' => $data['account_contract_id'],
                 )
               );

        $successes[] = sprintf(
          "%sプランへ変更を要求しました",
          $values['account_contract'][$data['account_contract_id']]['name']
        );
      } catch (Exception $e) {

        $errors[] = $e->getMessage();
      }
    }

    return array(
      'data'      => $data,
      'errors'    => $errors,
      'successes' => $successes,
    );
  }

  private function requestMigrationPackages($data)
  {

    $successes = array();
    $errors    = array();

    try {

      $this->_connect_model
           ->relay('m', $this->_controller)
           ->update(
               array(
                 'id' => $this->_user['id'],
                 'market_screening' => $data['market_screening'],
                 'merchandise_management' => $data['merchandise_management'],
                 'migration_packages_datetime' => $this->_datetime
                                                       ->format(
                                                           'Y-m-d H:i:s'
                                                         ),
               )
             );

      $successes[] = sprintf(
        "契約パッケージを変更しました"
      );
    } catch (Exception $e) {

      $errors[] = $e->getMessage();
    }

    return array(
      'data'      => $data,
      'errors'    => $errors,
      'successes' => $successes,
    );
  }

  private function isMigration()
  {

    return array(
      'plans' => $this->_connect_model
                      ->relay('m', $this->_controller)
                      ->updatedWithinThePeriod(
                          $this->_session->get('user')['id'],
                          'migration_plans_datetime', 
                          '1 MONTH'
                        ),
      'packages' => $this->_connect_model
                         ->relay('m', $this->_controller)
                         ->updatedWithinThePeriod(
                             $this->_session->get('user')['id'],
                             'migration_packages_datetime', 
                             '1 MONTH'
                           ),
    );
  }
}
