<?php
class Request{
  // ***isPost()メソッド***
  public function isPost(){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      return true;
    }
    return false;
  }

  // ***getGet()メソッド***
  public function getGet($name, $param = null){
    if (isset($_GET[$name])) {
      return $_GET[$name];
    }
    return $param;
  }

  // ***getPost()メソッド***
  public function getPost($name, $param = null)
  {
    if (isset($_POST[$name])) {
      return $_POST[$name];
    }

    return $param;
  }

  public function getPosts()
  {

    return $_POST;
  }

  public function getSubmitType()
  {
    if (!empty(array_keys($_POST))) {
      return array_keys($_POST)[0];
    }
  }

  public function getFiles()
  {
    return $_FILES;
  }

  // ***getHostName()メソッド***
  public function getHostName(){
    if (!empty($_SERVER['HTTP_HOST'])) {
      return $_SERVER['HTTP_HOST'];
    }
    return $_SERVER['SERVER_NAME'];
  }

  // ***getRequestUri()メソッド***
  public function getRequestUri(){
    return $_SERVER['REQUEST_URI'];
  }

  // ***getBaseUrl()メソッド***
  public function getBaseUrl()
  {
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $requestUri = $this->getRequestUri();

    if (0 === strpos($requestUri, $scriptName)) {
      return $scriptName;
    } else if (0 === strpos($requestUri,
                            dirname($scriptName))
    ){
      return rtrim(dirname($scriptName), '/');
    }
    return '';
  }

  // ***getPath()メソッド***
  public function getPath(){
    $base_url = $this->getBaseUrl();
    $requestUri = $this->getRequestUri();
    
    if (false !== ($sp = strpos($requestUri, '?'))){
      $requestUri = substr($requestUri, 0, $sp);
    }
    
    $path = (string)substr($requestUri,
                           strlen($base_url));
    return $path;
  }
}
