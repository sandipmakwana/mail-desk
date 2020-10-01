<html>
<head>
    <title>Visiting Card Request</title>
    
</head>
<body style="background-color: #FAFAFA;font-family: Trebuchet MS;font-size: 14px;line-height: 20px;">
<div style="max-width:650px;margin:0px auto;text-align:center">
 <br><img src="http://www.mahindra.com/resources/img/logo.png" alt="Mahindra Rise" id="imgLogo" style="height:25px;" /><br><br>
    <div style="overflow: hidden; color: #605e5f; padding: 20px; background: #fff; border-radius: 2px; border: 1px solid #e8e8e8;     -webkit-box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);
    box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);text-align:left">
    
        <div style="overflow: hidden;">
            <div style="overflow: hidden;">
               
               
                Dear <b style="color: #e31837;"><?=$reqarray ['req_emp_name']; ?></b>,
                <br />
                <br />
                Thank You for the request.
                <br/>
                <br/>
                Please note that following data filled in your request are not matching with your record maintained in the system. therefore, the request has gone for further approval from your HR.
                <br />
                <br />
                Details not matching :
                <br />
                <br />
                <table border="0" style="border: none; width: 100%;">

                    <tr>
                        <td width="150px">
                            <b>Designation</b>
                        </td>
                        <td width="20px">
                            :
                        </td>
                        <td>
                            <?php if($reqarray['req_emp_org_desig']!=$reqarray['req_emp_new_desig']) echo $reqarray['req_emp_new_desig']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="150px">
                            <b>Department/Division</b>
                        </td>
                        <td width="20px">
                            :
                        </td>
                        <td>
                             <?php if($reqarray['req_emp_org_dept']!=$reqarray['req_emp_new_dept']) echo $reqarray['req_emp_new_dept']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="150px">
                            <b>Phone</b>
                        </td>
                        <td width="20px">
                            :
                        </td>
                        <td>
                            <?php if($reqarray['req_emp_landline']!=$reqarray['req_emp_new_landline']) echo $reqarray['req_emp_new_landline']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="150px">
                            <b>Mobile</b>
                        </td>
                        <td width="20px">
                            :
                        </td>
                        <td>
                            <?php if($reqarray['req_emp_new_mobile']!=$reqarray['req_emp_new_mobile']) echo $reqarray['req_emp_new_mobile']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="150px">
                            <b>Cost Centre</b>
                        </td>
                        <td width="20px">
                            :
                        </td>
                        <td>
                           <?php if($reqarray['req_emp_costcenter']!=$reqarray['req_emp_new_costcenter']) echo $reqarray['req_emp_new_costcenter']; ?>
                        </td>
                    </tr>
                </table>
                <br />
                <br />
                <b>For any further assistance feel free to connect.</b>
                <br />
                <br />
                <br />
                <br />
                <b>Regards,</b>
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
