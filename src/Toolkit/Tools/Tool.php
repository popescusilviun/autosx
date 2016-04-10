<?php

namespace Toolikir\Tools;

abstract class Tool
{
    private $progress_handler = null;
    private $output_handler = null;

    public function __construct() {}

    public function __destruct() {}

    public function setProgressHandler($progress_handler) {
        $this->progress_handler = $progress_handler;
        return $this;
    }

    public function setOutputHandler($output_handler) {
        $this->output_handler = $output_handler;
        return $this;
    }

    protected function outputProgress() {
        if(!is_null($this->progress_handler)) {
            call_user_func($this->progress_handler);
            return true;
        }
        return false;
    }

    public function output($out = '') {
        if(!is_null($this->progress_handler)) {
            call_user_func($this->progress_handler, $out);
            return true;
        }
        return false;
    }
}