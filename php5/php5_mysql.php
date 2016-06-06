<?php
/*
 * use microtime to count less than 1 second
 */
$start = time();

	class Test
	{

		private $db = null;
		const HOST = "localhost";
		const DBNM = "test";
		const USNM = "root";
		const PSWD = "root";

		public function __construct()
		{
			try {
				$this->db = new PDO("mysql:host=" . Test::HOST . ";dbname=" . Test::DBNM, Test::USNM, Test::PSWD);
			} catch (PDOException $e) {
				die("couldn't connect " . $e->getMessage());
			}
		}

		public function insert($n)
		{
			for ($i = 0; $i < $n; $i++):
				$stmt = $this->db->prepare("insert into userinfo(username,departname,created) values(?,?,?)");
				$stmt->execute(array("test","test",time()));
			endfor;

			return $i;
		}

		public function select($i){
			$stmt = $this->db->query("select * from userinfo");
			if($i):
				$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach ($rows as $k => $v):
					echo "\n".$v["uid"]." ".$v["username"]." ".$v["departname"]." ".$v["created"];
				endforeach;
			endif;
			return $stmt->rowCount();
		}

		public function update(){
			$stmt = $this->db->prepare("update userinfo set username=?,departname=?");
			$stmt->execute(array("username"=>"one","departname"=>"two"));
		}

		public function delete(){
			$stmt = $this->db->query("delete from userinfo");
		}
	}

$g = "";

$o = new Test();
/*
 * uncomment below methods for insert, select, update for each cycle
 */

/*
 * send no of rows
 */
$g = $o->insert(1000000);

/*
 * send whether to data from select or count the rows
 */
//$g = $o->select(1);


//$o->update();
//$o->delete();

//$t = microtime() - $start;
$t = time()-$start;

echo "\n ".$g." ".$t."\n";