<?php include 'db_connect.php' ?>
<style>
   span.float-right.summary_icon {
    font-size: 3rem;
    position: absolute;
    right: 1rem;
    top: 0;
}
.imgs{
		margin: .5em;
		max-width: calc(100%);
		max-height: calc(100%);
	}
	.imgs img{
		max-width: calc(100%);
		max-height: calc(100%);
		cursor: pointer;
	}
	#imagesCarousel,#imagesCarousel .carousel-inner,#imagesCarousel .carousel-item{
		height: 60vh !important;background: black;
	}
	#imagesCarousel .carousel-item.active{
		display: flex !important;
	}
	#imagesCarousel .carousel-item-next{
		display: flex !important;
	}
	#imagesCarousel .carousel-item img{
		margin: auto;
	}
	#imagesCarousel img{
		width: auto!important;
		height: auto!important;
		max-height: calc(100%)!important;
		max-width: calc(100%)!important;
	}
</style>

<div class="containe-fluid">
	<div class="row mt-3 ml-3 mr-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <?php echo "Welcome back ". $_SESSION['login_name']."!"  ?>
                    <hr>


                    <div class="container">
                        <div class="row">
                           
                            <div class="col-lg-4 mb-2">
                                <div class="card-box bg-blue">
                                    <div class="icon">
                                        <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                    </div>
                                    <div class="inner">
                                        <h3> 13436 </h3>
                                        <p> Total Students </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="card-box bg-green">
                                     <div class="icon text-white">
                                        <i class="fa fa-certificate " aria-hidden="true"></i>
                                    </div>
                                    <div class="inner">
                                        <h3>185358 </h3>
                                        <p> Total Courses</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card-box bg-orange">
                                      <div class="icon">
                                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                                    </div>
                                    
                                    <div class="inner">
                                        <h3> 5464 </h3>
                                        <p> New Admissions </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 ">
                                <div class="card-box bg-red">
                                    <div class="icon">
                                        <i class="fa fa-users"></i>
                                    </div>
                                   
                                    <div class="inner">
                                        <h3> 723 </h3>
                                        <p> Total Users </p>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="row">
                             <div class="mt-5 mb-3">
                               <b class="display-6">Recent Payments</b> 
                            </div>
                            <table class="table table-condensed table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="">Date</th>
                                    <th class="">ID No.</th>
                                    <th class="">EF No.</th>
                                    <th class="">Name</th>
                                    <th class="">Paid Amount</th>
                                 
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                $payments = $conn->query("SELECT p.*,s.name as sname, ef.ef_no,s.id_no FROM payments p inner join student_ef_list ef on ef.id = p.ef_id inner join student s on s.id = ef.student_id order by unix_timestamp(p.date_created) desc ");
                                if($payments->num_rows > 0):
                                while($row=$payments->fetch_assoc()):
                                    $paid = $conn->query("SELECT sum(amount) as paid FROM payments where ef_id=".$row['id']);
                                    $paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid']:'';
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++ ?></td>
                                    <td>
                                        <p><?php echo date("M d,Y H:i A",strtotime($row['date_created'])) ?></p>
                                    </td>
                                    <td>
                                        <p><?php echo $row['id_no'] ?></p>
                                    </td>
                                    <td>
                                        <p><?php echo $row['ef_no'] ?></p>
                                    </td>
                                    <td>
                                        <p><?php echo ucwords($row['sname']) ?></p>
                                    </td>
                                    <td class="text-right">
                                        <p><?php echo number_format($row['amount'],2) ?></p>
                                    </td>
                                   
                                </tr>
                                <?php 
                                    endwhile; 
                                    else:
                                ?>
                                <tr>
                                    <th class="text-center" colspan="7">No data.</th>
                                </tr>
                                <?php
                                    endif;

                                ?>
                            </tbody>
<!--  Author Name: Mayuri K. 
 for any PHP, Codeignitor, Laravel OR Python work contact me at mayuri.infospace@gmail.com  
 Visit website : www.mayurik.com -->  
                        </table>
                        </div>
                    </div>
                </div>
            </div>      			
        </div>
    </div>
</div>
<!--  Author Name: Mayuri K. 
 for any PHP, Codeignitor, Laravel OR Python work contact me at mayuri.infospace@gmail.com  
 Visit website : www.mayurik.com -->  
<script>
	$('#manage-records').submit(function(e){
        e.preventDefault()
        start_load()
        $.ajax({
            url:'ajax.php?action=save_track',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                resp=JSON.parse(resp)
                if(resp.status==1){
                    alert_toast("Data successfully saved",'success')
                    setTimeout(function(){
                        location.reload()
                    },800)

                }
                
            }
        })
    })
    $('#tracking_id').on('keypress',function(e){
        if(e.which == 13){
            get_person()
        }
    })
    $('#check').on('click',function(e){
            get_person()
    })
    function get_person(){
            start_load()
        $.ajax({
                url:'ajax.php?action=get_pdetails',
                method:"POST",
                data:{tracking_id : $('#tracking_id').val()},
                success:function(resp){
                    if(resp){
                        resp = JSON.parse(resp)
                        if(resp.status == 1){
                            $('#name').html(resp.name)
                            $('#address').html(resp.address)
                            $('[name="person_id"]').val(resp.id)
                            $('#details').show()
                            end_load()

                        }else if(resp.status == 2){
                            alert_toast("Unknow tracking id.",'danger');
                            end_load();
                        }
                    }
                }
            })
    }
</script>
<!--  Author Name: Mayuri K. 
 for any PHP, Codeignitor, Laravel OR Python work contact me at mayuri.infospace@gmail.com  
 Visit website : www.mayurik.com -->  