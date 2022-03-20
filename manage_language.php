<?php 
  $page_title="Manage Language";
  $current_page="language";
  $active_page="movies";

  include("includes/header.php");
	require("includes/function.php");
	require("language/language.php");

  if(isset($_POST['data_search']))
   {

      $qry="SELECT * FROM tbl_language                   
                  WHERE tbl_language.`language_name` like '%".addslashes(trim($_POST['search_value']))."%'
                  ORDER BY tbl_language.`language_name`";
 
     $result=mysqli_query($mysqli,$qry); 

   }
   else
   {
	
	//Get all Category 
	 
      $tableName="tbl_language";   
      $targetpage = "manage_language.php"; 
      $limit = 12; 
      
      $query = "SELECT COUNT(*) as num FROM $tableName";
      $total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query));
      $total_pages = $total_pages['num'];
      
      $stages = 3;
      $page=0;
      if(isset($_GET['page'])){
      $page = mysqli_real_escape_string($mysqli,$_GET['page']);
      }
      if($page){
        $start = ($page - 1) * $limit; 
      }else{
        $start = 0; 
        } 
      
     $qry="SELECT * FROM tbl_language
                   ORDER BY tbl_language.`id` DESC LIMIT $start, $limit";
 
     $result=mysqli_query($mysqli,$qry); 
	
    } 

	if(isset($_GET['language_id']))
	{ 
 
    $id=$_GET['language_id'];
    Delete('tbl_language','id='.$id);
      
    $res_movie=mysqli_query($mysqli,"SELECT * FROM tbl_movies WHERE `language_id`='$id'");
    $row=mysqli_fetch_assoc($res_movie);

    if($row['movie_poster']!="")
    {
      unlink('images/movies/'.$row['movie_poster']);
      unlink('images/movies/thumbs/'.$row['movie_poster']);
    }

    if($row['movie_cover']!="")
    {
      unlink('images/movies/'.$row['movie_cover']);
      unlink('images/movies/thumbs/'.$row['movie_cover']);
    }

    Delete('tbl_movies','language_id='.$id);

		$_SESSION['msg']="12";
    header( "Location:manage_language.php");
    exit;
		
	}	

  function get_total_item($id)
  { 
    global $mysqli;   

    $sql="SELECT COUNT(*) as num FROM tbl_movies WHERE language_id='".$id."'";
     
    $total_movies = mysqli_fetch_array(mysqli_query($mysqli,$sql));
    return $total_movies['num'];
  }
	 
?>
                
<div class="row">
  <div class="col-xs-12">
    <div class="card mrg_bottom">
      <div class="page_title_block">
        <div class="col-md-5 col-xs-12">
          <div class="page_title"><?=$page_title?></div>
        </div>
        <div class="col-md-7 col-xs-12">
          <div class="search_list">
            <div class="search_block">
              <form  method="post" action="">
              <input class="form-control input-sm" placeholder="Search language..." aria-controls="DataTables_Table_0" type="search" name="search_value" value="<?php if(isset($_POST['search_value'])){ echo $_POST['search_value']; }?>" required>
                    <button type="submit" name="data_search" class="btn-search"><i class="fa fa-search"></i></button>
              </form>  
            </div>
            <div class="add_btn_primary"> <a href="add_language.php?add=yes">Add Language</a> </div>
          </div>
        </div>
      </div>
       <div class="clearfix"></div>
      <div class="col-md-12 mrg-top">
        <div class="row">
          <?php 
              $i=0;
              while($row=mysqli_fetch_array($result))
              {         
          ?>
          <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="block_wallpaper add_wall_category" style="border-radius: 10px;box-shadow: 0px 2px 5px #999">           
              <div class="wall_image_title">
                <h2><a href="manage_movies.php?language=<?=$row['id']?>" style="text-shadow: 1px 1px 1px #000"><?php echo $row['language_name'];?> <span>(<?php echo get_total_item($row['id']);?>)</span></a></h2>
                <ul> 
                  <li><a href="add_language.php?id=<?php echo $row['id'];?>&redirect=<?=$redirectUrl?>" data-toggle="tooltip" data-tooltip="Edit"><i class="fa fa-edit"></i></a></li>               
                  <li><a href="" data-id="<?php echo $row['id']?>" class="btn_delete_a" data-toggle="tooltip" data-tooltip="Delete"><i class="fa fa-trash"></i></a></li>
                  
                  <?php if($row['status']!="0"){
                  ?>
                  <li><div class="row toggle_btn"><a href="javascript:void(0)" data-id="<?php echo $row['id'];?>" data-action="deactive" data-column="status" data-toggle="tooltip" data-tooltip="ENABLE"><img src="assets/images/btn_enabled.png" alt="wallpaper_1" /></a></div></li>

                  <?php }else{
                  ?>
                  <li><div class="row toggle_btn"><a href="javascript:void(0)" data-id="<?=$row['id']?>" data-action="active" data-column="status" data-toggle="tooltip" data-tooltip="DISABLE"><img src="assets/images/btn_disabled.png" alt="wallpaper_1" /></a></div></li>
              
                  <?php }?>
                </ul>
              </div>
              <span><div style="background: #<?=$row['language_background']?>;height: 150px !important" /></div></span>
            </div>
          </div>
      <?php
        $i++;
        }
    ?>   
  </div>
      </div>
      <div class="col-md-12 col-xs-12">
        <div class="pagination_item_block">
          <nav>
            <?php if(!isset($_POST["data_search"])){ include("pagination.php");}?>
          </nav>
        </div>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
</div>
        
<?php include("includes/footer.php");?> 



<script type="text/javascript">

  $(".btn_delete_a").click(function(e){
      e.preventDefault();
      var _id=$(this).data("id");
      var _table='tbl_language';

      swal({ 
        title: "Are you sure?", 
        text:'<label class="chk_confirm"><input type="checkbox" name="chk_confirm"> Do you want to remove all thing related to this language</label>', 
        showCancelButton: true, 
        showCancelButton: true,
        confirmButtonClass: "btn-danger btn_edit",
        cancelButtonClass: "btn-warning btn_edit",
        confirmButtonText: "Yes",
        cancelButtonText: "No", 
        closeOnConfirm: false, 
        closeOnCancel: false, 
        html:true , 
        showLoaderOnConfirm: true
      },
      function(isConfirm) {
          if (isConfirm) {

            var _check=false;

            if($("input[name='chk_confirm']").prop("checked") == true){
              _check=true;
            }

            $.ajax({
              type:'post',
              url:'processData.php',
              dataType:'json',
              data:{id:_id,tbl_nm:_table,'action':'multi_delete','_check': _check},
              success:function(res){
                  console.log(res);
                  if(res.status=='1'){
                    swal({
                        title: "Successfully", 
                        text: "Language is deleted...", 
                        type: "success"
                    },function() {
                        location.reload();
                    });
                  }
                }
            });

          }
          else{
            swal.close();
          }
      });
  });
  


 $(".toggle_btn a").on("click",function(e){
    e.preventDefault();
    
    var _for=$(this).data("action");
    var _id=$(this).data("id");
    var _column=$(this).data("column");
    var _table='tbl_language';

    $.ajax({
      type:'post',
      url:'processData.php',
      dataType:'json',
      data:{id:_id,for_action:_for,column:_column,table:_table,'action':'toggle_status','tbl_id':'id'},
      success:function(res){
          console.log(res);
          if(res.status=='1'){
            location.reload();
          }
        }
    });

  });
</script>   