<html>
<head>
    <title>Visiting Card Request</title>
    <style type="text/css">
       table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
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
               
                <br />
                <br />
                  <?php echo form_open_multipart('Request/hrSubmitReason','class="form-inner"','autocomplete="off"'); ?>
                    <input type="hidden" name="reqid" id="reqid" value="<?= $req_id; ?>">
                    <input type="hidden" name="hrtoken" id="hrtoken" value="<?= $hr_token; ?>">
                    <input type="hidden" name="empmail" id="empmail" value="<?=$reg->req_emp_email; ?>">
                    <input type="hidden" name="hrname" id="hrname" value="<?=$reg->req_emp_hrmgr_name; ?>">
                    <input type="hidden" name="empname" id="empname" value="<?= $reg->req_emp_name; ?>">
                    <input type="hidden" name="emptoken" id="emptoken" value="<?=$reg->req_emp_token; ?>">
                    <b>Name </b> : <?=$reg->req_emp_name; ?> 
                    <br/>
                    <br>
                    <b> Token No. </b> : <?=$reg->req_emp_token; ?>
              
                    <br/>
                     <b><br>
                     Reason:</b>
                    <textarea name="hrreason" id="hrreason" style="width: 100%;" rows="5"></textarea>
                    <br/>
                    <br/>
                    <center>
                        <button type="submit" style="background-color:#ff4f5e; color:#fff;">Submit</button>
                        <button type ="reset" style="background-color:#fff; color:#ff4f5e;" onclick="window.close()" >Close
                        </button>
                    </center>
                <?php echo form_close() ?>
                <br/>
                <br/>
                
            </div>
        </div>
    </div>
     </div>
</body>
</html>
