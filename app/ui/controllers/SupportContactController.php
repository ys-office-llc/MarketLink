<?php
class SupportContactController extends BasicController
{
  const _INDEX = 'contact';
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

     if (!strlen($data['title'])) {
      $errors[] = 'タイトルを入力してください';
    }

    if (!strlen($data['inquiry'])) {
      $errors[] = '内容を入力してください';
    }

    return $errors;
  }

  public function postAction()
  {

    $results = array();
    $data    = array();
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
                 ->get($this->_controller)
                 ->desc();
    $data = $this->fillValue($data);

    $results = $this->accept($data);

    return $this->render(
      array(
        'errors'      => $results['errors'],
        'successes'   => $results['successes'],
        self::_INDEX  => $results['data'],
        'view_path'   => $this->_view_path,
        '_token'      => $this->getToken(
                           $this->_view_path.'/'.self::_POST
                         ),
      ),
      self::_GET
    );
  }

  private function accept($data)
  {

    $errors = array();

    $errors = $this->verify($data);

    if (count($errors) === 0) {

      try {

        $this->_connect_model
             ->get($this->_controller)
             ->create($this->processingImages($data));

        $data['title']   = null;
        $data['inquiry'] = null;
      } catch (Exception $e) {

        $errors[] = $e->getMessage();
      }
    }

    return array(
      'data'      => $data,
      'errors'    => $errors,
      'successes' => array('送信しました'),
    );
  }

  private function processingImages($data)
  {

    $account = $this->getAccount();
    $images  = $this->_request->getFiles()['images'];

    if (count($images['name']) > 10) {

      throw new InternalServerErrorException(
        '添付画像は10個までです'
      );
    } elseif ($images['size'] > 0) {

      foreach ($images['error'] as $ix => $error) {

        if ($error === UPLOAD_ERR_OK) {

          $tmp_name = $images['tmp_name'][$ix];

          list($width, $height, $type) = getimagesize($tmp_name);

          switch ($type) {
            case IMAGETYPE_JPEG:

              $suffix = 'jpg';
              break;
            case IMAGETYPE_PNG:

              $suffix = 'png';
              break;
            default:

              throw new InternalServerErrorException(
                'JPEGかPNGファイルのみ対応しています'
              );
          }

          $file = sprintf(
                    "%s_%s_%02d.%s",
                    $account['user_name'],
                    $this->_datetime->format('Y-m-d_H:i:s'),
                    $ix + 1,
                    $suffix
                  );
          $directory = sprintf(
                         "%s/spool/%s",
                         $this->_application->getDocDirectory(),
                         $account['id']
                       );

          if (!file_exists($directory)) {

            if (!@mkdir($directory, 0777, true)) {

              throw new InternalServerErrorException(
                error_get_last()['message']
              );
            }
          }

          $path = sprintf("%s/%s", $directory, $file);
          $data[sprintf("image_%02d", $ix + 1)] = sprintf(
            "%s/%s",
            $account['id'],
            $file
          );

          move_uploaded_file($tmp_name, $path);
        }
      }
    }

    return $data;
  }

}
