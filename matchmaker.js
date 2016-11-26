    function AppViewModel() {
        var self = this;
        BASEURL = 'http://localhost:8888/MoneyMatch/';

        self.allBorrowerMatchData = ko.observableArray();
        self.allBorrowerdata = ko.observableArray();
        self.allinvestedto = ko.observableArray();
        self.paytrailToken = ko.observable(null);
        
        var myDate = new Date(new Date().getTime()+(30*24*60*60*1000));
            self.Duedate = ko.observable(myDate); 
            self.newAmount = ko.observable(); 
            self.brwCur = ko.observable(); 
            self.investorsIban = ko.observable();
            self.brworigid = ko.observable();
            self.borrower_owner = ko.observable(); 
            
            
       
        self.getPaytrailTokenWithAjax = function (data) {
                $.ajax({
                type: 'POST',
                url: BASEURL + '/index.php/paytrail/getPaytrailTokenWithAjax/' + data.amount,
                contentType: 'application/json; charset=utf-8'
                })
                .done(function(data) {
                    self.paytrailToken(data.paytrail_token);
                    self.payUserMoney();
               
                             
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    self.errorMessage(textStatus);
                    alert(  errorThrown + textStatus);
            })
            .always(function(data){
            });
        };
        
        self.payUserMoney = function () {
            window.location.href = "https://payment.paytrail.com/payment/load/token/" + self.paytrailToken();
        };  
  
        function allMatchViewModel(root /* root not needed */, card) {
            var self = this;
            self.name = card.name;
            self.currency = card.currency;
            self.job = card.job;
            self.salary = card.salary;
            self.housing = card.housing;
            self.validation = card.validation;
            self.points = card.points;
            self.Amount = card.Amount;
            self.Interest = card.Interest;
            self.Loantime = card.Loantime;
            self.ORIG_ID = card.ORIG_ID;
            self.Borrower_id = card.Borrower_id;
            
            self.cur = ko.computed(function() {
               if(self.currency == 1){
                   return 'EUR'; 
               }    
               return 'USD';
                  
            });
            
             
        };
        
        self.InitializeMatchView = function(){
            $.ajax({
                type: 'GET',
                url: BASEURL + '/index.php/welcome/getAllBorrowerForMatch',
                contentType: 'application/json; charset=utf-8'
            })
            .done(function(result) {
                self.allBorrowerMatchData.removeAll();
                $.each(result, function (index, results) {
                self.allBorrowerMatchData.push(new allMatchViewModel(self, results));
                });
                self.InitializeInvestedTo();
            })
            .fail(function(xhr, status, error) {
                alert(status);
            })
            .always(function(data){                 
            });
        }
        self.InitializeMatchView();
        
        
        function allInvestedToViewModel(root /* root not needed */, card) {
            var self = this;
            self.amount = card.amount;
            self.duedate = card.duedate;
            self.name = card.name;
            self.email = card.email;
            self.iban = card.iban;
           
        };
        
        self.InitializeInvestedTo = function(){
            $.ajax({
                type: 'GET',
                url: BASEURL + '/index.php/welcome/getallinvestedto',
                contentType: 'application/json; charset=utf-8'
            })
            .done(function(result) {
                self.allinvestedto.removeAll();
                $.each(result, function (index, results) {
                self.allinvestedto.push(new allInvestedToViewModel(self, results));
                });
                
            })
            .fail(function(xhr, status, error) {
                alert(status);
            })
            .always(function(data){                 
            });
        }
        
        
            
        
        self.CalculateEverythingForInvestment = function(data){
            var NewAmount = parseFloat(data.Amount);
            var InterestAmount = parseFloat((data.Interest/100) * data.Amount);
            var NewA = NewAmount + InterestAmount;
            self.newAmount(NewA); 
            self.brwCur(data.currency);
            self.brworigid(data.ORIG_ID);
            self.borrower_owner(data.Borrower_id);
            self.showmodalinvesttoborrower();
        }
        
        self.invest = function(){
            window.location.href = BASEURL + "index.php/welcome/investTheAmount/" + self.newAmount() + '/' + self.investorsIban() + '/' + self.brworigid() + '/' + self.borrower_owner();    
        }
        
        
        
        self.modalinvesttoborrower = ko.observable(false);    
        self.showmodalinvesttoborrower = function () {
            self.modalinvesttoborrower(true);
        };
        self.hidemodalinvesttoborrower = function () {
            self.modalinvesttoborrower(false);
        };
        
        
        
    }
    $(document).ready(function () {
        
        ko.applyBindings(new AppViewModel(), document.getElementById('pocketAllowance_wrapper'));
                      
        
    });
    
  // Custom binding handler for modal dialog.
    (function ($) {

        ko.bindingHandlers.bootstrapShowModal = {
            init: function (element, valueAccessor) {
                $(element).modal({ backdrop: 'static', keyboard: true, show: false });
            },
            update: function (element, valueAccessor) {
                var value = valueAccessor();
                if (ko.utils.unwrapObservable(value)) {
                    $(element).modal('show');
                    // this is to focus input field inside dialog
                    // $("input", element).focus();
                }
                else {
                    $(element).modal('hide');
                }
            }
        };
    })(jQuery);
