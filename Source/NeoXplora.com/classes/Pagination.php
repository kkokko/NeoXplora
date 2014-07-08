<?php 

namespace NeoX\Classes;

class Pagination {
  
  private $total;
  private $current;
  private $result = array();
  private $skip_placeholder = "skip";
  
  public function __construct($total = 0, $current = 0, $skip_placeholder = "skip") {
    $this->total = $total;
    $this->current = $current;
    $this->skip_placeholder = $skip_placeholder;
  }
  
  public function setTotal($total) {
    $this->total = $total;
  }
  
  public function setCurrent($current) {
    $this->current = $current;
  }
  
  public function setSkipPlaceholder($skip_placeholder) {
    $this->skip_placeholder = $skip_placeholder;
  }
  
  public function generate() {
    $this->result = array();
    if($this->total > 5) {
      if($this->current > 3 && $this->current < $this->total - 2) {
        $this->result[] = 1;
        $this->result[] = $this->skip_placeholder;
        $this->result[] = $this->current - 1;
        $this->result[] = $this->current;
        $this->result[] = $this->current + 1;
        $this->result[] = $this->skip_placeholder;
        $this->result[] = $this->total;
      } else if($this->current <= 3) {
        $this->result[] = 1;
        $this->result[] = 2;
        $this->result[] = 3;
        $this->result[] = 4;
        $this->result[] = $this->skip_placeholder;
        $this->result[] = $this->total;
      } else {
        $this->result[] = 1;
        $this->result[] = $this->skip_placeholder;
        $this->result[] = $this->total - 3;
        $this->result[] = $this->total - 2;
        $this->result[] = $this->total - 1;
        $this->result[] = $this->total;
      }
    } else if($this->total != 1) {
      for($i = 1; $i <= $this->total; $i++)
        $this->result[] = $i;
    }
    
    return $this->result;
  }  
  
}

?>