<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notifications
{
    protected $CI;

    public function __construct()
    {
        $this->CI = & get_instance();
      $this->CI->load->library('email');
      $this->CI->load->model('Model');
    }


function pluralize( $count, $text )
{ if($text!='mois'){
    return $count . ( ( $count == 1 ) ? ( " $text" ) : ( " ${text}s" ) );
}else{
   return $count . ( ( $count == 1 ) ? ( " $text" ) : ( " ${text}" ) );

}
}

  function date_manage($nb_jr = 0,$date_semi=0){

    $your_date = strtotime("+".$nb_jr." day", strtotime($date_semi));

    $new_date = date("Y-m-d", $your_date++);


    return date('d-m-Y',strtotime($new_date));
}

function ago($date1,$date2)
{

    $interval = date_create($date2)->diff( date_create($date1) );
    $suffix = "";
    if ( $v = $interval->y >= 1 ) return $this->pluralize( $interval->y, 'An' ) . $suffix;
    if ( $v = $interval->m >= 1 ) return $this->pluralize( $interval->m, 'Mois' ) . $suffix;
    if ( $v = $interval->d >= 1 ) return $this->pluralize( $interval->d, 'Jr' ) . $suffix;
    if ( $v = $interval->h >= 1 ) return $this->pluralize( $interval->h, 'hr' ) . $suffix;
    if ( $v = $interval->i >= 1 ) return $this->pluralize( $interval->i, 'min' ) . $suffix;
    return $this->pluralize( $interval->s, 'sec' ) . $suffix;
}


function send_mail($emailTo = array(), $subjet, $cc_emails = array(), $message, $attach = array())
{

  $config['protocol'] = 'smtp';
  $config['smtp_host'] = 'ssl://pongo.afriregister.com';
  $config['smtp_port'] = 465;
  // $config['smtp_user'] = 'alexis@mediabox.bi';
  // $config['smtp_pass'] = 'Badia@79839653';

  $config['smtp_user'] = 'mediatrucking@mediabox.bi';
  $config['smtp_pass'] = 'Media@2024';
  $config['mailtype'] = 'html';
  $config['charset'] = 'UTF-8';
  $config['wordwrap'] = TRUE;
  $config['smtp_timeout'] = 20;
  $config['newline'] = "\r\n";
  $this->CI->email->initialize($config);
  $this->CI->email->set_mailtype("html");

  
  $this->CI->email->from('mediatrucking@mediabox.bi', 'notification');
  $this->CI->email->to($emailTo);
       // $this->CI->email->bcc('ismael@mediabox.bi');
  // if (!empty($cc_emails)) {
  //   foreach ($cc_emails as $key => $value) {
  //     $this->CI->email->cc($value);
  //   }
  // }
  $this->CI->email->subject($subjet);
  $this->CI->email->message($message);

  if (!empty($attach)) {
    foreach ($attach as $att)
      $this->CI->email->attach($att);
  }
  $this->CI->email->send();
       /* if (!$this->CI->email->send()) {
            show_error($this->CI->email->print_debugger());
        } 
        else;*/
       // echo $this->CI->email->print_debugger();

      }


   public function smtp_mail($emailTo,$subjet,$cc_emails=NULL,$message,$attach=NULL)
   {     
        $this->CI = & get_instance();
        $this->CI->load->library('email');
        $config['protocol'] = 'smtp';
        //$config['smtp_crypto'] = 'tls';
        $config['smtp_host'] = 'ssl://twiga.afriregister.co.ke';
        $config['smtp_port'] = 465;
        $config['smtp_user'] = 'helpdesk_comesa@mediabox.bi';
        $config['smtp_pass'] = 'mediabox@comesa2018';
        $config['mailtype'] = 'html';
        $config['charset'] = 'UTF-8';
        $config['wordwrap'] = TRUE;
        $config['smtp_timeout'] = 20;
       // $config['priority'] = '1';


        $this->CI->email->initialize($config);
        $this->CI->email->set_mailtype("html");

        // Load email library and passing configured values to email library 
        $this->CI->load->library('email', $config);
        $this->CI->email->set_newline("\r\n");

        $this->CI->email->from('helpdesk_comesa@mediabox.bi', 'MIFA Projet');
        $this->CI->email->to($emailTo);
        //$this->CI->email->bcc('ismael@mediabox.bi');

          if (!empty($cc_emails)) {
          foreach ($cc_emails as $key => $value) {
          $this->CI->email->cc($value);
          }
          }
         
        $this->CI->email->subject($subjet);
        $this->CI->email->message($message);
        
        if(!empty($attach))
          {
            $this->email->attach($attach);
         }

        if (!$this->CI->email->send()) {
            show_error($this->CI->email->print_debugger());
        } else
            echo $this->CI->email->print_debugger();
   }

   public function send_sms($string_tel = NULL,$string_msg)
   {
        $data = '{"urns": ["' . $string_tel . '"],"text":"' . $string_msg . '"}';

        $header = array();
        $header [0] = 'Authorization:Token 8ae3e567ec75aeac4fab42a43c64edf52f0eb736';  //pas d'espace entre Authori et : et Token
        $header [1] = 'Content-Type:application/json';
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'https://sms.ubuviz.com/api/v2/broadcasts.json');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($curl);
       // $result = json_decode($result);

        return $result;
   }


   public function generate_UIID($taille)
   {
     $Caracteres = '0123456789'; 
      $QuantidadeCaracteres = strlen($Caracteres); 
      $QuantidadeCaracteres--; 

      $Hash=NULL; 
        for($x=1;$x<=$taille;$x++){ 
            $Posicao = rand(0,$QuantidadeCaracteres); 
            $Hash .= substr($Caracteres,$Posicao,1); 
        }

        return $Hash; 
   }

    public function generate_password($taille)
   {
     $Caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMOPQRSTUVXWYZ0123456789,.@{-_/#'; 
      $QuantidadeCaracteres = strlen($Caracteres); 
      $QuantidadeCaracteres--; 

      $Hash=NULL; 
        for($x=1;$x<=$taille;$x++){ 
            $Posicao = rand(0,$QuantidadeCaracteres); 
            $Hash .= substr($Caracteres,$Posicao,1); 
        }
        return $Hash; 
   }




function sendMessage($phone = null, $message = null){

      $phones = str_replace(" ", "", $phone);
      $phon = str_replace("+", "", $phones);
      $phone = trim($phon);
      
      $methode = "sendMessage";
      $data =  array('phone'=>$phone, 'body'=>$message);

      $header = array();
      $instance = "instance449097";
      $token = "oxlisbyj3z9sqtfy";
      //https://api.chat-api.com/instance449097/message?token=oxlisbyj3z9sqtfy
      $url = "https://api.chat-api.com/".$instance."/".$methode."?token=".$token;
      $header[1] = 'Content-Type:application/json';

      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
      curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);

      if ($err) {
        return 205;
      } else {
        $respo =  json_decode($response);
        // $result = ($respo->sent =="true") ?  200 : 205;
        return $respo;
      }


    }











   


   //notification sur whatsapp
   public function whatsapp($phone,$message)
   {
// {
//   "created": true,
//   "message": null,
//   "chatId": "25769176202-1585228756@g.us",
//   "groupInviteLink": "https://chat.whatsapp.com/Jwrl92pPGqCJNCafwiZZWl"
// }
    $data = [
    'phone' =>"'".$phone."'", // Receivers phone
    'body' => "".$message."" // Message
            ];

    $json = json_encode($data); // Encode data to JSON
    // URL for request POST /message
    $url = 'https://api.chat-api.com/instance110613/sendMessage?token=44k8xwmfiveo2h53';

    // Make a POST request
    $options = stream_context_create(['http' => [
        'method'  => 'POST',
        'header'  => 'Content-type: application/json',
        'content' => $json
        ]
        ]);
     // Send a request
     $result = file_get_contents($url, false, $options);


   }
    //Fonction pour la generation de code automatique
   
    function generate_code($taille = 0){

     $Caracteres = '0123456789'; 
      $QuantidadeCaracteres = strlen($Caracteres); 
      $QuantidadeCaracteres--; 

      $Hash=NULL; 
        for($x=1;$x<=$taille;$x++){ 
            $Posicao = rand(0,$QuantidadeCaracteres); 
            $Hash .= substr($Caracteres,$Posicao,1); 
        }

        return $Hash;

   }




   function pointLocation() {
}

function pointInPolygon($point, $polygon, $pointOnVertex = true) {
$this->pointOnVertex = $pointOnVertex;

// Transform string coordinates into arrays with x and y values
$point = $this->pointStringToCoordinates($point);
$vertices = array();
foreach ($polygon as $vertex) {
$vertices[] = $this->pointStringToCoordinates($vertex);
}

// Check if the point sits exactly on a vertex
if ($this->pointOnVertex == true and $this->pointOnVertex($point, $vertices) == true) {
return "vertex";
}

// Check if the point is inside the polygon or on the boundary
$intersections = 0;
$vertices_count = count($vertices);

for ($i=1; $i < $vertices_count; $i++) {
$vertex1 = $vertices[$i-1];
$vertex2 = $vertices[$i];
if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
return "boundary";
}
if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) {
$xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x'];
if ($xinters == $point['x']) { // Check if point is on the polygon boundary (other than horizontal)
return "boundary";
}
if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
$intersections++;
}
}
}
// If the number of edges we passed through is odd, then it's in the polygon.
if ($intersections % 2 != 0) {
return "inside";
} else {
return "outside";
}
}

function pointOnVertex($point, $vertices) {
foreach($vertices as $vertex) {
if ($point == $vertex) {
return true;
}
}

}

function pointStringToCoordinates($pointString) {
$coordinates = explode(" ", $pointString);
return array("x" => $coordinates[0], "y" => $coordinates[1]);
}

}

?>
