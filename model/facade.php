<?php
require_once "usertype.php";

interface facadefunction
{
  public function insert($name);
  public function update($name , $id);
  public function delete($id);
  public function show();
  public function show1($id);
}

class facade 
{
public $warehouse;
public $usertype;

public function __construct()
{
    $this->usertype = new usertype();
}




public function Insertusertype($name)
{
    
    $this->usertype->insert($name);
}
public function Updateusertype($name ,$id)
{
    
    $this->usertype->update($name , $id);
}
public function Deleteusertype($id)
{
    
    $this->usertype->delete($id);
}
public function Show1usertype($id)
{
    
    return $this->usertype->show1($id);


}

public function linkusertype($usertypeid,$pageid)
{
    $this->usertype->link($usertypeid,$pageid);
}

public function Showusertype()
{
    $this->usertype->show();
    


}

}


?>