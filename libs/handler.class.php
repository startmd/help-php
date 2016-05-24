<?php

class StartMD_DB {

	private $db;

	public function __construct($myHost,$myUser,$myPassword,$myDB)
	{

		$this->DB=new mysqli($myHost,$myUser,$myPassword,$myDB);

	}

	public function connectDB($myHost,$myUser,$myPassword,$myDB)
	{

		$this->DB=new mysqli($myHost,$myUser,$myPassword,$myDB);

		if ($this->DB->errno) {
            
            printf("MySQLi encountered the following error:  <Br /> %s",$this->DB->error);
            
            exit;
            
        }

	}

	public function safeQuery($sql, $params, $close){
           
           $stmt = $this->DB->prepare($sql) or die ("Failed to prepared the statement! Query:".$sql);
           
           call_user_func_array(array($stmt, 'bind_param'), $this->refValues($params));
           
           $stmt->execute();
           
           if($close){
               $result = $this->DB->affected_rows;
           } else {
               $meta = $stmt->result_metadata();
            
               while ( $field = $meta->fetch_field() ) {
                   $parameters[] = &$row[$field->name];
               }  
        
            call_user_func_array(array($stmt, 'bind_result'), $this->refValues($parameters));
            $results=array();   
            while ( $stmt->fetch() ) {  
               $x = array();  
               foreach( $row as $key => $val ) {  
                  $x[$key] = $val;  
               }  
               $results[] = $x;  
            }

            $result = $results;
           }
           
           $stmt->close();
           
           return  $result;
    
    }
   
   public function refValues($arr)
   {
        if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
        {
            $refs = array();
            foreach($arr as $key => $value)
                $refs[$key] = &$arr[$key];
            return $refs;
        }
        return $arr;
    }

    public function retrieveAttribute($attr,$table,$condition,$var_type_set)
    {
        
        $attr=$this->cleanVar($attr);

        $table=$this->cleanVar($table);

        $var_type_set=$this->cleanVar($var_type_set);

        foreach ($condition as $key=>$value) {

            $value=$this->cleanVar($value);

            $condition[$key]=$value;
            
        }

        $query="SELECT `{$attr}` FROM `{$table}` WHERE `{$condition[0]}`=?";

        $stmt=$this->DB->prepare($query);

        $stmt->bind_param($var_type_set,$condition[1]);

        $stmt->execute();

        $stmt->bind_result($result);

        $stmt->fetch();

        $stmt->close();

        return $result;

    }

    public function updateAttribute($table,array $new,$condition,$type)
    {

        $query="UPDATE `pinhole_{$table}` SET `{$new[0]}`=? WHERE `{$condition[0]}`=?";

        $stmt=$this->DB->prepare($query);

        $stmt->bind_param($type,$new[1],$condition[1]);

        $stmt->execute();

        $num=$stmt->affected_rows;

        $stmt->close();

        return $num;

    }

    public function retrieve($table) 
    {

        $query=$this->DB->query("SELECT * FROM `{$table}`");

        $rows=array();

        while($row=$query->fetch_assoc()) {

            $rows[]=$row;

        }

        return $rows;

    }

}