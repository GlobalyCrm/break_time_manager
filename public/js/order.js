let product_name = document.getElementById('product_name')
let order_quantity = document.getElementById('order_quantity')
let product_image = document.getElementById('product_image')
let cancell_order = document.getElementById('cancell_order')
let delete_order_url = document.getElementById('delete_order')
let complete_all_pending_items_url = document.getElementById('complete_all_pending_items')
let perform_order = document.getElementById('perform_order_')

let edit_product_name = document.getElementById('edit_product_name')
let edit_order_quantity = document.getElementById('edit_order_quantity')
let edit_product_image = document.getElementById('edit_product_image')
let edit_perform_order = document.getElementById('edit_perform_order_')
let edit_quantity = document.getElementById('edit_quantity')
let edit_order_function = document.getElementById('edit_order_function')
let product_item_0 = document.getElementById('product_item_0')

let accepted_by_recipient_order = document.getElementById('accepted_by_recipient_order')
let order_delivered_ = document.getElementById('order_delivered')
let ready_for_pick_up_ = document.getElementById('ready_for_pick_up')
let cancell_accepted_by_recipient_order = document.getElementById('cancell_accepted_by_recipient_order')
let perform_order_button = document.getElementById('perform_order_button')
let cancell_order_delivered_ = document.getElementById('cancell_order_delivered')
let delete_order_detail_ = document.getElementById('delete_order_detail')
let cancell_ready_for_pick_up_ = document.getElementById('cancell_ready_for_pick_up')
let user_order_number = document.getElementById('user_order_number')
let user_full_name = document.getElementById('user_full_name')
let user_birth_date = document.getElementById('user_birth_date')
let user_gender = document.getElementById('user_gender')
let user_phone_number = document.getElementById('user_phone_number')
let user_email = document.getElementById('user_email')
let user_address = document.getElementById('user_address')
let item_quantity = 0;
function completing_order(quantity, product_name_, image, image_1, url){
    product_name.innerHTML = ""
    order_quantity.innerHTML = ""
    product_image.innerHTML = ""
    if(product_name_ != ""){
        product_name.innerHTML = "<b>"+product_name_text+"</b> <span>"+product_name_+"</span>"
    }
    if(quantity != ""){
        order_quantity.innerHTML = "<b>"+order_quantity_text+"</b> <span>"+quantity+"</span>"
    }
    if(image != "" && image_1 != ""){
        product_image.innerHTML = "<img height='64px' src='"+image+"'>" +
            "<img height='64px' src='"+image_1+"'>"
    }else if(image != ""){
        console.log(image)
        product_image.innerHTML = "<img height='64px' src='"+image+"'>"
    }
    perform_order.setAttribute("action", url)
}
function editing_order(quantity, product_name_, image, image_1, url){
    edit_product_name.innerHTML = ""
    edit_order_quantity.innerHTML = ""
    edit_product_image.innerHTML = ""
    item_quantity = parseInt(quantity)
    if(product_name_ != ""){
        edit_product_name.innerHTML = "<b>"+product_name_text+"</b> <span>"+product_name_+"</span>"
    }
    if(item_quantity >0){
        edit_order_quantity.innerHTML = "<b>"+order_quantity_text+"</b> <span>"+item_quantity+"</span>"
        edit_quantity.value = item_quantity
        if(!product_item_0.classList.contains('d-none')){
            product_item_0.classList.add('d-none')
        }
        edit_order_function.innerHTML = `<button class='btn edit_button' onclick='plusItem()'>+</button>
        <button class='btn delete_button' onclick='minusItem()'>-</button>`
    }else{
        if(product_item_0.classList.contains('d-none')){
            product_item_0.classList.remove('d-none')
        }
    }
    if(image != "" && image_1 != ""){
        edit_product_image.innerHTML = "<img height='64px' src='"+image+"'>" +
            "<img height='64px' src='"+image_1+"'>"
    }else if(image != ""){
        edit_product_image.innerHTML = "<img height='64px' src='"+image+"'>"
    }
    edit_perform_order.setAttribute("action", url)
}
function plusItem() {
    if(item_quantity>=0){
        item_quantity = item_quantity + 1
        edit_quantity.value = item_quantity
        if(item_quantity == 0){
            if(product_item_0.classList.contains('d-none')){
                product_item_0.classList.remove('d-none')
            }
        }else{
            if(!product_item_0.classList.contains('d-none')){
                product_item_0.classList.add('d-none')
            }
        }
        edit_order_quantity.innerHTML = "<b>"+order_quantity_text+"</b> <span>"+item_quantity+"</span>"
    }
}
function minusItem() {
    if(item_quantity>0){
        item_quantity = item_quantity - 1
        if(item_quantity <= 0){
            if(product_item_0.classList.contains('d-none')){
                product_item_0.classList.remove('d-none')
            }
        }
        edit_quantity.value = item_quantity
        edit_order_quantity.innerHTML = "<b>"+order_quantity_text+"</b> <span>"+item_quantity+"</span>"
    }else if(item_quantity <= 0){
        if(product_item_0.classList.contains('d-none')){
            product_item_0.classList.remove('d-none')
        }
    }
}
function accepted_by_recipient(url){
    if(url){
        accepted_by_recipient_order.setAttribute("action", url)
    }else{
        accepted_by_recipient_order.setAttribute("action", "")
    }
}
function order_delivered(url){
    if(url){
        order_delivered_.setAttribute("action", url)
    }else{
        order_delivered_.setAttribute("action", "")
    }
}
function ready_for_pick_up(url){
    if(url){
        ready_for_pick_up_.setAttribute("action", url)
    }else{
        ready_for_pick_up_.setAttribute("action", "")
    }
}
function cancell_accepted_by_recipient(url){
    cancell_accepted_by_recipient_order.setAttribute("action", url)
}
function cancell_ready_for_pick_up(url){
    cancell_ready_for_pick_up_.setAttribute("action", url)
}
function cancell_order_delivered(url){
    cancell_order_delivered_.setAttribute("action", url)
}
function delete_order_detail(url){
    delete_order_detail_.setAttribute("action", url)
}
function getOrderData(user_order_number_, user_full_name_, user_birth_date_, user_gender_, user_phone_number_, user_email_, user_address_){
    user_order_number.innerHTML = ""
    user_full_name.innerHTML = ""
    user_birth_date.innerHTML = ""
    user_gender.innerHTML = ""
    user_phone_number.innerHTML = ""
    user_email.innerHTML = ""
    user_address.innerHTML = ""
    if(user_order_number_ != ""){
        user_order_number.innerHTML = user_order_number_
    }
    if(user_full_name_ != ""){
        user_full_name.innerHTML = user_full_name_
    }
    if(user_birth_date_ != ""){
        user_birth_date.innerHTML = user_birth_date_
    }
    if(user_gender_ != ""){
        user_gender.innerHTML = user_gender_
    }
    if(user_phone_number_ != ""){
        user_phone_number.innerHTML = user_phone_number_
    }
    if(user_email_ != ""){
        user_email.innerHTML = user_email_
    }
    if(user_address_ != ""){
        user_address.innerHTML = user_address_
    }
}
function cancelling_order(url){
    cancell_order.setAttribute("action", url)
}

function delete_order(url){
    delete_order_url.setAttribute("action", url)
}

function complete_all_pending_order_detail(url){
    complete_all_pending_items_url.setAttribute("action", url)
}


