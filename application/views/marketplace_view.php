<script type="text/javascript" src="<?php echo base_url();?>matchmaker.js" ></script>
<style>
    .makepLookbetta p{
        font-size: 20px;
        border : 0.5px solid #66cccc;
    }
</style>
<div style="margin-bottom : 100px;"></div>
<div class="container">
    
    <div>
        <div class="row">
            <div class="col-xs-6">
                <h1>All borrower you invested too</h1>
            </div>
            <div class="col-xs-6">
                <h1>MATCH MAKER! </h1>
                <p>FIND A PERFECT PERSON SO YOU CAN LEND HIM/HER MONEY</p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6" data-bind="foreach : allinvestedto">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3>Invested too : <span data-bind="text : $data.name"></span></h3> 
                    </div>
                    <div class="panel-body">
                        <p>Repayment  : <span data-bind="text : $data.amount"></span> EUR</p>
                        <p>Duedate : <span data-bind="text : $data.duedate"></span></p>
                        
                    </div>
                    <div class="panel-body">
                        <p>We give the name and email so you can talk to them if they received the payment or not</p>
                        <p>Name  : <span data-bind="text : $data.amount"></span> EUR</p>
                        <p>Email : <span data-bind="text : $data.email"></span></p>
                        <p>iban : <span data-bind="text : $data.iban"></span></p>
                        <p>Ref : <span>RF642215546</span></p>
                    </div>
                    <div class="panel-body">
                        <p>Did the user pay back? Give them points!</p>
                        <div class="checkbox">
                            <label class="radio-inline"><input type="radio" name="type_person" value="1" >5 Star Borrower</label>
                        </div>
                      <div class="checkbox">
                            <label class="radio-inline"><input type="radio" name="type_person" value="1" >4 Star Borrower</label>
                       </div>
                        <div class="checkbox">
                            <label class="radio-inline"><input type="radio" name="type_person" value="1" >3 Star Borrower</label>
                       </div>
                        <div class="checkbox">
                            <label class="radio-inline"><input type="radio" name="type_person" value="1" >2 Star Borrower</label>
                       </div>
                        <div class="checkbox">
                            <label class="radio-inline"><input type="radio" name="type_person" value="1" >1 Star Borrower</label>
                       </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-6" data-bind="foreach : allBorrowerMatchData">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3> Borrower : <span data-bind="text : $data.name"></span> </h3>
                    </div>
                    <div class="panel-body">
                        <span data-bind="visible : $data.name == 'Masnad Nehith' "><img style="width : 300px; height: 300px;" src="<?php echo base_url('images/masnad.jpg'); ?>" /></span>
                        <span data-bind="visible : $data.name == 'deadpool' "><img style="width : 300px; height: 300px;" src="<?php echo base_url('images/deadpool.jpg'); ?>" /></span>
                        <span data-bind="visible : $data.name == 'joker' "><img style="width : 300px; height: 300px;" src="<?php echo base_url('images/joker.jpg'); ?>" /></span>
                        <span data-bind="visible : $data.name == 'harley' "><img style="width : 300px; height: 300px;" src="<?php echo base_url('images/harley.jpg'); ?>" /></span>
                        <div class="text-center makepLookbetta">
                            <p>Currency : <span data-bind="text : $data.cur"></span></p>
                            <p>Amount : <span data-bind="text : $data.Amount"></span></p>
                            <p>Interest : <span data-bind="text : $data.Interest"></span></p>
                            <p>loan time : <span data-bind="text : $data.Loantime"></span></p>
                            <p>housing  : <span data-bind="text : $data.housing"></span></p>
                            <p>Job  : <span data-bind="text : $data.job"></span></p>
                            <p>Customer Review  : 
                                <span data-bind="if : $data.points == 0">
                                    <span class="glyphicon glyphicon-star-empty"></span> <span class="glyphicon glyphicon-star-empty"></span> <span class="glyphicon glyphicon-star-empty"></span> <span class="glyphicon glyphicon-star-empty"></span> <span class="glyphicon glyphicon-star-empty"></span>
                                </span>
                            </p>
                            <p>Valid Identification  : 
                                <span data-bind="if : $data.validation == 0">
                                    <span class="glyphicon glyphicon-ok"></span>
                                </span>
                            </p>
                            <div class="text-center">
                                <button class="btn btn-primary" data-bind="click : $root.CalculateEverythingForInvestment"> Invest</button>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>


<div data-bind="bootstrapShowModal: modalinvesttoborrower" class="modal fade">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" aria-hidden="true" type="button" data-bind="click: hidemodalinvesttoborrower">×</button>                             
            </div>
            <div class="modal-body">
                <div class="text-center">
                <h1>Found your match?</h1>
                <p> Read the details below and complete this loan </p>
            </div>
            <br>
            <div class="">
                <div class="form-group">
                    <p>Borrowers currency : 
                        <span data-bind="if : brwCur == 1">
                            EURO
                        </span>
                        <span data-bind="ifnot : brwCur == 1">
                            USD
                        </span>
                    
                    </p>
                </div>
                <div class="form-group">
                    <p>Due date of this loan <span data-bind="text : Duedate"></span></p>
                </div>
                <div class="form-group">
                    <p>Amount you will receive back <span data-bind="text : newAmount"></span> EURO</p>
                </div>
                <div class="form-group">
                    <p>Please enter your iban so the user will be able to pay you back the amount</p>
                    <p>IBAN </p>
                    <input data-bind="value : investorsIban"/>
                </div>
                <div class="form-group">
                    <p>User will receive a invoice near the end of the month to pay back</p>
                </div>
                
            </div> 
            <button style="width : 100%" class="btn btn-primary" data-bind="click : invest">Invest</button>
            </div>
            
        </div>
    </div>
</div>