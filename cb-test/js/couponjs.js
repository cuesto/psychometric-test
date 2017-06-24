function getdata(couponId,couponCode,couponDiscount,couponMaxLimit) {
	document.getElementById('coupon_code_edit').value = couponCode;
	document.getElementById('coupon_discount_edit').value = couponDiscount;
	document.getElementById('coupon_id_edit').value = couponId;
	document.getElementById('coupon_uses_limit_edit').value = couponMaxLimit;
}