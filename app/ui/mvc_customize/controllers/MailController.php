<?php

require_once('Mail.php');

class MailController extends Controller
{

  public function __construct()
  {

    var_dump($this->_configure->current['configure']);

  }

  public function sendMail()
  {
  }

}
