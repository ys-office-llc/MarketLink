<?php
abstract class Controller
{
  protected $_application;
  protected $_controller;
  protected $_action;
  protected $_request;
  protected $_response;
  protected $_session;
  protected $_connect_model;
  protected $_configure;
  protected $_user;
  protected $_datetime;
  protected $_authentication = array();
  // const PROTOCOL = 'http://';
  const PROTOCOL = 'https://';
  const ACTION = 'Action';

  // ***コンストラクター***
  public function __construct($application)
  {
    $this->_controller    = substr(get_class($this), 0, -10);
    $this->_view_path     = $this->convert();
    $this->_application   = $application;
    $this->_request       = $application->getRequestObject();
    $this->_response      = $application->getResponseObject();
    $this->_session       = $application->getSessionObject();
    $this->_connect_model = $application->getConnectModelObject();
    $this->_configure     = $application->getConfigureObject();
    $this->_datetime      = new DateTime();
  }

  private function convert()
  {
    $paths = array();

    $class = substr(get_class($this), 0, -10);
    preg_match_all("/[A-Z]/", $class, $matches);
    $classes = preg_split("/[A-Z]/", $class);
    $classes = array_filter($classes, "strlen");
    $classes = array_values($classes);

    foreach ($classes as $index => $value) {
      $paths[] = strtolower($matches[0][$index]) . $value;
    }

    return implode('/', $paths);
  }

  // ***dispatch()メソッド***
  public function dispatch($action, $params = array())
  {
    $this->_action = $action;
    $action_method = $action . self::ACTION;
    
    if (!method_exists($this, $action_method)) {
      $this->httpNotFound();
    }

    if ($this->isAuthentication($action)
        && !$this->_session->isAuthenticated()
    ) {
      throw new AuthorizedException();
    }
    
    $content = $this->$action_method($params);

    return $content;
  }

  // ***httpNotFound()メソッド***
  protected function httpNotFound()
  {
    throw new FileNotFoundException('File Not Found '
        . $this->_controller . '/' . $this->_action);
  }

  protected function httpForbidden()
  {
    throw new ForbiddenException('Forbidden');
  }

  // ***needsAuthentication()メソッド***
  protected function isAuthentication($action)
  {
    if ($this->_authentication === true
        || (is_array($this->_authentication)
        && in_array($action, $this->_authentication))
    ) {
      return true;
    }
    return false;
  }

  // ***render()メソッド***
  protected function render(
    $param = array(), $viewFile = null, $_layout = null
  ) {
    $info = array(
      'request'  => $this->_request,
      'base_url' => $this->_request->getBaseUrl(),
      'session'  => $this->_session,
      'PROTOCOL' => self::PROTOCOL,
    );


    $view = new View($this->_application
                          ->getViewDirectory(),
                     $info);

    if (is_null($viewFile)) {
        $viewFile = $this->_action;
    }

    if (is_null($_layout)) {
        $_layout = '_layout_';
    }

    //$path = $this->_controller . '/' .$viewFile;
    $path = $this->_view_path. '/' .$viewFile;
    $contents = $view->render($path,
                              $param,
                              $_layout);
    return $contents;
  }


  // ***redirect()メソッド***
  protected function redirect($url) {
    $host     = $this->_request->getHostName();
    $base_url = $this->_request->getBaseUrl();
    $url      = self::PROTOCOL . $host . $base_url . $url;
    $this->_response
         ->setStatusCode(302, 'Found');
    $this->_response
         ->setHeader('Location', $url);
  }

  // ***getToken()メソッド***
  protected function getToken($form) {
    $key      = 'token/' . $form;
    $tokens   = $this->_session
                     ->get($key, array());
    if (count($tokens) >= 10) {
        array_shift($tokens);
    }
    $password = session_id() . $form;
    // データベースカラムを255にしないとエラーになる。カットしてしまうから。
    $token    = password_hash(
                  $password,
                  PASSWORD_DEFAULT
                );
    $tokens[] = $token;

    $this->_session->set($key, $tokens);

    return $token;
  }

  // ***checkToken()メソッド***
  protected function checkToken($form, $token) {
    $key    = 'token/' . $form;
    $tokens = $this->_session->get($key, array());

    if (false !== ($present = array_search($token,
                                           $tokens,
                                           true))
    ){
      unset($tokens[$present]);
      $this->_session
           ->set($key, $tokens);

      return true;
    }

    return false;
  }
}
