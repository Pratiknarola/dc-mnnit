<?php
function validate($val){
	if(is_array($val)){
		foreach($val as &$v){
			$v=validate($v);
		}
	}
	else{
		$val=htmlentities($val);
		$val=mysql_real_escape_string(trim($val));
	}
	return $val;	
}
function dbconnect(){
require_once("dbdetails.php");	//contains constants DB_HOST,DB_USER,DB_PASS
$dbhost=DB_HOST;
$dbuname=DB_USER;
$dbpassword=DB_PASS;
$dbname='dcp';		//your database name
$con=new MySQLi($dbhost,$dbuname,$dbpassword,$dbname);

if($con->connect_errno){
	die("Not able to".$con->connect_error);	
}
return $con;	
}

function get_val($conn,$col,$tab,$var,$expr,$val){
	//echo "select $col from $tab where $var $expr ?";
	$qry1=mysqli_prepare($conn,"select $col from $tab where $var $expr ?");
	$qry1->bind_param("s",$val);
	$qry1->execute();
	$qry1->bind_result($res);
	if($qry1->fetch())
		return $res;
}

function getFooter()
{
	//$val=exec('wc -l downlog.txt');


	echo '<nav class="navbar navbar-default navbar-fixed-bottom" style="background-color:#DDD;background-image:none;" id="footr"><div class="col-md-4 col-md-offset-5"><p style="margin-top:10px;"><b>Created By :</b> Vandit Jain, Harsh Agarwal, -Bloodraven- </p></div><div class="pull-right"><p style="margin-top:10px; margin-right:25px;"><b>Your IP: </b>'.$_SERVER[REMOTE_ADDR].'</div></nav>';//<b>Page Hits: </b>'.intval($val).'</p>
	?>
    <script>
	
	</script>
    <?php

}

function getHeader($page)
{
	?>
 

    <nav class="navbar navbar-default navbar-inverse " role="navigation">
<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbarCollapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
      <a class="navbar-brand" href="index.php">MNNIT DC</a>
</div>
<div class="collapse navbar-collapse navbarCollapse">
      <ul id="menu" class="nav navbar-nav ">
        <li <?= $page=="index.php"?"class=\"active\"":"";?>><a href="index.php"><span class="glyphicon glyphicon-list-alt"></span> HUBS Status</a></li>
      	<li <?= $page=="proxy.php"?"class=\"active\"":"";?>><a href="proxy.php"><span class="glyphicon glyphicon-globe"></span> Working Proxies<sup> &beta;</sup></a></li>
      	
          <li <?= $page=="addhub.php"?"class=\"active\"":"";?>><a href="addhub.php"><span class="glyphicon glyphicon-plus-sign"></span> Add new HUB</a></li>
      	  <li <?= $page=="request.php"?"class=\"active\"":"";?>><a href="request_old.php"><span class="glyphicon glyphicon-cloud-download"></span> Request File</a></li>
      	<li <?= $page=="info.php"?"class=\"active\"":"";?>><a href="info.php"><span class="glyphicon glyphicon-info-sign"></span> Info<!--<img src="new.gif" />--></a></li>
      	
      </ul>
      <ul class="nav navbar-nav navbar-right" style="margin-right:10px;">
      <?php if(isset($_SESSION['admin'])){
	  	?>
        <li <?= $page=="admin.php"?"class=\"active\"":"";?>><a href="admin.php"><span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION['user']; ?></a></li>
      <li <?= $page=="logout.php"?"class=\"active\"":"";?>><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
      <?php } else { ?>
      <li ><p class="navbar-text"><a href="admin.php"><?php echo $_SESSION['user']; ?> </a></p></li>
      <li <?= $page=="admin.php"?"class=\"active\"":"";?>><a href="admin.php"><span class="glyphicon glyphicon-user"></span> Hub Admin</a></li>
      <?php } ?>
      </ul>
    </div>
  </div>
</nav><br>
<?php
}
?>

<?php

function proxy_wget($ip,$port=3128)
{
	$ex="wget -e use_proxy=yes -e http_proxy=http://edcguest:edcguest@$ip:$port http://client.akamai.com/install/test-objects/10MB.bin -O /dev/null";
	exec($ex);
}
function ip_flush()
{
	//exec("iptables -F");
}
function proxy_check($ip,$port=3128)
{
	$arr=array();
	$ex="timeout 15 wget -e use_proxy=yes -e https_proxy=https://edcguest:edcguest@$ip:$port https://google.com -O /dev/null 2>&1";
	exec($ex,$arr);
	return $arr;
}

function get_speed($speed)
{
	if($speed>1024)
			{
				$speed=$speed/1024;
				$speed=round($speed,2);
				$speed.=" MB/s";
			}
			else
			{
				$speed=round($speed,0);
				$speed.=" KB/s";
			}
			return $speed;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function getIP()
{
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
	{
    	$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
	{
    	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else 
	{
    	$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}
function palert($msg,$url){
	echo '<script>alert("'.$msg.'"); window.location.href="'.$url.'";</script>';
}
?>
