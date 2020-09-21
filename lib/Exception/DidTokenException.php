<?php

namespace MagicAdmin\Exception; 

/**
 * DIDTokenException is thrown in the event that DID token is missing, 
 * DID token is malformed, given DID token has expired or 
 * signature mismatch between "proof" and "claim".
 */

class DIDTokenException extends MagicException {}  
