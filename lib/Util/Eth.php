<?php

namespace MagicAdmin\Util;

use Elliptic\EC;
use kornrunner\Keccak;

class Eth
{
    public static function ecRecover(string $message, string $signature)
    {
        $msglen = \strlen($message);
        $hash = Keccak::hash("\x19Ethereum Signed Message:\n{$msglen}{$message}", 256);
        $sign = [
            'r' => \substr($signature, 2, 64),
            's' => \substr($signature, 66, 64),
        ];
        $recid = \ord(\hex2bin(\substr($signature, 130, 2))) - 27;

        if ($recid !== ($recid & 1)) {
            return false;
        }

        $ec = new EC('secp256k1');
        $pubkey = $ec->recoverPubKey($hash, $sign, $recid);

        return '0x' . \substr(Keccak::hash(\substr(\hex2bin($pubkey->encode('hex')), 1), 256), 24);
    }
}
