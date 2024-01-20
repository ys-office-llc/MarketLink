<?php
abstract class AppBase
{
  // Requestクラスのインスタンスを保持するプロパティ
  protected $_request;
  // Responseクラスのインスタンスを保持するプロパティ
  protected $_response;
  // Sessionクラスのインスタンスを保持するプロパティ
  protected $_session;
  // ConnectModelクラスのインスタンスを保持するプロパティ
  protected $_connectModel;
  // Routerクラスのインスタンスを保持するプロパティ
  protected $_router;
  // Configureクラスのインスタンスを保持するプロパティ
  protected $_configure;
  // サインイン時のコントローラーとアクションの組合せを保持するプロパティ
  protected $_signinAction = array();
  // エラー表示のオン／オフを保持するプロパティ
  protected $_displayErrors;
  // コントローラークラス名のベース部分
  const CONTROLLER = 'Controller';
  // viewsフォルダーのディレクトリ
  const VIEWDIR = '/views';
  // modelsフォルダーのディレクトリ
  const MODELSDIR = '/models';
  // ドキュメントルートのディレクトリ
  const WEBDIR = '/../../htdocs';
  // ドキュメントルートのディレクトリ
  const IMAGESDIR = '/images';
  // controllersフォルダーのディレクトリ
  const CONTROLLERSDIR = '/controllers';
  // configurationsフォルダーのディレクトリ
  const CONFIGURATIONSDIR = '/../../etc/yml';
  const VARDIR = '/../../var';

  // ***コンストラクター__construct()***
  public function __construct($dspErr){
    $this->setDisplayErrors($dspErr);
    $this->initialize();
    $this->doDbConnection();
  }

  // ***initialize()メソッド***
  protected function initialize()
  {
    // メモリ最大容量を30Mバイトへ
    // システム設定でないと有効にならなかった（2017-01-27）
    //ini_set('post_max_size', '30M');

    $this->_router       = new Router($this->getRouteDefinition());
    $this->_connectModel = new ConnectModel();
    $this->_request      = new Request();
    $this->_response     = new Response();
    $this->_session      = new Session();
    // 追記 (2016-08-19 Fri)
    // 設定情報を保存するインスタンス
    $this->_configure    = new Configure($this->getConfigurationDirectory());
  }

  // ***setDisplayErrors()メソッド
  protected function setDisplayErrors($dspErr)
  {
    if ($dspErr) {
      $this->_displayErrors = true;
      ini_set('display_errors', 1);
      //ini_set('error_reporting', E_ALL);
      // http://qiita.com/MANO_fukuoka/items/8d4ec7d4f3cc20355272
      ini_set('error_reporting', E_ALL & ~E_STRICT);
    } else {
      $this->_displayErrors = false;
      ini_set('display_errors', 0);
    }
  }

  // ***isDisplayErrors()メソッド***
  public function isDisplayErrors()
  {
    return $this->_displayErrors;
  }
  
  // ***run()メソッド***
  public function run()
  {
    try {
      $params = $this->_router->getRouteParams($this->_request->getPath());

      if ($params === false) {
        throw new FileNotFoundException('No Route'.$this->_request->getPath());
      }
      
      $controller = $params['controller'];
      $action     = $params['action'];
      $this->getContent($controller, $action, $params);
    } catch (PDOException $e) {
      $this->dispErrorPage(500, $e);
    } catch (InternalServerErrorException $e) {
      $this->dispErrorPage(500, $e);
    } catch (ForbiddenException $e) {
      $this->dispErrorPage(503, $e);
    } catch (FileNotFoundException $e) {
      $this->dispErrorPage(404, $e);
    } catch (AuthorizedException $e) {
      list($controller, $action) = $this->_signinAction;
      $this->getContent($controller, $action);
    }
    $this->_response->send();
  }

  // ***getContent()メソッド***
  public function getContent($controllerName, $action, $params = array())
  {
    $controllerClass = ucfirst($controllerName) . self::CONTROLLER;
    $controller      = $this->getControllerObject($controllerClass);

    if ($controller === false) {
        throw new FileNotFoundException(
          $controllerClass . ' Not Found.');
    }

    $content = $controller->dispatch($action, $params);
    $this->_response->setContent($content);
  }

  // ***getControllerObject()メソッド***
  protected function getControllerObject($controllerClass)
  {
    if (!class_exists($controllerClass)) {
        $controllerFile = 
          $this->getControllerDirectory() . '/' . $controllerClass . '.php';
        if (!is_readable($controllerFile)) {
          return false;
        } else {
          require_once $controllerFile;
          if (!class_exists($controllerClass)) {
            return false;
          }
        }
    }
    $controller = new $controllerClass($this);

    return $controller;
  }
  
  // ***dispErrorPage()メソッド***
  protected function dispErrorPage($code, $e)
  {
    $errors = array(
      404 => 'File Not Found.',
      500 => 'Internal Server Error.',
      503 => 'Forbidden.',
    );
    $this->_response
         ->setStatusCode($code, $errors[$code]);
    $errMessage = $this->isDisplayErrors() ? $e->getMessage() : $errors[$code];
    $errMessage = htmlspecialchars($errMessage, ENT_QUOTES, 'UTF-8');
    $html = "
<!DOCTYPE html>
<html>
  <head>
    <meta charset='UTF-8' />
    <title>HTTP {$code} Error</title>
  </head>
  <body>
    {$errMessage}
  </body>
</html>
";
    $this->_response->setContent($html);
  }

  // ***getRouteDefinition()メソッド***
  abstract protected function getRouteDefinition();

  // ***doDbConnection()メソッド***
  protected function doDbConnection() {}

  // ***getRequestObject()メソッド***
  public function getRequestObject()
  {
    return $this->_request;
  }

  // ***getResponseObject()メソッド***
  public function getResponseObject()
  {
    return $this->_response;
  }

  // ***getSessionObject()メソッド***
  public function getSessionObject()
  {
    return $this->_session;
  }

  // ***getConnectModelObject()メソッド***
  public function getConnectModelObject()
  {
    return $this->_connectModel;
  }

  // ***getConfigureObject()メソッド***
  public function getConfigureObject()
  {
    return $this->_configure;
  }

  // ***getViewDirectory()メソッド***
  public function getViewDirectory()
  {
    return $this->getRootDirectory() . self::VIEWDIR;
  }

  // ***getModelDirectory()メソッド***
  public function getModelDirectory()
  {
    return $this->getRootDirectory() . self::MODELSDIR;
  }

  // ***getDocDirectory()メソッド***
  public function getDocDirectory()
  {
    return $this->getRootDirectory() . self::WEBDIR;
  }

  // ***getImagesDirectory()メソッド***
  public function getImagesDirectory()
  {
    return $this->getDocDirectory() . self::IMAGESDIR;
  }

  // ***getConfigDirectory()メソッド***
  public function getConfigurationDirectory()
  {
    return $this->getRootDirectory() . self::CONFIGURATIONSDIR;
  }

  public function getVarDirectory()
  {
    return $this->getRootDirectory() . self::VARDIR;
  }

  // ***抽象メソッドgetRootDirectory()***
  abstract public function getRootDirectory();

  // ***getControllerDirectory()メソッド***
  public function getControllerDirectory()
  {
    return $this->getRootDirectory() . self::CONTROLLERSDIR;
  }
}
