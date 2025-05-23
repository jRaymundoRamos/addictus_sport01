<?php

class NotFound extends Controllers {
    public function __construct() {
        parent::__construct();
    }

    public function notFound() {
        $this->views->getView($this, "error");
    }
}
