<?php

class InvalidRuleException extends \Exception {
    /**
     * Initialize exception message.
     * @return void
     */
    public function __construct($message = 'Sorry but this rule you specfied does not exist') {
        parent::__construct($message);
    }
}