    <div class="container">
        <div class="row">           
            <div class="col-sm-12">
                <!-- Default form login -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="pink-text clearfix"> Request Details 
                            <span class="float-right">
                              <a  href="<?= base_url('Vendorreq/index') ?>"  class="btn btn-sm btn-outline-danger m-0" name="" />Back to List</a>
                            </span>
                            </h4><hr/>
                           
                                <input type="hidden" name="req_id" id="req_id" value="<?php if(isset($reg->req_id)) echo $reg->req_id; ?>">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>Display Name on V-Card</label>
                                        <input type="text" id="empname" class="form-control mb-4" readonly name="empname" value="<?php if(isset($reg->req_emp_name)) echo $reg->req_emp_name; ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Designation</label>
                                        <input type="text" id="designation" class="form-control mb-4" placeholder="" name="designation" value="<?php if(isset($reg->req_emp_new_desig)) echo $reg->req_emp_new_desig; ?>" readonly>
                                    </div>                          
                                    <div class="col-sm-4">
                                        <label>Department/Division</label>
                                        <input type="text" id="department" class="form-control mb-4" placeholder="" name="department" value="<?php if(isset($reg->req_emp_new_dept)) echo $reg->req_emp_new_dept; ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label>Business Unit</label>
                                                <input type="text" id="business_unit" class="form-control mb-4" placeholder="" name="business_unit" value="<?php if(isset($reg->req_emp_new_buss_unit)) echo $reg->req_emp_new_buss_unit; ?>" readonly>
                                            </div>                     
                                            <div class="col-sm-6">
                                                <label>Location</label>
                                                <input type="text" id="location" class="form-control mb-4" placeholder="" name="location" value="<?php if(isset($reg->req_emp_new_location_name)) echo $reg->req_emp_new_location_name; ?>" readonly>
                                            </div>
                                            <div class="col-sm-6">
                                                <label>ISD/STD Code</label>
                                                <input type="text" id="stdcode" class="form-control mb-4" placeholder="" name="stdcode" value="<?php if(isset($reg->req_emp_stdcode)) echo $reg->req_emp_stdcode; ?>" readonly>
                                            </div>
                                             <div class="col-sm-6">
                                                <label>Telephone</label>
                                                <input type="text" id="telephone" class="form-control mb-4" placeholder="" name="telephone" value="<?php if(isset($reg->req_emp_new_landline)) echo $reg->req_emp_new_landline; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Office Address</label>
                                        <textarea class="form-control mb-4" name="officeaddress" id="officeaddress" readonly ><?php if(isset($reg->req_emp_new_address)) echo $reg->req_emp_new_address; ?></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>Fax</label>
                                        <input type="text" id="fax" class="form-control mb-4" placeholder="" name="fax" value="<?php if(isset($reg->req_emp_fax)) echo $reg->req_emp_fax; ?>" readonly>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Mobile</label>
                                        <input type="text" id="mobile" class="form-control mb-4" placeholder="" name="mobile" value="<?php if(isset($reg->req_emp_new_mobile)) echo $reg->req_emp_new_mobile; ?>" readonly>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Official Email Address</label>
                                        <input type="text" id="emailaddress" class="form-control mb-4" placeholder="" name="emailaddress" value="<?php if(isset($reg->req_emp_email)) echo $reg->req_emp_email; ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>Cost Center</label>
                                        <input type="text" id="costcenter" class="form-control mb-4" placeholder="" name="costcenter" value="<?php if(isset($reg->req_emp_new_costcenter)) echo $reg->req_emp_new_costcenter; ?>" readonly>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>WBS Element</label>
                                        <input type="text" id="wbselement" class="form-control mb-4" placeholder="" name="wbselement" value="<?php if(isset($reg->req_emp_wbs)) echo $reg->req_emp_wbs; ?>" readonly>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Vendor</label>
                                        <input type="text" id="vendor" class="form-control mb-4" placeholder="" name="vendor" value="<?php if(isset($reg->vendor_cmpname)) echo $reg->vendor_cmpname; ?>" readonly>
                                    </div>
                                </div>    
                            </div>
                    </div>
                </div>
            </div>
        </div>       
    </div>
</div>
