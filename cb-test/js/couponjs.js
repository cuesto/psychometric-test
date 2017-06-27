function getdata(couponId,couponCode,couponDiscount,couponMaxLimit,couponEmail) {
	document.getElementById('coupon_code_edit').value = couponCode;
	document.getElementById('coupon_discount_edit').value = couponDiscount;
	document.getElementById('coupon_id_edit').value = couponId;
	document.getElementById('coupon_uses_limit_edit').value = couponMaxLimit;
	document.getElementById('coupon_email_edit').value = couponEmail;
}
jQuery(document).ready(function($){    
    $("#unlock").click(function(){
        var email = $("#i_email").val();
        var ref_no = $("#reference_no").val();
        var site_url = $("#url_hidden").val() + "/wp-admin/admin-ajax.php";
        jQuery.ajax({
            type:"POST",
            url: site_url,
            data: {
                    'action' : 'checkcoupon',
                    'email'  : email,
                    'ref_no' : ref_no
                },
            success:function(data){
                var obj = jQuery.parseJSON(data);
                $("#suc_s").html(obj.first);
                $("#suc").html(obj.second);
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
    });
});