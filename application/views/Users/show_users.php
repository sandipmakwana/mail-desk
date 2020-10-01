<?php
if (!isset($this->session->userdata['adm_login']['adm_username'])) {
$url = base_url()."login/adm_login";
redirect($url);
}
?>
<br><br>
<div class="datalisting  animated fadeIn">

   <h5 class="text-muted font-bold dark-grey-text"><strong>User Management</strong></h5>

<!--******************** START SESSION SETFLASH MESSAGES *****************************-->
       <?php if($this->session->flashdata('message')){?>
          <div>
            <?php echo $this->session->flashdata('message')?>
          </div>
        <?php } ?>
<!--************************* END SESSION SETFLASH MESSAGES   ************************-->


        <br>
        <div align="right">
          <a href="<?php echo site_url('routes/add_user/1'); ?>">Click to add new User</a>
        </div>
        <br>


<!--*************************  START  DISPLAY ALL THE RECODEDS *************************-->
        <table class="table-box">
            <thead class="table-box">
            <tr class="table-tr">
                <th class="table-th">Name</th>
                <th class="table-th">Email</th>
                <th class="table-th">Role</th>
                <th class="table-th">Edit</th>
                <th class="table-th">Action</th>
            </tr>
            </thead>

            <tbody>
              <?php
               // if(isset($view_data) && is_array($view_data) && count($view_data)): $i=1;
            //    foreach ($view_data as $key => $data) {
            if(isset($results) && is_array($results) && count($results)): $i=1;
            foreach($results as $data){
                ?>
                <tr <?php if($i%2==0){echo 'class="even"';}else{echo'class="odd"';}?>>
                    <td><?php echo $data->emp_firstname." ".$data->emp_lastname; ?></td>
                    <td><?php echo $data->emp_email; ?></td>
                    <td><?php echo $data->emp_role; ?></td>
                    <td class="table-td"><a href="<?php echo site_url('routes/edit_user/'. $data->emp_id.''); ?>">Edit</a></td>
                    <td class="table-td"><a href="<?php echo site_url('routes/delete_user/'. $data->emp_id.''); ?>">Delete</a> | <a href="<?php echo site_url('routes/editstatus_user/'. $data->emp_id.'/'.$data->emp_status.''); ?>"><?php echo $data->emp_status; ?></a></td>
                </tr>
                <?php
                    $i++;
                      }
                    else:
                ?>
                <tr>
                    <td colspan="7" align="center" >No Records Found..</td>
                </tr>
                <?php
                    endif;
                ?>

            </tbody>
        </table>
<!--*********************  END  DISPLAY ALL THE RECODEDS ******************************-->
 <?php if (isset($links)) { ?>
                <?php echo $links ?>
            <?php } ?></div>

