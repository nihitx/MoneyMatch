<script type="text/javascript" src="<?php echo base_url();?>borrower.js" ></script>
<style>
    .topPanel{
            border: 1px solid blue;
    }
</style>
<div style="margin-bottom : 100px;"></div>
<div class="container">
    <div class="row">
        <div class="col-lg-4 col-md-3">
             <div class="panel topPanel">
                 <div class="panelHeader">
                     <div class="row">
                         <div class="col-xs-12 text-center">
                             <h4>Request seen by</h4>
                         </div>
                     </div>
                 </div>
                 <div class="panelFoot" >
                     <p class="text-center">
                          8 people
                     </p>
                 </div>
             </div>
         </div>
        <div class="col-lg-4 col-md-3">
             <div class="panel topPanel">
                 <div class="panelHeader">
                     <div class="row">
                         <div class="col-xs-12 text-center">
                             <h4>Card Valid</h4>
                         </div>
                     </div>
                 </div>
                 <div class="panelFoot" >
                     <p class="text-center">
                          Yes
                     </p>
                 </div>
             </div>
         </div>
        <div class="col-lg-3 col-md-3">
             <div class="panel topPanel">
                 <div class="panelHeader">
                     <div class="row">
                         <div class="col-xs-12 text-center">
                             <h4>Points</h4>
                         </div>
                     </div>
                 </div>
                 <div class="panelFoot" >
                     <p class="text-center">
                          5 stars
                     </p>
                 </div>
             </div>
         </div>
        
    </div>
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <h1> HELLO BORROWER!</h1>
            <p>create a borrowing request, your request will be funded if you get choosen, keep interest rate low</p>
            <button class="btn btn-primary" data-bind="click : showmodalmakerequest" >Make a request</button>
        </div>
    </div>
    <br><br>
    <div class="row">
        <div class="col-xs-6" data-bind="foreach : allBorrowerdata">
            <div class="panel panel-success">
                <div class="panel-heading">User Information <span class="pull-right btn btn-small">Edit</span></div>
                <div class="panel-body">
                    
                    <p> please note all info here will not be shown to investors</p>
                    <div>
                        <span data-bind="visible : $data.name == 'Masnad Nehith' "><img style="width : 100px; height: 100px;" src="<?php echo base_url('images/masnad.jpg'); ?>" /></span>
                        <span data-bind="visible : $data.name == 'deadpool' "><img style="width : 100px; height: 100px;" src="<?php echo base_url('images/deadpool.jpg'); ?>" /></span>
                        <span data-bind="visible : $data.name == 'joker' "><img style="width : 100px; height: 100px;" src="<?php echo base_url('images/joker.jpg'); ?>" /></span>
                        <span data-bind="visible : $data.name == 'harley' "><img style="width : 100px; height: 100px;" src="<?php echo base_url('images/harley.jpg'); ?>" /></span>
                    </div>
                    <p> Name : <span data-bind="text : $data.name"></span> </p>
                    <p> email : <span data-bind="text : $data.email"></span> </p>
                    <p> phone : <span data-bind="text : $data.phone"></span> </p>
                    <p> ssn : <span data-bind="text : $data.ssn"></span> </p>
                    <p> currency : <span data-bind="text : $data.currency"></span> </p>
                    <p> job : <span data-bind="text : $data.job"></span> </p>
                    <p> salary : <span data-bind="text : $data.salary"></span> </p>
                    <p> housing : <span data-bind="text : $data.housing"></span> </p>
                    <p> iban : <span data-bind="text : $data.iban"></span> </p>
                </div>
            </div>
        </div>
        <div class="col-xs-6">
            <div data-bind="foreach : alldata">
            
                <div class="panel panel-info">
                    <div class="panel-heading">Borrowing Card <span data-bind="visible:$data.status == 0"><span class="pull-right btn btn-small">Edit</span></span></div>
                    <div class="panel-body">
                        <p> Status : <span data-bind="if : $data.status == 0">Requesting money</span> 
                            <span data-bind="ifnot : $data.status == 0">Money Recieved</span> 
                        </p>
                        <p> Amount : <span data-bind="text : $data.Amount"></span> </p>
                        <p> Interest : <span data-bind="text : $data.Interest"></span> % </p>
                        <p> Loantime : <span data-bind="text : $data.Loantime"></span> days </p>
                    </div>
                    <div data-bind="if : $data.status== 1" class="panel-body">
                        <p> Amount to pay back : <span data-bind="text : $data.amount_got"></span> EUR </p>
                        <p> Reference : <span></span> RF234659976 </p>
                        <p> IBAN : <span data-bind="text : $data.iban"></span> </p>
                        <p> Duedate : <span data-bind="text : $data.duedate"> </span> </p>
                        <p> Did you pay already ? <input type="checkbox" data-bind="checked: $root.AgreedtoTerm" /> </p>
                    </div>
                </div>
            
            </div>
        </div>
        
    </div>
</div>





<div data-bind="bootstrapShowModal: modalmakerequest" class="modal fade">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" aria-hidden="true" type="button" data-bind="click: hidemodalmakerequest">×</button>                             
                <h4 class="modal-title"><?php echo lang("login_title");?></h4>
            </div>
            <div class="modal-body">
                <div class="text-center">
                <h1>Enter all information to make a request</h1>
            </div>
            <br>
            <div class="text-center">
                <div class="form-group">
                    <input type="text" name="amount" data-bind="value: amount" class="form-control input-lg" placeholder="Amount">
                </div>
                <div class="form-group">
                    <p>Please use numbers, we will convert it to %</p>
                    <input type="text" name="interest" data-bind="value: interest" class="form-control input-lg" placeholder="Interest">
                </div>
                <div class="form-group">
                    <p>We only offer a loan time of 30 days</p>
                    <input type="text" name="loantime" data-bind="value: loantime" class="form-control input-lg">
                </div>
                <button class="btn btn-primary" data-bind="click : makeBorrowRequest">Request</button>
                <br>
                
            </div> 
            </div>
            
        </div>
    </div>
</div>