<?php

namespace BereanK\Coupler\Filters;

use BereanK\Coupler\Filters\Filter;

class Contains extends Filter
{
    
    private $field;
    private $value;
    
    public function __construct($field, $value){
        $this->field = $field;
        $this->value = $value;
    }

    public function execute(){
        return 'contains(' . $this->field . "," . $this->escape($this->value) . ")";
    }
}