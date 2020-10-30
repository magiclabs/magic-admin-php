<?php

namespace MagicAdmin\Exception; 

/**
 * RateLimitingException is thrown in the event that 
 * the SDK has sent too many requests to server in a given amount of time.
 */

class RateLimitingException extends RequestException {}  
