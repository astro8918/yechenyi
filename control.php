<?php
//预定义变量
$host = "localhost";
$user = "root";
$password = "125796";
$database = "game918";
$table1 = "game_user";
$table2 = "game_gift";
$page_title = "妈妈我爱你--母亲节特别活动";
ini_set("error_reporting",E_ALL ^ E_NOTICE ^ E_DEPRECATED);
?>

<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta content="telephone=no" name="format-detection"/>
    
<title><?php $page_title ?></title>

<style type="text/css">

</style>

</head>

<body>
    

    
    <?php


	$db=new mysqli("$host","$user","$password","$database");
	if(mysqli_connect_errno())
	{
		echo "服务器连接失败!";
		exit();
	}	
	mysqli_query($db,"set names utf8");

    $name=$_POST['name'];
	$tel=$_POST['tel'];
	$userid=$_POST['userid'];
	$giftid=$_POST['giftid'];
	$gitname=$_POST['gitname'];
	$giftcounter=$_POST['giftcounter'];
	$action=$_POST["submit"];
	
	if($action=="登陆")
    {
        $query = "SELECT user_name , user_gift_invite FROM game_user WHERE user_tel =$tel";
		$result = mysqli_query($db,$query);
		$row=mysqli_fetch_row($result);
			
        if($row[0]!=$name || $row[1]!="918")
		{
				?>
				<CENTER>

				登陆信息不正确

				</CENTER>
				<?php
		} 
        else
        {
     		?>    
			<CENTER>
			<form action="" method="post" >
            <?
                echo("<input type=hidden name=\"name\" value=\"$name\">");
				echo("<input type=hidden name=\"tel\" value=\"$tel\">");
            ?>
			<br><input type="submit" name="submit" style="background:#e69528" value="查询一">   
            <br><br><input type="submit" name="submit" style="background:#e69528" value="查询二">
            <br>礼品<br><input type="text"  name="gitname" value="" >
        	<br>数量<br><input type="text"  name="giftcounter" value="">
			<br><br><input type="submit" name="submit" style="background:#e69528" value="添加">   
			</form>
			</CENTER>
    	<? 
        }
    }
	elseif($action=="查询一")
	{
		$query = "SELECT * FROM game_user";
        $result = mysqli_query($db,$query);		
        while($row=mysqli_fetch_row($result))
		{
			echo("ID：".$row[0]."  姓名：".$row[1]."  电话：".$row[2]."  邀请人ID：".$row[3]."  礼物一：".$row[4]."  礼物二：".$row[5]."  邀请数一：".$row[6]."  邀请数二：".$row[7]."  是否领取：".$row[9]."  时间：".$row[8]."+"."<br>");
		}
        ?>    
			<CENTER>
			<form action="" method="post" >
            <?
                echo("<input type=hidden name=\"name\" value=\"$name\">");
				echo("<input type=hidden name=\"tel\" value=\"$tel\">");
            ?>
			<br><input type="submit" name="submit" style="background:#e69528" value="登陆">  
            <br>ID<input type="text" name="userid" value="">
			<br><input type="submit" name="submit" style="background:#e69528" value="删除一">   
			</form>
			</CENTER>
    	<? 
	}	
	elseif($action=="查询二")
	{
		$query = "SELECT * FROM game_gift";
        $result = mysqli_query($db,$query);		
        while($row=mysqli_fetch_row($result))
		{
			echo("ID：".$row[0]."  名称：".$row[1]."  数量：".$row[2]."<br>");
		}
            ?>    
			<CENTER>
			<form action="" method="post" >
            <?
                echo("<input type=hidden name=\"name\" value=\"$name\">");
				echo("<input type=hidden name=\"tel\" value=\"$tel\">");
            ?>
			<br><input type="submit" name="submit" style="background:#e69528" value="登陆"> 
            <br>ID<input type="text" name="giftid" value="">
			<br><input type="submit" name="submit" style="background:#e69528" value="删除二">   
			</form>
			</CENTER>
    	<? 
	}
	elseif($action=="删除一")
    {
    	$query="DELETE from game_user where user_id = $userid ";
        $result = mysqli_query($db,$query);
        ?>    
			<CENTER>
			<form action="" method="post" >
            <?
                echo("<input type=hidden name=\"name\" value=\"$name\">");
				echo("<input type=hidden name=\"tel\" value=\"$tel\">");
            ?>
                删除成功！
			<br><input type="submit" name="submit" style="background:#e69528" value="登陆"> 
			</form>
			</CENTER>
    	<? 
    }
	elseif($action=="删除二")
    {
       	$query="DELETE from game_gift where gift_id = $giftid ";
       	mysqli_query($db,$query);
        ?>    
			<CENTER>
			<form action="" method="post" >
            <?
                echo("<input type=hidden name=\"name\" value=\"$name\">");
				echo("<input type=hidden name=\"tel\" value=\"$tel\">");
            ?>
                删除成功....！
			<br><input type="submit" name="submit" style="background:#e69528" value="登陆"> 
			</form>
			</CENTER>
    	<? 
    }
	elseif($action=="添加")
    {
       $query = "INSERT INTO game_gift VALUES('','$gitname', '$giftcounter')";

		mysqli_query($db,$query);
        ?>    
			<CENTER>
			<form action="" method="post" >
            <?
                echo("<input type=hidden name=\"name\" value=\"$name\">");
				echo("<input type=hidden name=\"tel\" value=\"$tel\">");
            ?>
			添加成功！
			<br><input type="submit" name="submit" style="background:#e69528" value="登陆"> 
			</form>
			</CENTER>
    	<? 
    }
else
{
	?>
        	<CENTER>
		<form action="" method="post" >
		<br>账号<br><input type="text" style="text-align:center" name="name" value="<? echo"$name";?>" >
        <br><br>密码<br><input type="text" style="text-align:center" name="tel" value="<? echo"$tel";?>">
		<br><br><input type="submit" name="submit" style="background:#e69528" value="登陆">   
		</form>
		</CENTER>
    <?
}

    ?>
</body>
</html>