/********************************/
//  Custom Function
/********************************/
(function ($) {
    var Shop_Zita_ShopWooLib = {
        init: function (){
            this.bindEvents();
        },
         bindEvents: function (){
            var $this = this;
            $this.listGridView();
        },
         listGridView: function(){
            var wrapper = $('.zita-list-grid-switcher');
            var class_name = '';
            wrapper.find('a').on('click', function (e){
              e.preventDefault();
                var type = $(this).attr('data-type');
                switch (type){
                    case "list":
                        class_name = "zita-list-view";
                        break;
                    case "grid":
                        class_name = "zita-grid-view";
                        break;
                    default:
                        class_name = "zita-grid-view";
                        break;
                }
                if (class_name != ''){
                    $(this).closest('#shop-product-wrap').attr('class', '').addClass(class_name);
                    $(this).closest('.zita-list-grid-switcher').find('a').removeClass('selected');
                    $(this).addClass('selected');
                }
              
            });
        },

       }
   Shop_Zita_ShopWooLib.init();
})(jQuery);