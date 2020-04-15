<?php

namespace Cora\Feedback;

use \Ds\Map as Map;
use \Ds\Set as Set;

use JsonSerializable;

class Feedback implements JsonSerializable {
    protected $general;
    protected $specific;

    public function __construct() {
        $this->general = new Set();
        $this->specific = new Map();
    }

   /**
    * Add feedback. If a specific element is given the feedback
    * is in regards to that specific element, otherwise it is
    * treated as general feedback.
    * @param Feedback\FeedbackCode The feedback code to add
    * @param int $element The optional element to provide feedback for
    * @return void
    **/
    public function add(int $code, ?int $element=NULL): void {
        if (is_null($element)) 
            $this->general->add($code);
        else {
            if (!$this->specific->hasKey($element)) 
                $this->specific->put($element, new Set());
            $this->specific->get($element)->add($code);
        }
    }

   /**
    * Retrieve feedback codes. If a specific element identifier
    * is given, only the feedback codes for that element are returned.
    * If an element is not provided, only the general feedback codes
    * are returned.
    * @param int $element The optional identifier for the element for
    *    which we want to retrieve the feedback codes
    * @return Set Set of feedback codes
    **/
    public function get($element = NULL): Set {
        if (is_null($element)) 
            return $this->general;
        else if ($this->specific->hasKey($element))
            return $this->specific->get($element);
        return new Set();
    }

   /**
    * Remove a piece of feedback associated with a particular element
    * @param Feedback\FeedbackCode The feedback to remove
    * @param int $element The element to remove it from
    * @return void
    **/
    public function remove($code, $element): void {
        if($this->specific->hasKey($element)) {
            $f = $this->specific->get($element);
            if($f->contains($code)) 
                $f->remove($code);
        }
    }

    public function jsonSerialize() {
        return [
            "general" => $this->general,
            "specific" => $this->specific
        ];
    }
}
