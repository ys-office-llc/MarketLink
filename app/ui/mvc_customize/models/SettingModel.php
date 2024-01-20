<?php 
class SettingModel extends BasicModel
{

  protected function resolve()
  {
    $class_paths = $this->convert();
    unset($class_paths[0]);

    return implode('_', $class_paths);
  }

}
