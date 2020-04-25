<?php

namespace Cora\Domain\Systems\Petrinet\Marking\Tokens;

class IntegerTokenCount extends AbstractTokenCount {
    public $value;

    public function __construct($val=0)
    {
        $this->value = intval($val);
    }

    public function add($b): TokenCountInterface {
        $val = $this->value;
        if($b instanceof IntegerTokenCount){
            return new IntegerTokenCount($val + $b->value);
        }
        else if(is_int($b)){
            return new IntegerTokenCount($val + $b);
        }
        else if($b instanceof OmegaTokenCount) {
            return new OmegaTokenCount();
        }
        return $val;
    }

    public function subtract($b): TokenCountInterface {
        $val = $this->value;
        if($b instanceof IntegerTokenCount){
            return new IntegerTokenCount($val - $b->value);
        }
        else if(is_int($b)){
            return new IntegerTokenCount($val - $b);
        }
        else if($b instanceof OmegaTokenCount) {
            return new OmegaTokenCount();
        }
        return $val;
    }

    public function greater($b): bool {
        $val = $this->value;
        if($b instanceof IntegerTokenCount) {
            return $val > $b->value;
        }
        else {
            return false;
        }
    }

    public function geq($b): bool {
        $val = $this->value;
        if($b instanceof IntegerTokenCount) {
            return $val >= $b->value;
        }
        else {
            return false;
        }
    }

    public function hash() {
        return strval($this);
    }

    public function equals($other): bool {
        return $this->value === $other->value;
    }

    public function jsonSerialize() {
        return $this->value;
    }

    public function __toString() {
        return sprintf("%d", $this->value);
    }
}