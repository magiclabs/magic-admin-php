<?php

require_once(MAGIC_PACKAGE_PATH.'/error.php');

/**
 * Args:
 *     issuer (str): Issuer (the signer, the "user"). This field is represented
 *     as a Decentralized Identifier populated with the user's Ethereum
 *     public key.
 *
 * Returns:
 *     public_address (str): An Ethereum public key.
 */
function parse_public_address_from_issuer($issuer) {
    try {
        $issuer_split = explode(':', $issuer);
        return strtolower($issuer_split[2]);
    } catch (Exception $e) {
        $didTokenError = new DIDTokenError(
            'Given issuer (' . $issuer . ') is malformed. Please make sure it follows the ' . 
            '`did:method-name:method-specific-id`' . get_class($didTokenError) . '(' .  $e->getMessage() . ')'
        );
        echo $didTokenError->getErrorMessage();
    }
}

function construct_issuer_with_public_address($public_address) {
    return 'did:ethr:' . $public_address;
}


