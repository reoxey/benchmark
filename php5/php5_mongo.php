<?php
/*
 * use microtime to count less than 1 second
 */
$start = time();

class Test {

    private $db = null;
    private $c;

    public function __construct()
    {
        $this->db = new MongoClient();

        /*
         * uncomment below line before inserting fresh data.
         * and comment it again for insert, select, update.
         */
        //$this->db->dropDB("test");

        $db = $this->db->selectDB("test");

        $this->c = $db->selectCollection("people");
    }

    public function insert($n){

        for($i=0;$i<$n;$i++):

            $this->c->insert(array("name"=>"test","for"=>"speed","time"=>time()));
        endfor;
    }

    public function select($i){

        $res = $this->c->find();
        if($i):
            while ($res->hasNext()):
                $d = $res->getNext();
                echo $d["_id"]." ".$d["name"]." ".$d["for"]." ".$d["time"];
                echo "\n";
            endwhile;
        endif;
        return $res->count();
    }

    public function update(){

        $this->c->update(array("name"=>"test"),array('$set'=>array("name"=>"testing")),array("multiple"=>true));
    }
}
$n="";
$o = new Test();
/*
 * uncomment below methods for insert, select, update for each cycle
 */

/*
 * send no of rows
 */
$o->insert(1000000);

/*
 * send whether to data from select or count the rows
 */
//$n = $o->select(1);

//$o->update();

//$t = microtime() - start();
$t = time() - $start;

echo "\n\t$n\t     ".(time()-$t)."\n";