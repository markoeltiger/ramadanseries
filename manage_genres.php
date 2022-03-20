<?php 
  $page_title="Manage Genre";
  $current_page="genre";
  $active_page="movies";

  include("includes/header.php");
	require("includes/function.php");
	require("language/language.php");
	
  if(isset($_POST['data_search']))
  {

      $searchInput=cleanInput($_POST['search_value']);

      $qry="SELECT * FROM tbl_genres WHERE tbl_genres.`genre_name` LIKE '%$searchInput%' ORDER BY tbl_genres.`genre_name`";
 
      $result=mysqli_query($mysqli,$qry); 

  }
  else
  {
   
      $tableName="tbl_genres";   
      $targetpage = "manage_genres.php"; 
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
      
      $qry="SELECT * FROM tbl_genres ORDER BY tbl_genres.`gid` DESC LIMIT $start, $limit";
 
      $result=mysqli_query($mysqli,$qry); 
  
  }

  function get_total_item($id)
  { 
    global $mysqli;   

    $sql="SELECT COUNT(*) as num FROM tbl_movies WHERE genre_id='".$id."'";
     
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
              <input class="form-control input-sm" placeholder="Search genre..." aria-controls="DataTables_Table_0" type="search" name="search_value" value="<?php if(isset($_POST['search_value'])){ echo $_POST['search_value']; }?>" required>
                    <button type="submit" name="data_search" class="btn-search"><i class="fa fa-search"></i></button>
              </form>  
            </div>
            <div class="add_btn_primary"> <a href="add_genre.php?add=yes">Add Genre</a> </div>
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
                <h2><a href="manage_movies.php?genre=<?=$row['gid']?>" style="text-shadow: 1px 1px 1px #000"><?php echo $row['genre_name'];?> <span>(<?php echo get_total_item($row['gid']);?>)</span></a></h2>
                <ul>                
                  <li><a href="add_genre.php?g_id=<?php echo $row['gid'];?>&redirect=<?=$redirectUrl?>" data-toggle="tooltip" data-tooltip="Edit"><i class="fa fa-edit"></i></a></li>               
                  <li><a href="" data-id="<?php echo $row['gid'];?>" class="btn_delete_a" data-toggle="tooltip" data-tooltip="Delete"><i class="fa fa-trash"></i></a></li>
                </ul>
              </div>
              <span><img src="images/<?php echo $row['genre_image'];?>" style="height: 150px !important;"/></span>
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
    var _table='tbl_genres';

    swal({
        title: "Are you sure you want to delete this genre?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger btn_edit",
        cancelButtonClass: "btn-warning btn_edit",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        closeOnConfirm: false,
        closeOnCancel: false,
        showLoaderOnConfirm: true
      },
      function(isConfirm) {
        if (isConfirm) {

          $.ajax({
            type:'post',
            url:'processData.php',
            dataType:'json',
            data:{id:_id,tbl_nm:_table,'action':'multi_delete'},
            success:function(res){
                console.log(res);
                if(res.status=='1'){
                  swal({
                      title: "Successfully", 
                      text: "Genre is deleted...", 
                      type: "success"
                  },function() {
                      location.reload();
                  });
                }
                else if(res.status=='-2'){
                  swal(res.message);
                }
              }
          });
        }
        else{
          swal.close();
        }
    });
  });

</script>


