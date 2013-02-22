function formatCurrency(num) {
    /*num = num.toString().replace(/\$|\,/g,'');
    if(isNaN(num))
    num = "0";
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num*100+0.50000000001);
    cents = num%100;
    num = Math.floor(num/100).toString();
    if(cents<10)
    cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
        num = num.substring(0,num.length-(4*i+3))+','+
        num.substring(num.length-(4*i+3));
return (((sign)?'':'-') + '' + num + '.' + cents);*/
return num;
}

function rnd(float_number){
    formated_price = Math.round(float_number * 100)/100;
    return formated_price.toFixed(2);
}

function deletechecked()
{
    var answer = confirm("Are you sure that you want to delete it?")
    if (answer){
        document.messages.submit();
    }
    
    return false;  
}
//;
 function plusIt(id){
     var discount_type = parseInt(jQuery('#discount_type_'+id).val());
     var discount_value = parseFloat(jQuery('#discount_value_'+id).val());
     
     var quantity = parseInt(jQuery('#quantity_'+id).val());
     var price = parseFloat(jQuery('#ticket_price_'+id).html());
     var max_quantity = parseInt(jQuery('#max_quantity_'+id).val());
     var quantity_remaining = parseInt(jQuery('#quantity_remaining_'+id).val());
     
     if(max_quantity == 0){max_quantity = 9999999999;}//unlimited
     if(quantity_remaining == 0){quantity_remaining = 9999999999;}//unlimited
     
     var max = 1;
     if(max_quantity <= quantity_remaining){
         max = max_quantity;
     }else{
         max = quantity_remaining;
     }
     if(quantity < max){
        quantity = quantity + 1;
     }
     
     if(discount_type == 1){//fixed discount
         jQuery('#ticket_total_'+id).html(rnd(formatCurrency((quantity * price)-discount_value)));
     }
     if(discount_type == 2){//discount percentage
         price = price - ((price / 100) * discount_value);
         jQuery('#ticket_total_'+id).html(rnd(formatCurrency(quantity * price)));
     }
     jQuery('#quantity_'+id).val(quantity);
 }
 
 function minusIt(id){
     var discount_type = parseInt(jQuery('#discount_type_'+id).val());
     var discount_value = parseFloat(jQuery('#discount_value_'+id).val());
     
     var min_quantity = parseInt(jQuery('#min_quantity_'+id).val());
     
     if(min_quantity == 0){min_quantity = 1;}
     
     var quantity = parseInt(jQuery('#quantity_'+id).val());
     if( quantity > min_quantity ){
         quantity = quantity - 1;
     }
     var price = parseFloat(jQuery('#ticket_price_'+id).html());
     if(discount_type == 1){//fixed discount
         jQuery('#ticket_total_'+id).html(rnd(formatCurrency((quantity * price)-discount_value)));
     }
     if(discount_type == 2){//discount percentage
         price = price - ((price / 100) * discount_value);
         jQuery('#ticket_total_'+id).html(rnd(formatCurrency(quantity * price)));
     }
     jQuery('#quantity_'+id).val(quantity);
 }
 
 function stateIt(id){

     var discount_type = parseInt(jQuery('#discount_type_'+id).val());
     var discount_value = parseFloat(jQuery('#discount_value_'+id).val());
     
     var quantity = parseInt(jQuery('#quantity_'+id).val());
     var price = parseFloat(jQuery('#ticket_price_'+id).html());
     
     if(discount_type == 1){//fixed discount
         jQuery('#ticket_total_'+id).html(rnd(formatCurrency((quantity * price)-discount_value)));
     }
     if(discount_type == 2){//discount percentage
         price = price - ((price / 100) * discount_value);
         jQuery('#ticket_total_'+id).html(rnd(formatCurrency(quantity * price)));
     }
     jQuery('#quantity_'+id).val(quantity);
 }
 
function discountCode(id){
jQuery('#discount_info_'+id).html(jQuery('#coupon_applying_'+id).val());
var discount = jQuery('#discount_code_'+id).val();
jQuery.post("index.php", {action: "discount", discount_code: discount, id: id}, function(data) {
   if(data.search(/fail/i) == '-1'){
        jQuery('#discount_info_'+id).html(jQuery('#coupon_valid_'+id).val());
        var parsedData = data.split('|');
        jQuery('#discount_type_'+id).val(parsedData[0]);
        jQuery('#discount_value_'+id).val(parsedData[1]);
        stateIt(id);
   }else{
        jQuery('#discount_info_'+id).html('<font color="red">'+ jQuery('#coupon_invalid_'+id).val() +'</font>');
        jQuery('#discount_type_'+id).val(2);
        jQuery('#discount_value_'+id).val(0);
        stateIt(id);
   }
 });
}

function change_ticket_multi(id){
    current_ticket_id = id;
    resetItMulti();
}

function plusItMulti(){
    var id = current_ticket_id;
     var discount_type = parseInt(ticket[id]['discount_type']);
     var discount_value = parseFloat(ticket[id]['discount_value']);
     
     var quantity = parseInt(jQuery('#quantity').val());
     var price = parseFloat(ticket[id]['ticket_price']);
     var max_quantity = parseInt(ticket[id]['max_tickets']);
     var quantity_remaining = parseInt(ticket[id]['quantity']);
     
     if(max_quantity == 0){max_quantity = 9999999999;}//unlimited
     if(quantity_remaining == 0){quantity_remaining = 9999999999;}//unlimited
     
     var max = 1;
     if(max_quantity <= quantity_remaining){
         max = max_quantity;
     }else{
         max = quantity_remaining;
     }
     if(quantity < max){
        quantity = quantity + 1;
     }
     
     if(discount_type == 1){//fixed discount
         jQuery('#ticket_total_multi').html(rnd(formatCurrency((quantity * price)-discount_value)));
     }
     if(discount_type == 2){//discount percentage
         price = price - ((price / 100) * discount_value);
         jQuery('#ticket_total_multi').html(rnd(formatCurrency(quantity * price)));
     }
     jQuery('#quantity').val(quantity);
 }
 
 function minusItMulti(){
     var id = current_ticket_id;
     var discount_type = parseInt(ticket[id]['discount_type']);
     var discount_value = parseFloat(ticket[id]['discount_value']);
     
     var min_quantity = parseInt(ticket[id]['min_tickets']);
     
     if(min_quantity == 0){min_quantity = 1;}
     
     var quantity = parseInt(jQuery('#quantity').val());
     if( quantity > min_quantity ){
         quantity = quantity - 1;
     }
     var price = parseFloat(ticket[id]['ticket_price']);
     if(discount_type == 1){//fixed discount
         jQuery('#ticket_total_multi').html(rnd(formatCurrency((quantity * price)-discount_value)));
     }
     if(discount_type == 2){//discount percentage
         price = price - ((price / 100) * discount_value);
         jQuery('#ticket_total_multi').html(rnd(formatCurrency(quantity * price)));
     }
     jQuery('#quantity').val(quantity);
 }
 
 function stateItMulti(){
     var id = current_ticket_id;
     var discount_type = parseInt(ticket[id]['discount_type']);
     var discount_value = parseFloat(ticket[id]['discount_value']);
     
     var quantity = parseInt(jQuery('#quantity').val());
     var price = parseFloat(ticket[id]['ticket_price']);
     
     if(discount_type == 1){//fixed discount
         jQuery('#ticket_total_multi').html(rnd(formatCurrency((quantity * price)-discount_value)));
     }
     if(discount_type == 2){//discount percentage
         price = price - ((price / 100) * discount_value);
         jQuery('#ticket_total_multi').html(rnd(formatCurrency(quantity * price)));
     }
     jQuery('#quantity').val(quantity);
 }
 
 function resetItMulti(){
     var id = current_ticket_id;
     var discount_type = parseInt(ticket[id]['discount_type']);
     var discount_value = parseFloat(ticket[id]['discount_value']);
     
     var quantity = parseInt(ticket[id]['min_tickets']);
     var price = parseFloat(ticket[id]['ticket_price']);
     
     jQuery('#ticket_price_multi').html(formatCurrency(price));
     
     if(discount_type == 1){//fixed discount
         jQuery('#ticket_total_multi').html(rnd(formatCurrency((quantity * price)-discount_value)));
     }
     if(discount_type == 2){//discount percentage
         price = price - ((price / 100) * discount_value);
         jQuery('#ticket_total_multi').html(rnd(formatCurrency(quantity * price)));
     }
     jQuery('#quantity').val(quantity);
     if(parseInt(ticket[id]['coupon_available']) != 0){
        jQuery('#coupon_multi').css('display', 'block');
     }else{
        jQuery('#coupon_multi').css('display', 'none');
     }
 }
 
function discountCodeMulti(){
var id = current_ticket_id;
jQuery('#discount_info_multi').html(jQuery('#coupon_applying_multi').val());
var discount = jQuery('#discount_code_multi').val();
jQuery.post("index.php", {action: "discount", discount_code: discount, id: id}, function(data) {
   if(data != 'fail'){
        jQuery('#discount_info_multi').html(jQuery('#coupon_valid_multi').val());
        var parsedData = data.split('|');
        ticket[id]['discount_type'] = parsedData[0];
        ticket[id]['discount_value'] = parsedData[1];
        stateItMulti(id);
   }else{
        jQuery('#discount_info_multi').html('<font color="red">'+ jQuery('#coupon_invalid_multi').val() +'</font>');
        ticket[id]['discount_type'] = 2;
        ticket[id]['discount_value'] = 0;
        stateItMulti(id);
   }
 });
}

function submitTicketForm(id){
var formated_price = rnd(parseFloat(jQuery('#ticket_total_'+id).html()) / (parseInt(jQuery('#quantity_'+id).val())));
if(tickera_payment_gateway == '2checkout'){
        jQuery('#li_0_price_'+id).val(formated_price);
        //jQuery('#li_0_name_'+id).val(ticket[id]['ticket_type']);
        jQuery('#li_0_quantity_'+id).val(parseInt(jQuery('#quantity_'+id).val()));
        jQuery('#merchant_order_id_'+id).val(jQuery('#discount_code_'+id).val()+'|'+jQuery('#ticket_type_id_'+id).val());
    }else{
        //jQuery('#item_name_'+id).val(ticket[id]['ticket_type']);
        jQuery('#amount_'+id).val(formated_price);
        jQuery('#custom_'+id).val(jQuery('#discount_code_'+id).val()+'|'+jQuery('#ticket_type_id_'+id).val());
    }
    return true;
}

function submitTicketFormMulti(){
    var formated_price = rnd(parseFloat(jQuery('#ticket_total_multi').html()) / (parseInt(jQuery('#quantity').val())));
    if(tickera_payment_gateway == '2checkout'){
        jQuery('#li_0_price').val(formated_price);
        jQuery('#li_0_name').val(ticket[current_ticket_id]['ticket_type']);
        jQuery('#li_0_quantity').val(parseInt(jQuery('#quantity').val())); 
        jQuery('#merchant_order_id').val(jQuery('#discount_code_multi').val()+'|'+current_ticket_id);//jQuery('#ticket_type_id').val());
    }else{
        jQuery('#item_name').val(ticket[current_ticket_id]['ticket_type']);
        jQuery('#amount').val(formated_price);
        jQuery('#custom').val(jQuery('#discount_code_multi').val()+'|'+current_ticket_id);//jQuery('#ticket_type_id').val())
    }
    return true;
}