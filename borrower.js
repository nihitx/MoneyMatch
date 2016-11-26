    function AppViewModel() {
        var self = this;
        BASEURL = 'http://localhost:8888/MoneyMatch/';
        
        
        self.amount = ko.observable();
        self.loantime = ko.observable(30);
        self.interest = ko.observable();
        
        self.AgreedtoTerm = ko.observable(false);
        
        self.alldata = ko.observableArray();
        self.allBorrowerdata = ko.observableArray();
        
        self.makeBorrowRequest = function(){
            window.location.href = BASEURL + "index.php/welcome/saveborrowrequest/" + self.amount() + '/' + self.interest() + '/' + self.loantime();    
        }
        
        function allBorrowerInfo(root /* root not needed */, card) {
            var self = this;
            self.Amount = card.Amount;
            self.Interest = card.Interest;
            self.Loantime = card.Loantime;
            self.status = card.status;
            self.iban = card.iban;
            self.amount_got = card.amount_got;
            self.duedate = card.duedate;

            
             
        };
        
        self.InitializeBorrowerView = function(){
            $.ajax({
                type: 'POST',
                url: BASEURL + '/index.php/welcome/getBorrowerRequest',
                contentType: 'application/json; charset=utf-8',
                dataType :'json'
            })
            .done(function(result) {
                self.alldata.removeAll();
                $.each(result, function (index, results) {
                self.alldata.push(new allBorrowerInfo(self, results));
                });
                self.getBorrowerInformation();
            })
            .fail(function(xhr, status, error) {
                alert(status);
            })
            .always(function(data){                 
            });
        }
        self.InitializeBorrowerView();
        
        
        
        function allBorrowerDataViewModel(root /* root not needed */, card) {
            var self = this;
            self.name = card.name;
            self.email = card.email;
            self.phone = card.phone;
            self.ssn = card.ssn;
            self.currency = card.currencyprop;
            self.job = card.job;
            self.salary = card.salary;
            self.housing = card.housing;
            self.iban = card.iban;
            
            
            self.currencyprop = ko.computed(function() {
               if(self.currency ==1 ){
                   return 'EUR'; 
               }    
               return 'USD';
                  
            });
            
             
        };
        
        self.getBorrowerInformation = function(){
            $.ajax({
                type: 'POST',
                url: BASEURL + '/index.php/welcome/getBorrowerInformation',
                contentType: 'application/json; charset=utf-8',
                dataType :'json'
            })
            .done(function(result) {
                self.allBorrowerdata.removeAll();
                $.each(result, function (index, results) {
                self.allBorrowerdata.push(new allBorrowerDataViewModel(self, results));
                });
                
                
            })
            .fail(function(xhr, status, error) {
                alert(status);
            })
            .always(function(data){                 
            });
        };
        
        
        
        self.modalmakerequest = ko.observable(false);    
        self.showmodalmakerequest = function () {
            self.modalmakerequest(true);
        };
        self.hidemodalmakerequest = function () {
            self.modalmakerequest(false);
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
