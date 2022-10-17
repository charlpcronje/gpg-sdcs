<?php
trait SessionTraits {
    public function sessionKeyExist($key) {
        return $this->dataKeyExist($key,$this->output->session);
    }

    public function session($dotName,$value = null) {
        if (isset($value)) {
            return $this->setData($dotName,$value,$this->output->session);
        }
        return $this->getData($dotName,null,$this->output->session);
    }
}
