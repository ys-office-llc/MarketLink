<?php
class ApiLineBotMessageController extends BasicController
{
  private $access_token = null;

  protected $_authentication = array(

  );

  public function replyAction()
  {

    $json_string = null;
    $json_object = null;

    $this->access_token = 'rhFz3dv48Deg/wEni9VjWY+CMlkCX2gc5xlYvUa0ASIVuaP9roub/cUqnkuOM1cDMPdrOgX1+fQkTM7WeuOhFdBM2dr90pHfsq31jUy4iMIreuWi/VCKYzb6xNGOMsgBHUqTG9NURhnqlkuV7T89UgdB04t89/1O/w1cDnyilFU=';

    $json_string = file_get_contents('php://input');
    $json_object = json_decode($json_string);
    $type = $json_object->{'events'}[0]->{'message'}->{'type'};
    $text = $json_object->{'events'}[0]->{'message'}->{'text'};
    $reply_token = $json_object->{'events'}[0]->{'replyToken'};

    //メッセージ以外のときは何も返さず終了
    if ($type != 'text') {

      exit;
    }

    //返信データ作成
    $response_format_text = array(
      'type' => 'text',
      'text' => 'from Market Link for CAMERA V3.0'
    );

    $post_data = array(
      'replyToken' => $reply_token,
      'messages'   => [$response_format_text]
    );

    $ch = curl_init('https://api.line.me/v2/bot/message/reply');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json; charser=UTF-8',
      'Authorization: Bearer '.$this->access_token
    ));

    $result = curl_exec($ch);
    curl_close($ch);
  }

}
