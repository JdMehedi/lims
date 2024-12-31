import $ from 'jquery'
class API{

    onlyNumber(){
        $('.onlyNumber').on('keydown', function (e) {
            //period decimal
            if ((e.which >= 48 && e.which <= 57)
                //numpad decimal
                || (e.which >= 96 && e.which <= 105)
                // Allow: backspace, delete, tab, escape, enter and .
                || $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1
                // Allow: Ctrl+A
                || (e.keyCode == 65 && e.ctrlKey === true)
                // Allow: Ctrl+C
                || (e.keyCode == 67 && e.ctrlKey === true)
                // Allow: Ctrl+V
                || (e.keyCode == 86 && e.ctrlKey === true)
                // Allow: Ctrl+X
                || (e.keyCode == 88 && e.ctrlKey === true)
                // Allow: home, end, left, right
                || (e.keyCode >= 35 && e.keyCode <= 39))
            {

                var thisVal = $(this).val();
                if (thisVal.indexOf(".") != -1 && e.key == '.') {
                    return false;
                }
                $(this).removeClass('error');
                return true;
            }
            else
            {
                $(this).addClass('error');
                return false;
            }
        }).on('paste', function (e) {
            var $this = $(this);
            setTimeout(function () {
                $this.val($this.val().replace(/[^0-9]/g, ''));
            }, 4);
        }).on('keyup', function (e) {
            var $this = $(this);
            setTimeout(function () {
                $this.val($this.val().replace(/[^0-9]/g, ''));
            }, 4);
        });
    };

    isEmail() {
        $('.email').on('keydown', function (e) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if(regex.test( $(this).val()) == false){
                $(this).addClass('error');
                // return false
            }else{
                $(this).removeClass('error');
                return true;
            }
        });

    };

    stripedContent(html) {
        var tmp = document.createElement("DIV");
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || "";
    }

}

export {API}
