let kitchen_order_quantity = document.getElementById('kitchen_order_quantity')
let bill_order_quantity = document.getElementById('bill_order_quantity')

if(pending_orders_quantity <= 0){
    if(!kitchen_order_quantity.classList.contains('d-none')){
        kitchen_order_quantity.classList.add('d-none')
    }
}
if(open_debt_bills_quantity <= 0){
    if(!bill_order_quantity.classList.contains('d-none')){
        bill_order_quantity.classList.add('d-none')
    }
}
// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

var pusher = new Pusher('958b716fb8b7e0a8d39f', {
    cluster: 'ap1'
});
var channel = pusher.subscribe('post-order');
channel.bind('post-event', function(data) {
    if(data.message != null && data.message != undefined){
        toastr.success(data.message)
        kitchen_order_quantity.textContent = pending_orders_quantity + 1
        if(kitchen_order_quantity.classList.contains('d-none')){
            kitchen_order_quantity.classList.remove('d-none')
        }
        if(['kitchen', 'bills'].includes(current_page)){
            setTimeout(function () {
                location.reload()
            }, 400)
        }
    }
});
var channel_bill = pusher.subscribe('complete-order');
channel_bill.bind('complete-event', function(data) {
    if(data.message != null && data.message != undefined){
        toastr.success(data.message)
        bill_order_quantity.textContent = open_debt_bills_quantity + 1
        if(bill_order_quantity.classList.contains('d-none')){
            bill_order_quantity.classList.remove('d-none')
        }
        if(['kitchen', 'bills'].includes(current_page)){
            setTimeout(function () {
                location.reload()
            }, 400)
        }
    }
});
var channel_reload = pusher.subscribe('reload-order');
channel_reload.bind('reload-event', function(data) {
    if(data.message != null && data.message != undefined){
        toastr.success(data.message)
        if(['kitchen', 'bills'].includes(current_page)){
            setTimeout(function () {
                location.reload()
            }, 400)
        }
    }
});
