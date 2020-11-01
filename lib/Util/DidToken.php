<?php

namespace MagicAdmin\Util;

use MagicAdmin\Exception;

class DidToken
{
    /**
     * Args:
     *     issuer (str): Issuer (the signer, the "user"). This field is represented
     *     as a Decentralized Identifier populated with the user's Ethereum
     *     public key.
     *
     * Returns:
     *     public_address (str): An Ethereum public key.
     *
     * @param mixed $issuer
     */
    public static function parse_public_address_from_issuer($issuer)
    {
        try {
            $issuer_split = \explode(':', $issuer);

            return \strtolower($issuer_split[2]);
        } catch (Exception $e) {
            throw new DIDTokenException(
                'Given issuer (' . $issuer . ') is malformed. Please make sure it follows the ' .
                '`did:method-name:method-specific-id`' . \get_class($didTokenError) . '(' . $e->getMessage() . ')'
            );
        }
    }

    public static function construct_issuer_with_public_address($public_address)
    {
        return 'did:ethr:' . $public_address;
    }
}
