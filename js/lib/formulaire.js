$(document).ready(function() {
    $(document).on("blur keyup","form.formulaire input",function(){
        var retour = true;
        if($(this).attr("required")){
            switch($(this).attr("name")){
                case "nom":
                case "prenom":
                    if(!checkAlpha($(this).val())){
                        retour = false;
                    }
                    break;
                case 'telephone':
                    if(!checkTel($(this).val())){
                        retour = false;
                    }
                    break;
                case 'email':
                    if(!checkEmail($(this).val())){
                        retour = false;
                    }
                    break;
            }
        }
        if(!retour){
            $(this).css("padding","9px");
            $(this).css("border","solid 2px red");
            $(this).siblings(".status").attr("src","img/vignette_nok.png");
            $(this).siblings(".status").css("display","inline-block");
        } else {
            $(this).css("padding","10px");
            $(this).css("border","solid 1px #CCC");
            $(this).siblings(".status").attr("src","img/vignette_ok.png");
        }
    });
});

function checkAlpha(val){
    reg = /^[\'ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑña-zA-Z- ]{2,}$/;
    if(reg.test(val)){
        return true;
    } else {
        return false;
    }
}
function checkTel(val){
    reg = /^[\+0][1-9]{1,2}([-. ]?[0-9]{2}){4,}$/;
    if(reg.test(val)){
        return true;
    } else {
        return false;
    }
}
function checkEmail(val){
    reg = /^[a-zA-Z0-9\-_]+[a-zA-Z0-9\.\-_]*@[a-zA-Z0-9\-_]+\.[a-zA-Z\.\-_]{1,}[a-zA-Z\-_]+$/;
    if(reg.test(val)){
        return true;
    } else {
        return false;
    }
}