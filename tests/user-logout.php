<?php

require_once('vendor/autoload.php');

if (isset($_POST['type']) && $_POST['type'] == 'logout') {
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


  # Call your appilication logic to load the user.
  /**
  $user_info = $logic->user->load_by($email)
    
  if ($user_info->issuer != $issuer) {
    throw new \MagicAdmin\Exception\UnAuthorizedError('UnAuthorized user login');
  }
  **/

  try {
    $magic->user->logout_by_issuer($issuer);
  } catch (Exception $e) {
    throw new \MagicAdmin\Exception\HttpException(
      $e->getMessage()
    ); 
  }

  $result = array(
    'success' => true,
    'user_info' => $user_meta
  );

  echo json_encode($result);

}

?>
