var myVar;
window.onload = function () {
    myVar = setTimeout(showPage, 1400);
}
let table_id = '';

let has_items = document.getElementById('has_items')
let no_items = document.getElementById('no_items')

function showPage() {
    let loader = document.getElementById("loader")
    let myDiv = document.getElementById("myDiv")
    if(loader != undefined && loader != null){
        if(!loader.classList.contains("d-none")){
            loader.classList.add("d-none")
        }
    }
    if(myDiv != undefined && myDiv != null) {
        if (myDiv.classList.contains("d-none")) {
            myDiv.classList.remove("d-none")
        }
    }
}
order_data_content = document.getElementById('order_data_content')
let carousel_product_images = document.getElementById('carousel_product_images')
function getImages(images) {
    let all_images = images.split(' ')
    let images_content = ''
    for(let i=0; i<all_images.length; i++){
        if(i == 0){
            images_content = images_content +
                `<div class="carousel-item active">
                        <img class="d-block img-fluid" src="${all_images[i]}" alt="First slide">
                    </div>`
        }else{
            images_content = images_content +
                `<div class="carousel-item">
                            <img class="d-block img-fluid" src="${all_images[i]}" alt="First slide">
                        </div>`
        }
    }
    carousel_product_images.innerHTML = images_content
}
let order_json = {}
let order_data = []
let is_exist = false

function showHasItems(){
    if(has_items != undefined && has_items != null){
        if(has_items.classList.contains('d-none')){
            has_items.classList.remove('d-none')
        }
    }
    if(no_items != undefined && no_items != null){
        if(!no_items.classList.contains('d-none')){
            no_items.classList.add('d-none')
        }
    }
}
function hideHasItems() {
    if(has_items != undefined && has_items != null){
        if(!has_items.classList.contains('d-none')){
            has_items.classList.add('d-none')
        }
    }
    if(no_items != undefined && no_items != null){
        if(no_items.classList.contains('d-none')){
            no_items.classList.remove('d-none')
        }
    }
}

if(localStorage.getItem('order_data') != undefined && localStorage.getItem('order_data') != null){
    order_data = JSON.parse(localStorage.getItem('order_data'))
    if(order_data.length>0){
        showHasItems()
    }else{
        hideHasItems()
    }
}else{
    hideHasItems()
    order_data = []
}
if(order_data_content != undefined && order_data_content != null){
    order_data_html = setOrderHtml(order_data)
    order_data_content.innerHTML = order_data_html
}
function addToOrder(id, name, image, price, discount, discount_percent, last_price, amount) {
    is_exist = false
    order_json = {}
    if(order_data.length>0){
        for(let i = 0; i<order_data.length; i++){
            if(order_data[i].id == id){
                order_data[i].quantity = order_data[i].quantity + 1
                is_exist = true
            }
        }
        if(!is_exist){
            order_json = {'id':id, 'name':name, 'image':image, 'price':price, 'discount':discount, 'discount_percent':discount_percent, 'last_price':last_price, 'amount':amount, 'quantity':1}
        }
    }else{
        order_json = {'id':id, 'name':name, 'image':image, 'price':price, 'discount':discount, 'discount_percent':discount_percent, 'last_price':last_price, 'amount':amount, 'quantity':1}
    }
    if(Object.keys(order_json).length != 0){
        order_data.push(order_json)
    }
    if(order_data.length>0){
        showHasItems()
    }else{
        hideHasItems()
    }
    if(localStorage.getItem('order_data') != undefined && localStorage.getItem('order_data') != null){
        localStorage.setItem('order_data', JSON.stringify(order_data))
    }else{
        localStorage.removeItem('order_data')
        localStorage.setItem('order_data', JSON.stringify(order_data))
    }
    if(order_data_content != undefined && order_data_content != null){
        order_data_html = setOrderHtml(order_data)
        order_data_content.innerHTML = order_data_html
    }
}

function orderDinIn(id, name, surname) {
    if(loader != undefined && loader != null){
        if(loader.classList.contains("d-none")){
            loader.classList.remove("d-none")
        }
    }
    if(myDiv != undefined && myDiv != null){
        if(!myDiv.classList.contains("d-none")){
            myDiv.classList.add("d-none")
        }
    }
    $(document).ready(function () {
        if(order_data.length>0 && table_id != '' && id){
            try{
                $.ajax({
                    url: "/../api/din-in",
                    method: 'POST',
                    data:{
                        'order_data':order_data,
                        'table_id':table_id,
                        'user':{
                            'id':id,
                            'name':name,
                            'surname':surname,
                        }
                    },
                    success: function (data) {
                        hideHasItems()
                        if(loader != undefined && loader != null){
                            if(!loader.classList.contains("d-none")){
                                loader.classList.add("d-none")
                            }
                        }
                        if(myDiv != undefined && myDiv != null){
                            if(myDiv.classList.contains("d-none")){
                                myDiv.classList.remove("d-none")
                            }
                        }
                        if(data.status == true){
                            if(localStorage.getItem('order_data') != undefined && localStorage.getItem('order_data') != null){
                                localStorage.removeItem('order_data')
                            }
                            window.location.href = kitchen_index+'?id='+data.order_id
                        }
                    },
                    error: function (xhr, status, error) {
                        // Handle errors here
                        console.log(xhr.responseText); // Log the error response from the server
                        toastr.error('An error occurred: ' + xhr.status + ' ' + error); // Show error message
                    }
                })
            }catch (e) {
                console.log(e)
            }
        }else{
            toastr.warning(ordered_fail_text)
        }
    })
}

function getTableId(tableId) {
    table_id = tableId
}
function plusProduct(id) {
    if(order_data.length>0) {
        for (let i = 0; i < order_data.length; i++) {
            if (order_data[i].id == id) {
                order_data[i].quantity = order_data[i].quantity + 1
            }
        }
        order_data_html = setOrderHtml(order_data)
        order_data_content.innerHTML = order_data_html
        if (localStorage.getItem('order_data') != undefined && localStorage.getItem('order_data') != null) {
            localStorage.setItem('order_data', JSON.stringify(order_data))
        } else {
            localStorage.removeItem('order_data')
            localStorage.setItem('order_data', JSON.stringify(order_data))
        }
    }
}
function minusProduct(id) {
    if(order_data.length>0){
        for(let i = 0; i<order_data.length; i++){
            if(order_data[i].id == id){
                order_data[i].quantity = order_data[i].quantity - 1
                if(order_data[i].quantity == 0){
                    order_data.splice(i, 1)
                }
            }
        }
        if(order_data.length>0){
            showHasItems()
        }else{
            hideHasItems()
        }
    }else{
        hideHasItems()
    }
    order_data_html = setOrderHtml(order_data)
    order_data_content.innerHTML = order_data_html
    if(localStorage.getItem('order_data') != undefined && localStorage.getItem('order_data') != null){
        localStorage.setItem('order_data', JSON.stringify(order_data))
    }else{
        localStorage.removeItem('order_data')
        localStorage.setItem('order_data', JSON.stringify(order_data))
    }
}

function notifyTableReserved(text) {
    toastr.warning(text)
}

function setOrderHtml(order_data_){
    let total_sum = document.getElementById('total_sum')
    let service_price = document.getElementById('service_price')
    let service_price_content = document.getElementById('service_price_content')
    let all_sum = 0
    let servicePrice = 0
    order_data_html_ = ''
    let discount_html = ''
    for(let j=0; j<order_data_.length; j++){
        discount_html = ''
        all_sum = all_sum + order_data_[j].quantity*parseInt(order_data_[j].last_price.replace(/\s/g, ''), 10)
        if(order_data_[j].image){
            image_src = order_data_[j].image
        }
        if(parseInt(order_data_[j].discount.replace(/\s/g, ''), 10)>0){
            discount_html = `<div>${order_data_[j].last_price}</div><del>${order_data_[j].price}</del>`
        }else{
            discount_html = `${order_data_[j].price}`
        }
        order_data_html_ = order_data_html_ +
            `\n<tr>
                        <td>
                            <img onclick="showImage('${image_src}')" data-bs-toggle="modal" data-bs-target="#images-modal" src="${image_src}" alt="" height="44px">
                        </td>
                        <td>${order_data_[j].name+' '+order_data_[j].amount}</td>
                        <td>${order_data_[j].quantity}</td>
                        <td>
                            ${discount_html}
                        </td>
                        <td>${order_data_[j].quantity*parseInt(order_data_[j].last_price.replace(/\s/g, ''), 10)}</td>
                        <td>
                            <div class="d-flex">
                                <button class="edit_button btn" onclick="plusProduct(${order_data_[j].id})">+</button>
                                <button class="ms-2 edit_button btn" onclick="minusProduct(${order_data_[j].id})">-</button>
                            </div>
                        </td>
                    </tr>`
        if(service_percent>0){
            if(service_price_content.classList.contains('d-none')){
                service_price_content.classList.remove('d-none')
            }
        }
    }
    servicePrice = all_sum*service_percent/100
    service_price.innerHTML = servicePrice
    total_sum.innerHTML = all_sum - servicePrice
    return order_data_html_
}
let html = ''
function takeAwayFunc() {
    let take_away_content = document.getElementById('take_away_content')
    setTakeAwayHtml(order_data, take_away_content)
}
function deliveryFunc() {
    let delivery_content = document.getElementById('delivery_content')
    setTakeAwayHtml(order_data, delivery_content)
}
function setTakeAwayHtml(order_data_, content) {
    let all_sum = 0

    for(let j=0; j<order_data_.length; j++){
        all_sum = all_sum + order_data_[j].quantity*parseInt(order_data_[j].last_price.replace(/\s/g, ''), 10)
    }

    html = `<div class="d-flex justify-content-center">
                <div class="width_60_percent">
                    <h4 class="d-flex justify-content-between">
                        <span>${total_price_text}:</span>
                        <span><b>${all_sum} </b>${sum_text}</span>
                    </h4>
                </div>
            </div>`
    content.innerHTML = html
}

function takeAwayConfirm() {
    if(loader != undefined && loader != null){
        if(loader.classList.contains("d-none")){
            loader.classList.remove("d-none")
        }
    }
    if(myDiv != undefined && myDiv != null){
        if(!myDiv.classList.contains("d-none")){
            myDiv.classList.add("d-none")
        }
    }
    $(document).ready(function () {
        if(order_data.length>0){
            try{
                $.ajax({
                    url: "/../api/take-away",
                    method: 'POST',
                    data:{
                        'order_data':order_data
                    },
                    success: function (data) {
                        hideHasItems()
                        if(loader != undefined && loader != null){
                            if(!loader.classList.contains("d-none")){
                                loader.classList.add("d-none")
                            }
                        }
                        if(myDiv != undefined && myDiv != null){
                            if(myDiv.classList.contains("d-none")){
                                myDiv.classList.remove("d-none")
                            }
                        }
                        if(data.status == true){
                            if(localStorage.getItem('order_data') != undefined && localStorage.getItem('order_data') != null){
                                localStorage.removeItem('order_data')
                            }
                            window.location.href = kitchen_index+'?id='+data.order_id
                        }
                    },
                    error: function (xhr, status, error) {
                        // Handle errors here
                        console.log(xhr.responseText); // Log the error response from the server
                        toastr.error('An error occurred: ' + xhr.status + ' ' + error); // Show error message
                    }
                })
            }catch (e) {
                console.log(e)
            }
        }else{
            toastr.warning(ordered_fail_text)
        }
    })
}
