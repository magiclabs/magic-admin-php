<?php

namespace MagicAdmin\Exception; 

/**
 * RateLimitException is thrown in the event that 
 * the SDK has sent too many requests to server in a given amount of time.
 */

class RateLimitException extends RequestException {} 

