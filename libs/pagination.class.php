<?php

class ap_pagination 
{
	private $PAGE,$PER_PAGE,$ITEMS,$LOWER,$UPPER;
	public $TOTAL;
	public function _total() 
	{
		$TOTAL=ceil($this->ITEMS/$this->PER_PAGE);
		return intval($TOTAL);
	}
	public function _setUp($PER_PAGE,$ITEMS,$PAGE) 
	{
		$this->PER_PAGE=intval($PER_PAGE);
		$this->ITEMS=intval($ITEMS);
		$this->PAGE=intval($PAGE);
		$this->TOTAL=ceil($this->ITEMS/$this->PER_PAGE);
		$this->_limits();
		if ($this->PAGE==0)	$this->PAGE=1;
	}
	public function _limits() {
		$LOWER=($this->PAGE*$this->PER_PAGE)-$this->PER_PAGE;
		$UPPER=($this->PAGE*$this->PER_PAGE)-($this->PER_PAGE*($this->PAGE-1));
		$this->LOWER=$LOWER;
		$this->UPPER=$UPPER;
		return array($LOWER,$UPPER);
	}
	public function _pageContents(array $records,$LOWER=0,$UPPER=0) {
		if ($LOWER==0 && $UPPER==0) {
			$LOWER=$this->LOWER;
			$UPPER=$this->UPPER;
		}
		$result=array_splice($records,$LOWER,$UPPER);
		return $result;
	}
	public function _next() {
		$next=$this->PAGE+1;
		if ($next>$this->TOTAL) $next=$this->TOTAL;
		return $next;
	}
	public function _prev() {
		$prev=$this->PAGE-1;
		if($prev<1) $prev=1;
		return $prev;
	}
}