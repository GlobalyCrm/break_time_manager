var myVar;
window.onload = function () {
    myVar = setTimeout(showPage, 1400);
}
let table_id = '';

let has_items = document.getElementById('has_items')
let no_items = document.getElementById('no_items')

let reserveTime = document.getElementById('datetime-datepicker')
let reserve_submit_time = document.getElementById('reserve_submit_time')
let open_reserve_price_input_ = document.getElementById('open_reserve_price_input')
let debt_two_client_id_ = document.getElementById('debt_two_client_id')

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
reserve_data_content = document.getElementById('reserve_data_content')
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
let reserve_data = []
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

if(localStorage.getItem('reserve_data') != undefined && localStorage.getItem('reserve_data') != null){
    reserve_data = JSON.parse(localStorage.getItem('reserve_data'))
    if(reserve_data.length>0){
        showHasItems()
    }else{
        hideHasItems()
    }
}else{
    hideHasItems()
    reserve_data = []
}
if(reserve_data_content != undefined && reserve_data_content != null){
    reserve_data_html = setOrderHtml(reserve_data)
    reserve_data_content.innerHTML = reserve_data_html
}
function addToOrder(id, name, image, price, discount, discount_percent, last_price, amount) {
    is_exist = false
    order_json = {}
    if(reserve_data.length>0){
        for(let i = 0; i<reserve_data.length; i++){
            if(reserve_data[i].id == id){
                reserve_data[i].quantity = reserve_data[i].quantity + 1
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
        reserve_data.push(order_json)
    }
    if(reserve_data.length>0){
        showHasItems()
    }else{
        hideHasItems()
    }
    if(localStorage.getItem('reserve_data') != undefined && localStorage.getItem('reserve_data') != null){
        localStorage.setItem('reserve_data', JSON.stringify(reserve_data))
    }else{
        localStorage.removeItem('reserve_data')
        localStorage.setItem('reserve_data', JSON.stringify(reserve_data))
    }
    if(reserve_data_content != undefined && reserve_data_content != null){
        reserve_data_html = setOrderHtml(reserve_data)
        reserve_data_content.innerHTML = reserve_data_html
    }
}


function orderReserve(id, name, surname) {
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
        if(reserve_data.length>0 && table_id != '' && id && reserveTime.value != ''){
            try{
                $.ajax({
                    url: "/../api/reserve",
                    method: 'POST',
                    data:{
                        'reserve_data':reserve_data,
                        'table_id':table_id,
                        'reserve_time':reserveTime.value,
                        'client_id':debt_two_client_id_.value,
                        'user':{
                            'id':id,
                            'name':name,
                            'surname':surname,
                        }
                    },
                    success: function (data) {
                        console.log(data)
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
                            if(localStorage.getItem('reserve_data') != undefined && localStorage.getItem('reserve_data') != null){
                                localStorage.removeItem('reserve_data')
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
    if(reserve_data.length>0) {
        for (let i = 0; i < reserve_data.length; i++) {
            if (reserve_data[i].id == id) {
                reserve_data[i].quantity = reserve_data[i].quantity + 1
            }
        }
        reserve_data_html = setOrderHtml(reserve_data)
        reserve_data_content.innerHTML = reserve_data_html
        if (localStorage.getItem('reserve_data') != undefined && localStorage.getItem('reserve_data') != null) {
            localStorage.setItem('reserve_data', JSON.stringify(reserve_data))
        } else {
            localStorage.removeItem('reserve_data')
            localStorage.setItem('reserve_data', JSON.stringify(reserve_data))
        }
    }
}
function minusProduct(id) {
    if(reserve_data.length>0){
        for(let i = 0; i<reserve_data.length; i++){
            if(reserve_data[i].id == id){
                reserve_data[i].quantity = reserve_data[i].quantity - 1
                if(reserve_data[i].quantity == 0){
                    reserve_data.splice(i, 1)
                }
            }
        }
        if(reserve_data.length>0){
            showHasItems()
        }else{
            hideHasItems()
        }
    }else{
        hideHasItems()
    }
    reserve_data_html = setOrderHtml(reserve_data)
    reserve_data_content.innerHTML = reserve_data_html
    if(localStorage.getItem('reserve_data') != undefined && localStorage.getItem('reserve_data') != null){
        localStorage.setItem('reserve_data', JSON.stringify(reserve_data))
    }else{
        localStorage.removeItem('reserve_data')
        localStorage.setItem('reserve_data', JSON.stringify(reserve_data))
    }
}

function notifyTableReserved(text) {
    toastr.warning(text)
}

function setOrderHtml(reserve_data_){
    let total_sum = document.getElementById('total_sum')
    let all_sum = 0
    reserve_data_html_ = ''
    let discount_html = ''
    for(let j=0; j<reserve_data_.length; j++){
        discount_html = ''
        all_sum = all_sum + reserve_data_[j].quantity*parseInt(reserve_data_[j].last_price.replace(/\s/g, ''), 10)
        if(reserve_data_[j].image){
            image_src = reserve_data_[j].image
        }
        if(parseInt(reserve_data_[j].discount.replace(/\s/g, ''), 10)>0){
            discount_html = `<div>${reserve_data_[j].last_price}</div><del>${reserve_data_[j].price}</del>`
        }else{
            discount_html = `${reserve_data_[j].price}`
        }
        reserve_data_html_ = reserve_data_html_ +
            `\n<tr>
                        <td>
                            <img onclick="showImage('${image_src}')" data-bs-toggle="modal" data-bs-target="#images-modal" src="${image_src}" alt="" height="44px">
                        </td>
                        <td>${reserve_data_[j].name+' '+reserve_data_[j].amount}</td>
                        <td>${reserve_data_[j].quantity}</td>
                        <td>
                            ${discount_html}
                        </td>
                        <td>${reserve_data_[j].quantity*parseInt(reserve_data_[j].last_price.replace(/\s/g, ''), 10)}</td>
                        <td>
                            <div class="d-flex">
                                <button class="edit_button btn" onclick="plusProduct(${reserve_data_[j].id})">+</button>
                                <button class="ms-2 edit_button btn" onclick="minusProduct(${reserve_data_[j].id})">-</button>
                            </div>
                        </td>
                    </tr>`
    }
    total_sum.innerHTML = all_sum
    return reserve_data_html_
}
let html = ''
function reserveFunc() {
    let reserve_content = document.getElementById('reserve_content')
    setReserveHtml(reserve_data, reserve_content)
}
function setReserveHtml(reserve_data_, content) {
    let all_sum = 0

    for(let j=0; j<reserve_data_.length; j++){
        all_sum = all_sum + reserve_data_[j].quantity*parseInt(reserve_data_[j].last_price.replace(/\s/g, ''), 10)
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
        if(reserve_data.length>0){
            try{
                $.ajax({
                    url: "/../api/take-away",
                    method: 'POST',
                    data:{
                        'reserve_data':reserve_data
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
                            if(localStorage.getItem('reserve_data') != undefined && localStorage.getItem('reserve_data') != null){
                                localStorage.removeItem('reserve_data')
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

reserveTime.addEventListener('input', function (event) {
    event.preventDefault()
    if(reserveTime.value != '' && parseInt(open_reserve_price_input_.value)>0 && debt_two_client_id_.value != ''){
        if(reserve_submit_time.disabled == true){
            reserve_submit_time.disabled = false
        }
    }
})
