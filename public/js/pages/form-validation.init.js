"use strict";
$(document).ready(function(){
    if($(".parsley-examples") != undefined && $(".parsley-examples") != null){
        $(".parsley-examples").parsley()
    }
}),$(function(){
        if($("#demo-form") != undefined && $("#demo-form") != null) {
            if($("#demo-form").parsley() != undefined && $("#demo-form").parsley() != null) {
                $("#demo-form").parsley().on("field:validated", function () {
                    if($(".parsley-error") != undefined && $(".parsley-error") != null) {
                        var e = 0 === $(".parsley-error").length;
                    }else{
                        var e = 0
                    }
                    if($(".alert-info") != undefined && $(".alert-info") != null) {
                        $(".alert-info").toggleClass("d-none", !e)
                    }
                    if($(".alert-warning") != undefined && $(".alert-warning") != null) {
                        $(".alert-warning").toggleClass("d-none", e)
                    }
                }).on("form:submit", function () {
                    return !1
                })
            }
        }
    });
