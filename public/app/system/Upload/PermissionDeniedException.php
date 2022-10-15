<?php

class PermissionDeniedException extends \Exception {
    /**
     * Initialize exception message.
     * @return void
     */
    public function __construct($message = 'Server configuration denies the creation of the folder') {
        parent::__construct($message);
    }
}