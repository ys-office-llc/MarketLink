<?php 
class WrapperModel extends BasicModel
{

  protected function resolve()
  {
    $class_paths = $this->convert();

    return implode('_', $class_paths);
  }

}
