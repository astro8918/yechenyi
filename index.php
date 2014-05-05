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

<body bgcolor="#a41c1c" background="pic\bg.jpg">
<font color="white" weight="bold">
<p align="center">  <IMG SRC="pic\logo.png" style="width:80%;"></p>    
    
    <? 
   


    $submitgif=$_POST['submitgif'];
	$giftinvite=$_POST['giftinvite'];	
	$name=$_POST['name'];
	$tel=$_POST['tel'];
	$search=$_POST['search'];
	$back=$_POST['back'];
	$action=$_GET["action"];
	
	
	$db=new mysqli("$host","$user","$password","$database");
	if(mysqli_connect_errno())
	{
		echo "服务器连接失败!";
		exit();
	}	
	mysqli_query($db,"set names utf8");
	
	if($action=="find")
    {
        ?>
		<CENTER>
		<form action="index.php?action=submit" method="post">
                                                <IMG SRC="pic\hbh.png" style="width:70%;" align="center">
        			<div style="width:70%; float:center;background:#c81900">
		<?
        echo("<input type=hidden name=\"name\" value=\"$name\">");
		echo("<input type=hidden name=\"tel\" value=\"$tel\">");
        $querys = "SELECT user_gift1 , user_gift2 ,user_get FROM $table1 WHERE user_tel =$search";
		$results = mysqli_query($db,$querys);
		$rows=mysqli_fetch_row($results);
        
        if($rows[0]=="")
        {
            echo "<br>您未参加本次活动！";
        }
        else
        {
        $querys1 = "SELECT gift_name  FROM $table2 WHERE gift_id =$rows[0]";
		$results1 = mysqli_query($db,$querys1);
        $rows1=mysqli_fetch_row($results1);
        if($rows1[0]=="")
        {
            echo "<br>礼品一："."未获得";
        }
        else
        {
			
           echo "<br>礼品一："."$rows1[0]";
            
           if($rows[2]=="1"||$rows[2]=="3")
       		{
        		echo "（已使用）";
        	}
       		else
        	{
        		echo("<input type=hidden name=\"search\" value=\"$search\">");
        	
            	?>
            	<br><input type="submit" name="back" style="background:#e69528" value="使用奖品一">
        		<?
        	}
        }
        
        
        $querys2 = "SELECT gift_name  FROM $table2 WHERE gift_id =$rows[1]";
		$results2 = mysqli_query($db,$querys2);
        $rows2=mysqli_fetch_row($results2);
		if($rows2[0]=="")
        {
            echo "<br>礼品二："."未获得";
        }
        else
        {

            echo "<br>礼品二："."$rows2[0]";
            if($rows[2]=="2"||$rows[2]=="3")
       		{
        		echo "（已使用）";
        	}
       		else
        	{
        		echo("<input type=hidden name=\"search\" value=\"$search\">");
        	
            	?>
            	<br><input type="submit" name="back" style="background:#e69528" value="使用奖品二">
        		<?
        	}
        }
            
       
        }

        ?>
                                                                                                        </div>
        	<IMG SRC="pic\hbf.png" style="width:70%;" align="center">  
		</form>
		</CENTER>
		<?php
        
    	
    }
	elseif($action=="up")
	{
		if($submitgif=="确定")
		{
            session_start();
			if(isset($_POST['originator2'])) {
    		if($_POST['originator2'] == $_SESSION['code2']){
                
		?>
		<CENTER>
		<form action="index.php?action=submit" method="post">
                                    <IMG SRC="pic\hbh.png" style="width:70%;" align="center">
        			<div style="width:70%; float:center;background:#c81900">
		<?
        if(!is_numeric($giftinvite)||strlen($giftinvite)>9||strlen($giftinvite)<8)
        {
        	echo("<input type=hidden name=\"name\" value=\"$name\">");
			echo("<input type=hidden name=\"tel\" value=\"$tel\">");
			?>
                        <br><br>
			请输入正确的邀请码!
			<br><br><input type="submit" name="back" style="background:#e69528" value="返回">
		<?
        }
        else
        {
		$queryu = "SELECT user_gift1_counter , user_tel FROM $table1 WHERE user_id =$giftinvite";
		$resultu = mysqli_query($db,$queryu);
		$rowu=mysqli_fetch_row($resultu);
		if($rowu[0]=="")
		{
			echo("<input type=hidden name=\"name\" value=\"$name\">");
			echo("<input type=hidden name=\"tel\" value=\"$tel\">");
			?>
                        <br><br>
			请输入正确的邀请码!
			<br><br><input type="submit" name="back" style="background:#e69528" value="返回">
		<?
		}
		else 
		{
			if($rowu[1]==$tel)
			{
				echo("<input type=hidden name=\"name\" value=\"$name\">");
				echo("<input type=hidden name=\"tel\" value=\"$tel\">");
				?>
                        <br><br>
				不能使用自己的邀请码!
				<br><br><input type="submit" name="back" style="background:#e69528" value="返回">
			<?
			}
			elseif($rowu[0]<$invite_counter)
			{
                $queryu3 = "UPDATE $table1 set user_gift_invite = $giftinvite WHERE user_tel =$tel";
				mysqli_query($db,$queryu3);
                
                $queryuu = "SELECT user_tel FROM $table1 WHERE user_gift_invite =$giftinvite";
				$resultuu = mysqli_query($db,$queryuu);
                $counteruu=$resultuu->num_rows;
                
				$queryu1 = "UPDATE $table1 set user_gift1_counter = $counteruu WHERE user_id =$giftinvite";
				mysqli_query($db,$queryu1);
				
				$queryu4 = "UPDATE $table1 set user_gift2_counter = $counteruu WHERE user_gift_invite =$giftinvite";
				mysqli_query($db,$queryu4);
				
				$queryu2 = "UPDATE $table1 set user_gift2_counter = $counteruu WHERE user_tel =$tel";
				mysqli_query($db,$queryu2);
				
				
				echo("<input type=hidden name=\"name\" value=\"$name\">");
				echo("<input type=hidden name=\"tel\" value=\"$tel\">");
				
				?>
                        <br><br>
				邀请成功!
				<br><br><input type="submit" name="back" style="background:#e69528" value="返回">
				<?
			}
			else 
			{
				echo("<input type=hidden name=\"name\" value=\"$name\">");
				echo("<input type=hidden name=\"tel\" value=\"$tel\">");
				
				?>
                        <br><br>
				对方邀请次数已满!
				<br><br><input type="submit" name="back" style="background:#e69528" value="返回">
				<?
			}
        	}
		}
		?>
                                                                                </div>
        	<IMG SRC="pic\hbf.png" style="width:70%;" align="center">  
			</form>
			</CENTER>
			<?php
            unset($_SESSION["code2"]);   
            }else{
    		echo "请不要刷新本页面或重复提交表单";
    			}
            } 
		}
		elseif($submitgif=="领取福袋")
		{
            
            session_start();
			if(isset($_POST['originator1'])) {
    		if($_POST['originator1'] == $_SESSION['code1'])
            {
                
				$queryg1 = "SELECT gift_id , gift_name , gift_counter FROM $table2 WHERE gift_counter > 0 ORDER BY RAND() LIMIT 1";
				$resultg1 = mysqli_query($db,$queryg1);
                $counterlq=$resultg1->num_rows;
				$rowg1=mysqli_fetch_row($resultg1);
			
               if($counterlq==0)
                {
                	?>
					<CENTER>
					<form action="index.php?action=submit" method="post">
                                                <IMG SRC="pic\hbh.png" style="width:70%;" align="center">
        			<div style="width:70%; float:center;background:#c81900">
					<?
					echo("<input type=hidden name=\"name\" value=\"$name\">");
					echo("<input type=hidden name=\"tel\" value=\"$tel\">");
                     ?>
					<br>
                    <br>对不起
                        <br>今天的福袋奖品已经被抢光了
                    <br>不要灰心
                        <br>您获得一次开福袋的机会
                    <br>请于明天九点后再来开福袋
                    <br>机会是平等的
                        <br>看您的运气喽~
                    <br>

					<br><input type="submit" name="back" style="background:#e69528" value="返回">
                                                        </div>
        	<IMG SRC="pic\hbf.png" style="width:70%;" align="center">  
					</form>
					</CENTER>
					<?
                }
                else
                {
					$queryg2 = "UPDATE $table2 set gift_counter = $rowg1[2]-1 WHERE gift_id =$rowg1[0]";
					mysqli_query($db,$queryg2);
					$queryg3 = "UPDATE $table1 set user_gift1 = $rowg1[0] WHERE user_tel =$tel";
					mysqli_query($db,$queryg3);
			

					?>
					<CENTER>
					<form action="index.php?action=submit" method="post">
                        <IMG SRC="pic\hbh.png" style="width:70%;" align="center">
        			<div style="width:70%; float:center;background:#c81900">
                        <br>
                        <br>
					<?
					echo("<input type=hidden name=\"name\" value=\"$name\">");
					echo("<input type=hidden name=\"tel\" value=\"$tel\">");
					echo "$rowg1[1]";
                    if($rowg1[1]=="谢谢参与")
                    {?>
                        <br>很遗憾，您的运气稍微差了一点点，福袋是空的。快叫您的小伙伴完成邀请任务，一起来抢福袋，大礼等着您！
                    	<?   
                    }
                    else
                    {
                    	?>
                        <br>恭喜您获得<?echo"$rowg1[1]"; ?>！喜悦之情要跟小伙伴们一起分享哦。快叫您的小伙伴完成邀请任务，一起来抢福袋，大礼等着您！
                        <?
                    }
					?>
					<br><br><input type="submit" name="back" style="background:#e69528" value="返回">
                                                        </div>
        	<IMG SRC="pic\hbf.png" style="width:70%;" align="center">  
					</form>
					</CENTER>
					<?
                }
                unset($_SESSION["code1"]);   
            }else
            	{
    				echo "请不要刷新本页面或重复提交表单";
    			}
            }
			
		}
		elseif($submitgif=="分享福袋")
		{ 
            session_start();
			if(isset($_POST['originator3'])) {
    		if($_POST['originator3'] == $_SESSION['code3']){
                
			$queryg1 = "SELECT gift_id , gift_name , gift_counter FROM $table2 WHERE gift_counter > 0 ORDER BY RAND() LIMIT 1";
			$resultg1 = mysqli_query($db,$queryg1);
            $counterlq=$resultg1->num_rows;
			$rowg1=mysqli_fetch_row($resultg1);

                if($counterlq==0)
                {
                	?>
					<CENTER>
					<form action="index.php?action=submit" method="post">
                        <IMG SRC="pic\hbh.png" style="width:70%;" align="center">
        			<div style="width:70%; float:center;background:#c81900">
					<?
					echo("<input type=hidden name=\"name\" value=\"$name\">");
					echo("<input type=hidden name=\"tel\" value=\"$tel\">");
                    ?>
					<br>
                    <br>对不起
                        <br>今天的福袋奖品已经被抢光了
                    <br>不要灰心
                        <br>您获得一次开福袋的机会
                    <br>请于明天九点后再来开福袋
                    <br>机会是平等的
                        <br>看您的运气喽~
                    <br>

					<br><input type="submit" name="back" style="background:#e69528" value="返回">
                    </div>
        			<IMG SRC="pic\hbf.png" style="width:70%;" align="center">  
					</form>
					</CENTER>
					<?
                }
                else
                {
					$queryg2 = "UPDATE $table2 set gift_counter = $rowg1[2]-1 WHERE gift_id =$rowg1[0]";
					mysqli_query($db,$queryg2);
					$queryg3 = "UPDATE $table1 set user_gift2 = $rowg1[0] WHERE user_tel =$tel";
					mysqli_query($db,$queryg3);
			
					?>
					<CENTER>
					<form action="index.php?action=submit" method="post">
                    <IMG SRC="pic\hbh.png" style="width:70%;" align="center">
        			<div style="width:70%; float:center;background:#c81900">
                        <br><br>
					<?
					echo("<input type=hidden name=\"name\" value=\"$name\">");
					echo("<input type=hidden name=\"tel\" value=\"$tel\">");
					echo "$rowg1[1]";
                     if($rowg1[1]=="谢谢参与")
                    {?>
                        <br>很遗憾，您的运气稍微差了一点点，福袋是空的。快将您的邀请码发送给更多的小伙伴，一起来抢福袋，大礼等着您！
                    	<?   
                    }
                    else
                    {
                    	?>
                        <br>恭喜您获得<?echo"$rowg1[1]"; ?>！喜悦之情要跟小伙伴们一起分享哦。快将您的邀请码发送给更多的小伙伴，一起来抢福袋，大礼等着您！
                        <?
                    }
					?>
					<br><br><input type="submit" name="back" style="background:#e69528" value="返回">
					</div>
        			<IMG SRC="pic\hbf.png" style="width:70%;" align="center">  
                    </form>
					</CENTER>
					<?
                }
           		 unset($_SESSION["code3"]);   
            }else{
    		echo "请不要刷新本页面或重复提交表单";
    			}
            }
		}
        elseif($submitgif=="使用福袋一")
        {
            $queryglj1 = "SELECT user_get  FROM $table1 WHERE user_tel =$tel";
			$resultglj1 = mysqli_query($db,$queryglj1);
			$rowglj1=mysqli_fetch_row($resultglj1);
            
            if($rowglj1[0]==2)
            {
            	$queryglj = "UPDATE $table1 set user_get = 3 WHERE user_tel =$tel";
				mysqli_query($db,$queryglj);
            }
            else
            {
            	$queryglj = "UPDATE $table1 set user_get = 1 WHERE user_tel =$tel";
				mysqli_query($db,$queryglj);
            }
            
            ?>
			<CENTER>
			<form action="index.php?action=submit" method="post">
            <IMG SRC="pic\hbh.png" style="width:70%;" align="center">
        	<div style="width:70%; float:center;background:#c81900">
                <br><br>
			<?
			echo("<input type=hidden name=\"name\" value=\"$name\">");
			echo("<input type=hidden name=\"tel\" value=\"$tel\">");
			echo"成功使用了福袋！";
			?>
			<br><br><input type="submit" name="back" style="background:#e69528" value="返回">
            </div>
        	<IMG SRC="pic\hbf.png" style="width:70%;" align="center">  
			</form>
			</CENTER>
			<?
        }
        elseif($submitgif=="使用福袋二")
        {
            $queryglj1 = "SELECT user_get  FROM $table1 WHERE user_tel =$tel";
			$resultglj1 = mysqli_query($db,$queryglj1);
			$rowglj1=mysqli_fetch_row($resultglj1);
            
            if($rowglj1[0]==1)
            {
            	$queryglj = "UPDATE $table1 set user_get = 3 WHERE user_tel =$tel";
				mysqli_query($db,$queryglj);
            }
            else
            {
        		$queryglj = "UPDATE $table1 set user_get = 2 WHERE user_tel =$tel";
				mysqli_query($db,$queryglj);
            }
            
            ?>
			<CENTER>
			<form action="index.php?action=submit" method="post">
            <IMG SRC="pic\hbh.png" style="width:70%;" align="center">
        	<div style="width:70%; float:center;background:#c81900">
                <br><br>
			<?
			echo("<input type=hidden name=\"name\" value=\"$name\">");
			echo("<input type=hidden name=\"tel\" value=\"$tel\">");
			echo"成功使用了福袋！";
			?>
			<br><br><input type="submit" name="back" style="background:#e69528" value="返回">
            </div>
        	<IMG SRC="pic\hbf.png" style="width:70%;" align="center">  
			</form>
			</CENTER>
			<?
        }
	}
	else if($action=="submit")
	{
	
        if($back=="使用奖品一"&& $search!="")
        {
            $queryglj1 = "SELECT user_get  FROM $table1 WHERE user_tel =$search";
			$resultglj1 = mysqli_query($db,$queryglj1);
			$rowglj1=mysqli_fetch_row($resultglj1);
            
            if($rowglj1[0]==2)
            {
            	$queryglj = "UPDATE $table1 set user_get = 3 WHERE user_tel =$search";
				mysqli_query($db,$queryglj);
            }
            else
            {
            	$queryglj = "UPDATE $table1 set user_get = 1 WHERE user_tel =$search";
				mysqli_query($db,$queryglj);
            }
        }
        elseif($back=="使用奖品二"&& $search!="")
        {
            $queryglj1 = "SELECT user_get  FROM $table1 WHERE user_tel =$search";
			$resultglj1 = mysqli_query($db,$queryglj1);
			$rowglj1=mysqli_fetch_row($resultglj1);
            
            if($rowglj1[0]==1)


            {
            	$queryglj = "UPDATE $table1 set user_get = 3 WHERE user_tel =$search";
				mysqli_query($db,$queryglj);
            }
            else
            {
        		$queryglj = "UPDATE $table1 set user_get = 2 WHERE user_tel =$search";
				mysqli_query($db,$queryglj);
            }
        }
        
        //if(!is_numeric($tel)||strlen($tel)!=11)
        if(!preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$/",$tel))
        {?>
			<CENTER>
			<form action="index.php?action=index" method="post">
            <IMG SRC="pic\hbh.png" style="width:70%;" align="center">
        	<div style="width:70%; float:center;background:#c81900">
                <br><br>
			请输入正确的手机号码!
			<br><br><input type="submit" name="back" style="background:#e69528" value="返回">
            </div>
        	<IMG SRC="pic\hbf.png" style="width:70%;" align="center">    
			</form>
			</CENTER>
			<?php
		}
		elseif($name==""||strlen($name)>10)
		{
			?>
			<CENTER>
			<form action="index.php?action=index" method="post">
            <IMG SRC="pic\hbh.png" style="width:70%;" align="center">
        	<div style="width:70%; float:center;background:#c81900">
                <br><br>
			名字不能为空，或者大于三个中文字！
			<br><br><input type="submit" name="back" style="background:#e69528" value="返回">
            </div>
        	<IMG SRC="pic\hbf.png" style="width:70%;" align="center">    
			</form>
			</CENTER>
			<?php
		}
		else 
		{
            if(!isset($_COOKIE['user_tel'])||$_COOKIE['user_tel']!=$tel)
    		{
    		setcookie('user_name',$name,time()+3600*24*30);
        	setcookie('user_tel',$tel,time()+3600*24*30);
   			}
            
            

			$query = "SELECT user_name , user_gift_invite FROM game_user WHERE user_tel =$tel";
			$result = mysqli_query($db,$query);
			$row=mysqli_fetch_row($result);
			
			if($row[0]!=$name&&$row[0]!="")
			{
				?>
				<CENTER>
				<form action="index.php?action=index" method="post">
                <IMG SRC="pic\hbh.png" style="width:70%;" align="center">
        		<div style="width:70%; float:center;background:#c81900">
                    <br><br>
				登陆信息不正确
				<br><br><input type="submit" name="back" style="background:#e69528" value="返回">
                </div>
        		<IMG SRC="pic\hbf.png" style="width:70%;" align="center"> 
				</form>
				</CENTER>
				<?php
			} 
            elseif($row[1]=="918")
            {
            	?>
            	<CENTER>
				<form action="index.php?action=find" method="post">
            	<IMG SRC="pic\hbh.png" style="width:70%;" align="center">
        		<div style="width:70%; float:center;background:#c81900">
                <?
                echo("<input type=hidden name=\"name\" value=\"$name\">");
				echo("<input type=hidden name=\"tel\" value=\"$tel\">");
                ?>
				<br>号码:<input type="text" name="search">
				<br><br><input type="submit" name="back" style="background:#e69528" value="查询">
                </div>
        		<IMG SRC="pic\hbf.png" style="width:70%;" align="center">   
				</form>
				</CENTER>
    		<?php
            }
			else
			{	
				if($row[0]=="")
				{
                    //邀请码限制发放
                    //$queryadd = "SELECT user_tel FROM $table1";
                    //$resultadd = mysqli_query($db,$queryadd);
                    //$counteradd=$resultadd->num_rows;
                    
                    //if($counteradd>=$gift_counter)
                    //{
                    //$query = "INSERT INTO $table1 VALUES('','$name', '$tel', '', '0','0','0','0',NOW() , '0','1')";
                    //}
                    //else
                    //{
						$query = "INSERT INTO $table1 VALUES('','$name', '$tel', '', '0','0','0','0',NOW() , '0' ,'0')";
                    //}
                    mysqli_query($db,$query);
				}
				$query1="SELECT user_id , user_name , user_gift_invite , user_gift1 , user_gift2 , user_gift1_counter ,user_gift2_counter ,user_full FROM $table1 WHERE user_tel =$tel";
				$result1 = mysqli_query($db,$query1);
				$row1=mysqli_fetch_row($result1);
				?>
				<CENTER>
				<form action="index.php?action=up" method="post">
                <IMG SRC="pic\hbh.png" style="width:70%;" align="center">
        		<div style="width:70%; float:center;background:#c81900">
				<?
				echo("<input type=hidden name=\"name\" value=\"$name\">");
				echo("<input type=hidden name=\"tel\" value=\"$tel\">");
                
                //if($row1[7]=="1")
                //{
                //  <br>本次活动邀请码已领取结束！
                //}
                //else
                //{
				if($row1[3]=="0")
                {
					if($row1[5]==$invite_counter)
					{
                    session_start();                //根据当前SESSION生成随机数
					$code1 = mt_rand(0,1000000);
					$_SESSION['code1'] = $code1;      //将此随机数暂存入到session
                        
                    ?>
                    <input type="hidden" name="originator1" value="<?php echo $code1;?>">
                    <br><IMG SRC="pic\bbg.png" style="width:30%;">
                    	<br><br><input type="submit" name="submitgif" style="background:#e69528" value="领取福袋">		
					<?
					}
					else
					{
					?>
					<br>您的邀请码为：<? echo"$row1[0]"; ?>
                    <small><i>
					<br>（您可以将活动规则一起复制发送给朋友）
        			</i></small>
					<br>您已邀请了：<? echo"$row1[5]"; ?>个人
			
					<?
					}
				}
				else 
				{
					$query2="SELECT gift_name FROM $table2 WHERE gift_id =$row1[3]";
					$result2 = mysqli_query($db,$query2);
					$row2=mysqli_fetch_row($result2);
					?>
                    <br><IMG SRC="pic\bbg.png" style="width:30%;">
					<br><? echo"$row2[0]";
                    
                    if($row2[0]!="谢谢参与")
                        {
                   	 		$queryglj1 = "SELECT user_get  FROM $table1 WHERE user_tel =$tel";
							$resultglj1 = mysqli_query($db,$queryglj1);
							$rowglj1=mysqli_fetch_row($resultglj1);
                    
                  			if($rowglj1[0]==1||$rowglj1[0]==3)
                    		{
                    			?>
                    			(已使用)	
                    			<?
                    		}
                    		else
                    		{?>
                    			<br><br><input type="submit" name="submitgif" style="background:#e69528" value="使用福袋一">	
                      		  	<small><i>
								<br>（请在商家的指导下使用福袋，自己点击使用视为无效）
        						</i></small>
                    		<?
                    		}
                    }
                    else
                    {?>
                    	<br>快叫您的小伙伴完成邀请任务，一起来抢福袋，大礼等着您！
                    <?
                    }
				}
                //}
			
                ?>
                    <hr style="width:90%"/>
                <?
				if($row1[2]=="0")
				{
					session_start();                //根据当前SESSION生成随机数
					$code2 = mt_rand(0,1000000);
					$_SESSION['code2'] = $code2;      //将此随机数暂存入到session
                        
                    ?>
                    <input type="hidden" name="originator2" value="<?php echo $code2;?>">
					<br>请输入邀请码
					<br><input type="text" name="giftinvite">
                    <br><br><input type="submit" name="submitgif" style="background:#e69528" value="确定">
					<? 						
				}
				elseif($row1[4]=="0")
				{
					if($row1[6]==$invite_counter)
					{ 
                    session_start();                //根据当前SESSION生成随机数
					$code3 = mt_rand(0,1000000);
					$_SESSION['code3'] = $code3;      //将此随机数暂存入到session
                        
                    ?>
                    <input type="hidden" name="originator3" value="<?php echo $code3;?>">
						<br><br><input type="submit" name="submitgif" style="background:#e69528" value="分享福袋">		
					<?
					}
					else
					{
					?>
				
					<br>朋友已邀请了：<? echo"$row1[6]"; ?>个人
			
					<?
					}
				}
				else 
				{
					$query3="SELECT gift_name FROM $table2 WHERE gift_id =$row1[4]";
					$result3 = mysqli_query($db,$query3);
					$row3=mysqli_fetch_row($result3);
					?>
					<br><? echo"$row3[0]";
                    
                    if($row3[0]!="谢谢参与")
                        {
                    		$queryglj1 = "SELECT user_get  FROM $table1 WHERE user_tel =$tel";
							$resultglj1 = mysqli_query($db,$queryglj1);
							$rowglj1=mysqli_fetch_row($resultglj1);
                    
                    		if($rowglj1[0]==2||$rowglj1[0]==3)
                    		{
                    			?>
                    			(已使用)	
                    			<?
                    		}
                    	else
                    		{?>
                    			<br><br><input type="submit" name="submitgif" style="background:#e69528" value="使用福袋二">		
                    			<small><i>
								<br>（请在商家的指导下使用福袋，自己点击使用视为无效）
        						</i></small>
                    		<?
                    		}
                        }
                    else
                    {?>
                    	<br>快将您的邀请码发送给更多的小伙伴，一起来抢福袋，大礼等着您！
                   <? 
                    }
				}
				?>
			    </div>
        		<IMG SRC="pic\hbf.png" style="width:70%;" align="center"> 
				</form>
				</CENTER>
				<?php	
    			if($row1[3]!=0||$row1[4]!=0)
    			{?>

    		<p align="center"><IMG SRC="pic\sige2.png" style="width:20%;"></p>
    		<small>
        		<p align="left">①王力宏演唱会门票：请凭获奖页面  请于5月20日前到交通91.8听众服务中心（上城区近江家园7园1幢3号，地铁近江站附近）领取。
						<br>②鲜花：请凭获奖页面  请于5月10日前到交通91.8听众服务中心（上城区近江家园7园1幢3号，地铁近江站附近）领取。
                    	<br>③联通电话充值卡：请编辑微信文字（#联通#+联通手机号+姓名+中奖手机号）发送到交通91.8微信平台领取。
                    	<br>④市民卡：请编辑微信文字（#市民卡#+市民卡号+姓名+中奖手机号）发送到交通91.8微信平台领取。
                    	<br>⑤舌尖上的中国热门美食：请于5月11日前到交通91.8听众服务中心（上城区近江家园7园1幢3号，地铁近江站附近）领取。
                    	<br>⑥电影票：请于5月31日前到光影影城（文晖路湖墅南路口）领取。请勿自行点击“已使用”按钮，否则将被视为无效哦。
						<br>亲，您的福袋里是我们对母亲的一份爱心，谢谢您对交通91.8的支持，祝您幸福久一点吧。
				</p>
		    </small>
    			<?
   				}
			}
			
        }
	}
	else 
	{
        if(!empty($_COOKIE['user_name'])&&!empty($_COOKIE['user_tel']))
        {
            $name =  $_COOKIE['user_name'];
            $tel =  $_COOKIE['user_tel'];
        }
		
    ?>
		<CENTER>
		<form action="index.php?action=submit" method="post" >
        <IMG SRC="pic\hbh.png" style="width:70%;" align="center">
        <div style="width:70%; float:center;background:#c81900">
		<br>福袋主人姓名<br><input type="text" style="text-align:center" name="name" value="<? echo"$name";?>" >
        <br><br>金主联系方式<br><input type="text" style="text-align:center" name="tel" value="<? echo"$tel";?>">
		<br><br><input type="submit" name="submitinfo" style="background:#e69528" value="登陆">
         </div>
        <IMG SRC="pic\hbf.png" style="width:70%;" align="center">    
		</form>
		</CENTER>
	<?php
	}
	
//	$query="create table $table1".
//	"(".
//	"user_id int(11) auto_increment not null primary key,".
//	"user_name char(10) not null,".
//	"user_tel int(11) not null,".
//	"user_gift_invite int(11),".
//	"user_gift1 int(3) not null default'0',".
//	"user_gift2 int(3) not null default'0',".
//	"user_gift1_counter int(3) not null default'0',".
//	"user_gift2_counter int(3) not null default'0',".
//	"user_time datetime not null".
//	")";
//	mysqli_query($db,$query);	
//	$query="create table $table2".
//	"(".
//	"gift_id int(11) auto_increment not null primary key,".
//	"gift_name char(10) not null,".
//	"gift_counter int(11) not null default'10'".
//	")";
//	mysqli_query($db,$query);


	
?>
    <hr>
    <p align="center"><IMG SRC="pic\gift.png" style="width:100%;"></p>

    <p align="center"><IMG SRC="pic\sige.png" style="width:20%;"></p>

    <small>
        <p align="left">①必须关注交通91.8官方微信，在微信中搜索号码918918918，或者搜索公众号交通91.8；
						<br>②回复“福袋”或点击菜单“服务”——“抢福袋”，进入“抢福袋”活动页面；
						<br>③输入您的姓名和联系方式，即可获得福袋邀请码（每人可以获得系统派发的1个邀请码，并可以收到朋友赠送的1个邀请码）；
						<br>④系统派发邀请码：若您获得系统派发的1个邀请码，请将“邀请码”及“活动规则”复制发送给您最要好的9位小伙伴，并让他们根据活动规则关注91.8微信，进入“抢福袋”活动页面，输入您发给他们的邀请码；待您邀请的9位朋友一一输入福袋邀请码后，即可打开福袋，每人将获得相应的福袋奖品，当然如果运气不好，也有可能空手而归哦；
						<br>⑤收到邀请码：若您收到好友送给您福袋邀请码，请您先关注交通91.8官方微信，随后进入“抢福袋”页面，输入您获得的福袋邀请码，待福袋主人邀请的小伙伴一一输入邀请码之后，您将获得打开福袋的机会，并将获得福袋奖品，当然如果运气不好，也有可能空手而归哦；

		</p>
    </small>
    
    <p align="center"><IMG SRC="pic\sige1.png" style="width:20%;"></p>
    <small>
        <p align="left">①奖品：每天限量发放，第二天重新补充奖品，若您和您的小伙伴输入完邀请码后，当天的奖品也已经派发完毕，此时，系统会提示您“今日福袋奖品已派发完毕，您获得一次开福袋机会，请明日再来开启福袋”。看到这句话，别郁闷，交通91.8保留您的开福袋机会，第二天您可以早一点进入“抢福袋”页面，试试手气，说不定幸运就会降临哦；
						<br>②活动时间：2014年5月6日—2014年5月11日（福袋邀请码在活动时间内有效）；
						<br>③领奖：请根据您收到的奖品礼券，前往制定商户进行使用或兑换，请勿自己点击“已使用”按钮，否则视为奖品礼券已兑换，待前往商家兑换或使用完后，由商家点击“已使用”，确定兑换完毕。
						<br>活动最终解释权归杭州交通经济广播电台所有。
		</p>
    </small>
    </font>
</body>
</html>