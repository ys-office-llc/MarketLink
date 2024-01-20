<?php
class Configure
{
  public  $current = [];
  private $dir     = null;

  public function __construct($dir)
  {
    $this->dir = $dir;
    $this->getConfig();
  }

  private function mergeYaml($original, $append)
  {
    if (is_array($original) and is_array($append)) {
      foreach ($append as $key =>$val) {
        if (isset($original[$key]) and
            is_array($val)  and
            is_array($original[$key])) {
          $original[$key] = $this->mergeYaml($original[$key], $val);
        } else {
          $original[$key] = $val;
        }
      }
    } elseif (!is_array($original) and (strlen($original) === 0 or $original == 0)) {
        $original = $append;
    }

    return($original);
  }

  private function getYamlFiles($dir) {
    $files = scandir($dir);
    $files = array_filter($files, function ($file) {
      return !in_array($file, array('.', '..'));
    });
 
    $list = array();
    foreach ($files as $file) {
      $fullpath = rtrim($dir, '/') . '/' . $file;
      if (is_file($fullpath)) {
        $list[] = $fullpath;
      }
      if (is_dir($fullpath)) {
        $list = array_merge($list, $this->getYamlFiles($fullpath));
      }
    }
 
    return $list;
  }

  public function getConfig()
  {
    foreach ($this->getYamlFiles($this->dir) as $file) {
      $yaml = yaml_parse_file($file);
      $this->current = $this->mergeYaml($this->current, $yaml);
    }
  }

  public function dumpConfig()
  { 
    if (count($this->current) > 0) {
      var_dump($this->current);
    }
  }

}
