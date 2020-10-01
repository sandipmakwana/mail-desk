<html>
<head>
    <title>Visiting Card Request</title>
    <style type="text/css">
       table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        .btnapprove {
            width: 115px;
            height: 25px;
            background: #ff4f5e;
            color:#fff;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
        }
        .btnreject {
            width: 115px;
            height: 25px;
            background: #fff;
            color:#ff4f5e;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            border: 1px solid #ff3547;
            text-decoration: none;
        }
    </style>
</head>
<body style="background-color: #FAFAFA;font-family: Trebuchet MS;font-size: 14px;line-height: 20px;">
<div style="max-width:650px;margin:0px auto;text-align:center">
 <br><img src="http://www.mahindra.com/resources/img/logo.png" alt="Mahindra Rise" id="imgLogo" style="height:25px;" /><br><br>
    <div style="overflow: hidden; color: #605e5f; padding: 20px; background: #fff; border-radius: 2px; border: 1px solid #e8e8e8;     -webkit-box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);
    box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);text-align:left">
    
        <div style="overflow: hidden;">
            <div style="overflow: hidden;">
               
               
                Dear <b style="color: #e31837;"><?=$reqarray ['req_emp_hrmgr_name']; ?></b>,
                <br />
                <br />
                Please note that <b><?=$reqarray['req_emp_name']; ?></b> and <b><?=$reqarray['req_emp_token']; ?></b> has raised a visiting card request but his following details is not matching with the records available.
                <br />
                <br />
                Details of the request are :
                <br />
                <br />
                <table border="0" style="border: none; width: 100%;">
                    <tr><td></td>
                        <td style="color:red">
                            System
                        </td>
                        <td style="color:red">
                            Change
                        </td>
                    </tr>
                    <tr>
                        <td width="150px">
                            <b>Name</b>
                        </td>                        
                        <td>
                            <?=$reqarray['req_emp_name']; ?>
                        </td>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td width="150px">
                            <b>Token No</b>
                        </td>                        
                        <td>
                            <?=$reqarray['req_emp_token']; ?>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td width="150px">
                            <b>Designation</b>
                        </td>
                        <td>
                            <?=$reqarray['req_emp_org_desig']; ?>
                        </td>
                        <td>

                            <?php if($reqarray['req_emp_org_desig']!=$reqarray['req_emp_new_desig']) echo $reqarray['req_emp_new_desig']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="150px">
                            <b>Department/Division</b>
                        </td>                       
                        <td>
                            <?=$reqarray['req_emp_org_dept']; ?>
                        </td>
                        <td>
                            <?php if($reqarray['req_emp_org_dept']!=$reqarray['req_emp_new_dept']) echo $reqarray['req_emp_new_dept']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="150px">
                            <b>Location</b>
                        </td>
                        <td>
                            <?=$reqarray['req_emp_location_name']; ?>
                        </td>
                        <td>
                           <?php if($reqarray['req_emp_location_name']!=$reqarray['req_emp_new_location_name']) echo $reqarray['req_emp_new_location_name']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="150px">
                            <b>Address Details</b>
                        </td>
                        <td>
                            <?=$reqarray['req_emp_address']; ?>
                        </td>
                        <td>
                           <?php if($reqarray['req_emp_address']!=$reqarray['req_emp_new_address']) echo $reqarray['req_emp_new_address']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="150px">
                            <b>Phone</b>
                        </td>
                         <td>
                            <?=$reqarray['req_emp_landline']; ?>
                        </td>
                        <td>
                            <?php if($reqarray['req_emp_landline']!=$reqarray['req_emp_new_landline']) echo $reqarray['req_emp_new_landline']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="150px">
                            <b>Mobile</b>
                        </td>
                        <td>
                            <?=$reqarray['req_emp_mobile']; ?>
                        </td>
                        <td>
                            <?php if($reqarray['req_emp_new_mobile']!=$reqarray['req_emp_new_mobile']) echo $reqarray['req_emp_new_mobile']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="150px">
                            <b>E-mail ID</b>
                        </td>
                        <td>
                            <?=$reqarray['req_emp_email']; ?>
                         </td>
                         <td></td>
                    </tr>
                    <tr>
                        <td width="150px">
                            <b>Cost Centre</b>
                        </td>
                        <td>
                            <?=$reqarray['req_emp_costcenter']; ?>
                        </td>
                        <td>
                            <?php if($reqarray['req_emp_costcenter']!=$reqarray['req_emp_new_costcenter']) echo $reqarray['req_emp_new_costcenter']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="150px">
                            <b>WBS Codes</b>
                        </td>
                        <td>
                            <?=$reqarray['req_emp_wbs']; ?>
                        </td>
                        <td></td>
                    </tr>
                </table>
                <br/>
                <br/>
                <?php
                $key=base64_encode($req_id);
                $key1=base64_encode($reqarray['req_emp_hrmgr_token']);
                    $url = base_url('Request/hrApproval/').$key."/".$key1;
                     $url1 = base_url('Request/hrRejected/').$key."/".$key1;?>
                	<a href="<?= $url; ?>" class="btnapprove"> Approve</a>
                	<a href="<?= $url1; ?>" class="btnreject">Reject</a>
                <br />
                <br />
                <b>For any further assistance feel free to connect.</b>
                <br />
                <br />
                <br />
                <br />
                <b>--<br>Regards,</b>
                <br />
                <b style="color: #e31837;">MPeople Team</b>
                <br />
                Toll Free<b>*</b> <b>1-800-208-0808</b>
                <br />
                Email<b>*</b> <a href="mailto:hrsupport@mahindrampeople.com">hrsupport@mahindrampeople.com</a>
                <br />
                <br />
                <hr style="margin: 0px; padding: 0px; border: 1px solid #e41e3a; border-width: 1px 0px 0px;" />
                <br />
                <label style="color: #ff4100; font-weight: bold; font-size: 10px; line-height: 10px;">
                    This is a system generated email. Please do not reply to this email.
                </label>
                <br />
                
            </div>
        </div>
    </div>
     </div>
</body>
</html>
