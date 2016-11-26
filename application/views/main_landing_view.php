<script type="text/javascript" src="<?php echo base_url();?>register.js" ></script>

<script>
    <?php
        echo "var openmodel = \"" . $openmodel . "\";";
         ?>
</script>
<div style="margin-bottom : 100px;"></div>
<div class="container">
    <div class="col-sm-8 col-sm-offset-2">
        <h1> Welcome to money match</h1>
        <p>Please fill in all information to make an account</p>
        <?php
        
        if(validation_errors() != false) 
        { 
            echo '<div class="form-group alert alert-danger alert-box has-error">';
                echo'<ul>';
                    echo validation_errors('<li class="control-label">', '</li>');
                echo'</ul>';
            echo '</div>';   
        }
        
        /* form-horizontal */
        
       echo form_open('welcome/registerUser'); 
        
        ?>
        <input type="hidden"  name="register_user" value="register_user">
        <div class="form-group">
            <input type="text" name="name" class="form-control input-lg" placeholder="Name">
        </div>
        <div class="form-group">
            <input type="email" name="email" class="form-control input-lg" placeholder="Email">
        </div>
        <div class="form-group">
            <input type="text" name="phone" class="form-control input-lg" placeholder="Phone">
        </div>
        <div class="checkbox">
            <label class="radio-inline"><input type="radio" name="currencybutton" value="1" >EUR</label>
        </div>
      <div class="checkbox">
            <label class="radio-inline"><input type="radio" name="currencybutton" value="2" >USD</label>

       </div>
        <p>Are you a borrower or a investor</p>
        <div class="checkbox">
            <label class="radio-inline"><input type="radio" name="type_person" value="1" >Borrower</label>
        </div>
      <div class="checkbox">
            <label class="radio-inline"><input type="radio" name="type_person" value="2" >Investor</label>

       </div>
            <div class="form-group">
            <input type="text" name="ssn" class="form-control input-lg" placeholder="Social security number">
        </div>
        <div class="form-group">
            <input type="password" name="password" class="form-control input-lg" placeholder="Password">
        </div>
        <div class="form-group">
            <input type="password" name="password_confirm" class="form-control input-lg" placeholder="Confirm Password">
        </div>
        <div class="form-group">
            <button type='submit' class="btn btn-primary btn-lg btn-block">Register</button>
        </div>
        <p>Do you have a account already? <a href="#" data-bind="click : loginview"> login </a></p>
    </div>
</div>




<div data-bind="bootstrapShowModal: modalmoreborrowinfo" class="modal fade">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="text-center">
                <H1>We need a bit more information for you to apply for borrowing</H1>
            </div>
            <br>
            <div class="text-center">
                <div class="form-group">
                    <input type="text" name="salary" data-bind="value: borrowersalary" class="form-control input-lg" placeholder="Salary">
                </div>
                <div class="form-group">
                    <input type="email" name="job" data-bind="value: borrowerjob" class="form-control input-lg" placeholder="Job">
                </div>
                <div class="form-group">
                    <input type="text" name="housing" data-bind="value: borrowerhousing" class="form-control input-lg" placeholder="Housing? eg Permanent">
                </div>
                <div class="form-group">
                    <input type="text" name="iban" data-bind="value: iban" class="form-control input-lg" placeholder="IBAN">
                </div>
                <br>
                <p>Upload a profile picture for borrowing</p>
                <div class=" input-group-btn">
                    <span class="fileUpload btn btn-default">
                        <input type="file" multiple="multiple" />
                    </span> 
                </div>
                <br><hr><br>
                <p>Upload your passport or id card</p>
                <div class=" input-group-btn">
                    <span class="fileUpload btn btn-default">
                        <input type="file" multiple="multiple" />
                    </span> 
                </div>
                <button data-bind="click : uploadallinformation">Upload</button>
                <button data-bind="click : borrowerRequest">Save</button>
                <br>
                
            </div> 
        </div>
    </div>
</div>