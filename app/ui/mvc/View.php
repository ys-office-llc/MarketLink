<?php
 
class View
{
    protected $_baseUrl;
    protected $_initialValue;
    protected $_passValues = array();

    // コンストラクター
    public function __construct($baseUrl, $initialValue = array())
    {

      $this->_baseUrl      = $baseUrl;
      $this->_initialValue = $initialValue;
    }

    public function potal()
    {
      return $this->_initialValue['PROTOCOL'].
             $this->_initialValue['request']->getHostName();
    }

    //
    public function setPageTitle($name, $value){
      $this->_passValues[$name] = $value;
    }

    public function getUserData()
    {
      return $this->_initialValue['session']->get('user');
    }

    public function getReached()
    {
      return $this->_initialValue['session']->get('reached');
    }

    public function getCounterData()
    {
      return $this->_initialValue['session']->get('counter');
    }

    public function getThreads()
    {
      return $this->_initialValue['session']->get('resources_threads');
    }

    public function getRetentionPeriod()
    {
      return $this->_initialValue['session']->get('resources_retention_period');
    }

    public function getBaseUrl()
    {
      return $this->_baseUrl;
    }

    //
    public function render(
      $filename,
      $parameters = array(),
      $_layout = false
    ) {
      $view = $this->_baseUrl . '/' . $filename . '.php';
      extract(array_merge($this->_initialValue,
                          $parameters));
      ob_start();
      ob_implicit_flush(0);
      require $view;
      $content = ob_get_clean();

      if ($_layout) {
        $content = $this->render(
          $_layout,
          array_merge($this->_passValues,
          array('_content' => $content)
        ));
      }
      return $content;
    }

    //
    public function escape($string)
    {

      return htmlspecialchars(
               $string,
               ENT_QUOTES,
               'UTF-8'
      );

    }

    public function mbDelete($string)
    {

      return trim(preg_replace(
        "/[ぁ-んァ-ン一-龠★☆〓【】◆◇♪●*+#()ー]+/u",
        '',
        $string
      ));

    }

    public function remainingTime($end_time)
    {
      date_default_timezone_set('Asia/Tokyo');

      $time_now  = new DateTime('now');
      $time_end  = new DateTime($end_time);
      $time_diff = $time_now->diff($time_end);

      return sprintf(
               "%02d日%02d時%02d分%02d秒",
               $time_diff->d,
               $time_diff->h,
               $time_diff->i,
               $time_diff->s
      );

    }
  /**
   * バイト数をフォーマットする
   * @param integer $bytes
   * @param integer $precision
   * @param array $units
   */
  public function formatBytes($bytes, $precision = 2, array $units = null)
  {
    if (abs($bytes) < 1024) {
      $precision = 0;
    }

    if (is_array($units) === false) {
      $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    }

    if ($bytes < 0) {
      $sign = '-';
      $bytes = abs($bytes);
    } else {
      $sign = '';
    }

    if ($bytes > 0) {
      $exp = floor(log($bytes) / log(1024));
      $unit = $units[$exp];
      $bytes = $bytes / pow(1024, floor($exp));
      $bytes = sprintf('%.'.$precision.'f', $bytes);
      return $sign.$bytes.' '.$unit;
    } else {
      $bytes = "0B";
      return $bytes;
    }
  }
}
