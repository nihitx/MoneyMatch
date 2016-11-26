<div style="margin-bottom : 100px;"></div>
<div class="container">
    <div class="col-sm-8 col-sm-offset-2">
        <h1> Welcome user</h1>
        <p>login to moneymatch</p>
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
        
       echo form_open('welcome/login_user'); 
        
        ?>
        
        <div class="form-group">
            <input type="email" name="email" class="form-control input-lg" placeholder="email">
        </div>
        <div class="form-group">
            <input type="password" name="password" class="form-control input-lg" placeholder="Password">
        </div>
        <div class="form-group">
            <button type='submit' class="btn btn-primary btn-lg btn-block">Login</button>
        </div>
        <p>Do you have a account already? <a> sign up </a></p>
    </div>
</div>