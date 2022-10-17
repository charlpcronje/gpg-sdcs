<?php

trait RequestTraits {
    private function addRequestToSession() {
        if (!defined('REQUEST_ADDED')) {
            $method = strtolower(env('request.method',null,'get'));
            if (isset($_GET['controller'])) {
                if (isset($_GET['params']['_'])) {
                    unset($_GET['params']['_']);
                }
                $params = '';
                if (isset($_GET['params'])) {
                    $params = $_GET['params'];
                }
                if ($this->sessionKeyExist('request.'.$method)) {
                    $requests = $this->session('request.'.$method);
                    $prevRequest = '';
                    if (isset($requests) && is_array($requests)) {
                        $prevRequest = array_pop($requests);
                    }
                } else {
                    $prevRequest = uniqid();
                }
                $currentRequest = $_GET['controller'].$params;
                $prevGetRequests = $this->session('request.get');
                $prevGetRequest = '';
                if (isset($prevGetRequests) && is_array($prevGetRequests)) {
                    $prevGetRequest = array_pop($prevGetRequests);
                }

                if ($currentRequest !== $prevRequest) {
                    if ($this->session('request.'.$method)) {
                        //$this->session('request.'.$method.':'.count($this->session('request.'.$method)),$_GET['controller'].$params);
                    }
                    $this->session('request.'.$method.'.previous',$prevRequest);
                    $this->session('request.get.previous',$prevGetRequest);
                    $this->session('request.previous',$prevRequest);
                }
            } else {
                $this->forget('session.request');
            }
            define('REQUEST_ADDED',true);
        }
    }

    protected function back($backCount = 1,$method = 'get') {
        $requests = $this->session('request.'.$method);
        $backRequest = array_slice($requests,$backCount*-1,1)[0];
        array_splice($requests,$backCount*-1,$backCount);
        $this->setRedirect($backRequest,$method);
    }
}
