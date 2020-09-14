<?php

require_once('magic-admin-php/init.php');

if (isset($_POST['type']) && $_POST['type'] == 'signup') {
  if(isset($_POST['email'])) {
    $email = $_POST['email'];
  } else {
    $email = '';
  }

  $did_token = \MagicAdmin\Util\parse_authorization_header_value(
    getallheaders()['authorization']
  );

  if ($did_token == null) {
    throw new \MagicAdmin\Exception\BadRequestException(
      'Authorization header is missing or header value is invalid'
    ); 
  }

  $magic = new \MagicAdmin\Magic('sk_test_6F832D5FB8382105');
  
  try {
    $magic->token->validate($did_token);
    $issuer = $magic->token->get_issuer($did_token);

    $user_meta = $magic->user->get_metadata_by_issuer($issuer); 
  } catch (Exception $e) {
    throw new \MagicAdmin\Exception\DIDTokenException(
      'DID Token is invalid: ' . $e->getMessage()
    ); 
  }

  if ($user_meta->data['email'] != $email) {
    throw new \MagicAdmin\Exception\UnAuthorizedException('UnAuthorized user login');
  }
    
  $logic->user->add($name, $email, $issuer);
    
  $result = array(
    'success' => true,
    'user_info' => $user_meta
  );

  echo json_encode($result);
}

?>