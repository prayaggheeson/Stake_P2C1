 <?php
if($this->conn->plan_setting('pin_section')==1){

?>
<style>
	  .loading {
         
          opacity: 0.7;
          background-color: #fff;
          z-index: 99;
          text-align: center;
        }
        
        .loading-image {
          
          background-image:url("<?= base_url('images/loader/ajax-loader.gif');?>");
          z-index: 100;
        }
	  </style>
	  
	  
          <div class="card">
            <div class="card-header">
                <span id="epin_title">Epin Report</span> 
                <div class="card-action">
                 <div class="dropdown">
                 <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
                  <i class="icon-options"></i>
                 </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item change_epin" href="javascript:void();" data-val="all">All</a>
                        <a class="dropdown-item change_epin" href="javascript:void();" data-val="today">Today</a>
                        <a class="dropdown-item change_epin" href="javascript:void();" data-val="24hour">24 Hours</a>
                        <a class="dropdown-item change_epin" href="javascript:void();" data-val="lastweek">Last Week</a>
                        <a class="dropdown-item change_epin" href="javascript:void();" data-val="lastmonth">Last Month</a>
                        <a class="dropdown-item change_epin" href="javascript:void();" data-val="lastyear">Last Year</a>
                   </div>
                  </div>
                 </div>
                </div>
                <div class="card-body">
                     <div id="loader_section" class="">
                         <div id="loader_img_section" class="">
                             <div id="epindata" class="" >
                                <?php
                                $admn_path=$this->conn->company_info('admin_directory');
                                $dta['type']='all';
                                $this->load->view($admn_path.'/pages/dashboard/epin_table',$dta);
                                ?>
                            </div>
                        </div>
                    </div>
                  <!--<canvas id="dashboard-chart-1"></canvas>-->
                </div>
          </div>
        <script>
          $('.change_epin').click(function(){
              
              //$("#loader_section").addClass("loading");
              //$("#loader_img_section").addClass("loading-image");
              
              $("#epindata").html('<div class="loading"><img class="loading-image" src="<?= base_url('images/loader/ajax-loader.gif');?>"> </div>');
              var val =$(this).attr('data-val');
               $.ajax({
                type: "post",
                url: "<?= $admin_path.'dashboard/epin';?>",
                data: {type:val},          
                success: function (response) {
                   $('#epindata').html(response);
                   //$("#loader_section").removeClass("loading");
                  //$("#loader_img_section").removeClass("loading-image");
                }
              });
              
          });
        </script>
        <?php
        
}
        ?>