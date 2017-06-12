 <?php require_once('../base_home.php');
 
 if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}
 
$query_data = "SELECT * FROM organizers";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$row_sum = mysqli_num_rows($data);
 

$query_data1 = "SELECT * FROM organizers_c ";
$data1 = mysqli_query($link,$query_data1) or die(mysqli_error());
$row_data1 = mysqli_fetch_assoc($data1);
$row_sum1 = mysqli_num_rows($data1);

$guidance = "SELECT * FROM organizers where unit='指導單位'";
$gu_data = mysqli_query($link,$guidance) or die(mysqli_error());
$row_gu = mysqli_fetch_assoc($gu_data);
$sum_gu = mysqli_num_rows($gu_data);

$organizer = "SELECT * FROM organizers where unit='主辦單位'";
$or_data = mysqli_query($link,$organizer) or die(mysqli_error());
$row_or = mysqli_fetch_assoc($or_data);
$sum_or = mysqli_num_rows($or_data);

$co_organizers = "SELECT * FROM organizers where unit='協辦單位'";
$co_data = mysqli_query($link,$co_organizers) or die(mysqli_error());
$row_co = mysqli_fetch_assoc($co_data);
$sum_co = mysqli_num_rows($co_data);
 
$honorary = "SELECT * FROM organizers_c where position='榮譽主席' ";
$ho_data =  mysqli_query($link,$honorary) or die(mysqli_error());
$row_ho = mysqli_fetch_assoc($ho_data);
$sum_ho = mysqli_num_rows($ho_data);
 
$honorary_vice = "SELECT * FROM organizers_c where position='榮譽副主席' ";
$hov_data =  mysqli_query($link,$honorary_vice) or die(mysqli_error());
$row_hov = mysqli_fetch_assoc($hov_data);
$sum_hov = mysqli_num_rows($hov_data);
 
$general = "SELECT * FROM organizers_c where position='大會主席' ";
$ge_data =  mysqli_query($link,$general) or die(mysqli_error());
$row_ge = mysqli_fetch_assoc($ge_data);
$sum_ge = mysqli_num_rows($ge_data);

$forum = "SELECT * FROM organizers_c where position='論壇主席' ";
$fo_data =  mysqli_query($link,$forum) or die(mysqli_error());
$row_fo = mysqli_fetch_assoc($fo_data);
$sum_fo = mysqli_num_rows($fo_data);

$program = "SELECT * FROM organizers_c where position='議程主席' ";
$pr_data =  mysqli_query($link,$program) or die(mysqli_error());
$row_pr = mysqli_fetch_assoc($pr_data);
$sum_pr = mysqli_num_rows($pr_data);
 
 ?>
 
 
<html>
<div id=content class="col-sm-9"> 
<form class="navbar-form" method="POST" action="organizers.php?th=<?php echo $th ?>">

<div class="page-header">
	<h1>組織</h1>
</div>
<?php
if($button_on){ ?>
	<input type="button" class="btn btn-success" name="insert" id="insert" value="新增主辦單位" onClick="self.location='insert_o.php?th=<?php echo $th ?>'"><br><br>
<?php } ?>
<div class="panel panel-default">
	<div class="panel-heading"><h2>主辦單位</h2></div>
	<div class="panel-body">
		<?php 
		if($row_sum==0){?>
			無資料請新增
		<?php 
		}else{?>
			<div class="panel panel-default">
			<h4>指導單位/Guidance</h4>
			<div class="panel-body">
			<dl class="dl-horizontal">
				<?php do{
				if($button_on){
					if($sum_gu!=0){?> 
						<input type="button" class="btn btn-success btn-sm" value="修改" name="update" id="update" onClick="self.location='update_o.php?org_no=<?php echo $row_gu['org_no'];?>&th=<?php echo $th ?>'"> <?php 
					} 
				}
					echo $row_gu['org_name']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $row_gu['english'];?><br>
				<?php } while($row_gu = mysqli_fetch_assoc($gu_data)); ?>
			
			</div>
		</div>

			<div class="panel panel-default">
			<h4>主辦單位/Organizer</h4>
			<div class="panel-body">
				<?php do{ 
				if($button_on){
					if($sum_or!=0){?> 
						<input type="button" class="btn btn-success btn-sm"  value="修改" name="update" id="update" onClick="self.location='update_o.php?org_no=<?php echo $row_or['org_no'];?>&th=<?php echo $th ?>'"> <?php
					}
				}
					echo $row_or['org_name']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $row_or['english'];?><br>
				<?php } while($row_or = mysqli_fetch_assoc($or_data)); ?>
			</div>
		</div>
			
			<div class="panel panel-default">
			<h4>協辦單位/Co-Organizers</h4>
			<div class="panel-body">
				<?php do{
				if($button_on){
					if($sum_co!=0){?> 
						<input type="button" class="btn btn-success btn-sm"  value="修改" name="update" id="update" onClick="self.location='update_o.php?org_no=<?php echo $row_co['org_no'];?>&th=<?php echo $th ?>'"> <?php
					} 
				}
					echo $row_co['org_name']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $row_co['english'];?><br>
				<?php } while($row_co = mysqli_fetch_assoc($co_data)); ?>
			</div>
		</div>
			
		<?php	} ?>

	</div>
</div>
<?php
if($button_on){ ?>
	<input type="button" class="btn btn-primary" name="insert" id="insert" value="新增委員會" onClick="self.location='insert_c.php?th=<?php echo $th ?>'"><br><br>
<?php } ?>
	<div class="panel panel-primary">
	<div class="panel-heading"><h2>委員會</h2></div>
	<div class="panel-body">
		<?php 
		if($row_sum1==0){?>
			無資料請新增
		<?php 
		}else{?>
		<div class="panel panel-default">
			<h4>榮譽主席/Honorary Chair </h4>
			<div class="panel-body">
				<?php do{
				if($button_on){
					if($sum_ho!=0){?> 
						<input type="button" class="btn btn-primary btn-sm"  value="修改" name="update" id="update" onClick="self.location='update_c.php?c_no=<?php echo $row_ho['c_no'];?>&th=<?php echo $th ?>'"> <?php 
					} 
				}
					echo $row_ho['name']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $row_ho['identity'];?><br>
				<?php } while($row_ho = mysqli_fetch_assoc($ho_data)); ?>
			</div>
		</div>
			
		<div class="panel panel-default">
			<h4>榮譽副主席/Honorary Vice-Chairs</h4>
			<div class="panel-body">
				<?php do{ 
				if($button_on){
					if($sum_hov!=0){?> 
						<input type="button" class="btn btn-primary btn-sm"  value="修改" name="update" id="update" onClick="self.location='update_c.php?c_no=<?php echo $row_hov['c_no'];?>&th=<?php echo $th ?>'"> <?php 
					} 
				}
					echo $row_hov['name']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $row_hov['identity'];?><br>
				<?php } while($row_hov = mysqli_fetch_assoc($hov_data)); ?>
			</div>
		</div>

		<div class="panel panel-default">
			<h4>大會主席/General Chair</h4>
			<div class="panel-body">	
				<?php do{ 
				if($button_on){
					if($sum_ge!=0){?> 
						<input type="button" class="btn btn-primary btn-sm"  value="修改" name="update" id="update" onClick="self.location='update_c.php?c_no=<?php echo $row_ge['c_no'];?>&th=<?php echo $th ?>'"> <?php
					} 
				}
					echo $row_ge['name']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $row_ge['identity'];?><br>
				<?php } while($row_ge = mysqli_fetch_assoc($ge_data)); ?>
			</div>
		</div>
	
		<div class="panel panel-default">
			<h4>論壇主席/Forum Chair </h4>
			<div class="panel-body">		
				<?php do{ 
				if($button_on){
					if($sum_fo!=0){?> 
						<input type="button" class="btn btn-primary btn-sm"  value="修改" name="update" id="update" onClick="self.location='update_c.php?c_no=<?php echo $row_fo['c_no'];?>&th=<?php echo $th ?>'"> <?php 
					} 
				}
					echo $row_fo['name']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $row_fo['identity'];?><br>
				<?php } while($row_fo = mysqli_fetch_assoc($fo_data)); ?>
			
			</div>
		</div>

		<div class="panel panel-default">
			<h4>議程主席/Program Chairs </h4>
			<div class="panel-body">	
				<?php do{ 
				if($button_on){
					if($sum_pr!=0){?> 
						<input type="button" class="btn btn-primary btn-sm"  value="修改" name="update" id="update" onClick="self.location='update_c.php?c_no=<?php echo $row_pr['c_no'];?>&th=<?php echo $th ?>'"> <?php 
					} 
				}
					echo $row_pr['name']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $row_pr['identity'];?><br>
				<?php } while($row_pr = mysqli_fetch_assoc($pr_data)); ?>
			</div>
		</div>

<?php	} ?>
 	</div>
</div>
 
</div>
</html>
 
 
 
 
