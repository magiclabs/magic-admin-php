<?php

require_once('magic-init.php');

if (isset($_POST['type']) && $_POST['type'] == 'login') {
  if(isset($_POST['email'])) {
    $email = $_POST['email'];
  } else {
    $email = '';
  }

  $did_token = parse_authorization_header_value(
    getallheaders()['authorization']
  );

  if ($did_token == null) {
    $badRequestError = new BadRequestError(
      'Authorization header is missing or header value is invalid'
    );
    echo $badRequestError->getErrorMessage();
  }

  $magic = new Magic('sk_test_6F832D5FB8382105');
  
  try {
    $magic->token->validate($did_token);
    $issuer = $magic->token->get_issuer($did_token);
    $user_meta = $magic->user->get_metadata_by_issuer($issuer);
  } catch (Exception $e) {
    $didTokenError = new DIDTokenError(
      'DID Token is invalid: ' . $e->getMessage()
    );
    echo $didTokenError->getErrorMessage();
  }

  # Call your appilication logic to load the user.
  /**
  $user_info = $logic->user->load_by($email)
    
  if ($user_info->issuer != $issuer) {
    $unAuthorizedError = new UnAuthorizedError('UnAuthorized user login');
    echo $unAuthorizedError->getErrorMessage();
  }
  
  echo $user_info;
  **/
  
  $result = array(
    'success' => true,
    'user_info' => $user_meta
  );

  echo json_encode($result);
}

?>