    function AppViewModel() {
        var self = this;
        BASEURL = 'http://localhost:8888/MoneyMatch/';
        
        
        self.openmodel = ko.observable(openmodel);
        
        self.borrowersalary = ko.observable();
        self.borrowerjob = ko.observable();
        self.borrowerhousing = ko.observable();
        self.iban = ko.observable();
        
        
        self.modalmoreborrowinfo = ko.observable(false);    
        self.showmodalmoreborrowinfo = function () {
            self.modalmoreborrowinfo(true);
        };
        self.hidemodalmoreborrowinfo = function () {
            self.modalmoreborrowinfo(false);
        };
        
        
        self.InitializeRegister = function(){
            if(self.openmodel() == 1){
                self.showmodalmoreborrowinfo();
            }
        }
        self.InitializeRegister();
        
        self.uploadallinformation = function(){
            var files = $('.fileUpload input').get(0).files;
            if (files.length > 0) {
                var data = new FormData();
                for (i = 0; i < files.length; i++) {
                    data.append('file' + i, files[i]);
                }
            }
            $.ajax({
                type: 'POST',
                url: BASEURL + 'index.php/welcome/uploadProfilePicture/',
                contentType: false,       // The content type used when sending data to the server.
                cache: false,             // To unable request pages to be cached
                processData:false, 
                data : data
            })
            .done(function(done) {
                alert("done");
            })
            .fail(function(xhr, status, error) {
                alert(error);
            })
            .always(function(data){                 
            });
        }
        
        self.borrowerRequest = function(){
            window.location.href = BASEURL + "index.php/welcome/saveuser_data/" + self.borrowersalary() + '/' + self.borrowerjob() + '/' + self.borrowerhousing() + '/' + self.iban();
        }
        
        self.loginview = function(){
            window.location.href = BASEURL + "index.php/welcome/login/";
        }
        
        
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
