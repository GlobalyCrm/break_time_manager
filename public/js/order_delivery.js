let pay_bill_input = document.getElementById('pay_bill_input')

let client_name = document.getElementById('client_name')
let client_surname = document.getElementById('client_surname')
let client_middlename = document.getElementById('client_middlename')
let client_phone = document.getElementById('client_phone')
let client_image_input = document.getElementById('client_image_input')
let client_email = document.getElementById('client_email')
let client_male = document.getElementById('client_male')
let client_female = document.getElementById('client_female')
let client_address = document.getElementById('client_address')
let client_notes = document.getElementById('client_notes')
let client_region = document.getElementById('region')
let client_district = document.getElementById('district')

let debt_second_confirm = document.getElementById('debt_second_confirm')
let debt_two_client_id = document.getElementById('debt_two_client_id')

let open_delivery_price_input = document.getElementById('open_delivery_price_input')

let client_select_id = document.getElementById('client_select_id')
let client_select_submit = document.getElementById('client_select_submit')

let client_create_submit = document.getElementById('client_create_submit')

let debitor_client_full_name_html = document.getElementById('debitor_client_full_name')
let debitor_client_full_info_html = document.getElementById('debitor_client_full_info')


let delivery_address_ = document.getElementById('delivery_address')
let delivery_region_ = document.getElementById('delivery_region')
let delivery_district_ = document.getElementById('delivery_district')

let delivery_modal_one = document.querySelector('#delivery_modal_one')
let orderData = []
$(document).ready(function () {
    $('#client_select_id').select2({
        dropdownParent: $('#select_client') // modal ID ni kiriting
    });
})

let allSum = 0
function getOrderAllSum(){
    if(localStorage.getItem('order_data') != undefined && localStorage.getItem('order_data') != null){
        orderData = JSON.parse(localStorage.getItem('order_data'))
    }
    for(let j=0; j<orderData.length; j++) {
        allSum = allSum + orderData[j].quantity * parseInt(orderData[j].last_price.replace(/\s/g, ''), 10)
    }
    open_delivery_price_input.value = allSum
}
getOrderAllSum()

function orderDelivery() {
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
        if(orderData.length>0 && debt_two_client_id.value){
            try{
                $.ajax({
                    url: "/../api/order-delivery",
                    method: 'POST',
                    data:{
                        'order_data':orderData,
                        'client_id':debt_two_client_id.value,
                        'address':delivery_address_.value,
                        'region':delivery_region_.value,
                        'district':delivery_district_.value,
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


if(client_name.value == '' || client_surname.value == '' || client_phone.value == ''){
    client_create_submit.disabled = true
}
delivery_modal_one.addEventListener('scroll', function () {
    if(client_name.value == '' || client_surname.value == '' || client_phone.value == ''){
        client_create_submit.disabled = true
    }else{
        client_create_submit.disabled = false
    }
})
let client_id = ''
let client_data_ = {}
let client_data = {}
let client_info = {}
let client_select_id_value = ''
let delivery_loader = document.getElementById("delivery_loader")
let delivery_content_info = document.getElementById("delivery_content_info")

let refund_modal_form_url = document.getElementById('refund_modal_form')
let delete_bill_modal_form_url = document.getElementById('delete_bill_modal_form')
function refundBillFunc(url){
    refund_modal_form_url.setAttribute("action", url)
}

function deleteBillFunc(url){
    delete_bill_modal_form_url.setAttribute("action", url)
}

client_create_submit.addEventListener('click', function (e) {
    e.preventDefault()
    getOrderAllSum()
    if(open_delivery_price_input.hasAttribute('max')){
        open_delivery_price_input.removeAttribute('max')
    }
    open_delivery_price_input.value = 0
    debt_two_client_id.value = ''
    if(delivery_loader.classList.contains("d-none")){
        delivery_loader.classList.remove("d-none")
    }
    if(!delivery_content_info.classList.contains("d-none")){
        delivery_content_info.classList.add("d-none")
    }
    client_info = {
        'client_name':client_name.value,
        'client_surname':client_surname.value,
        'client_middlename':client_middlename.value,
        'client_phone':client_phone.value,
        'client_image_input':client_image_input.value,
        'client_email':client_email.value,
        'client_male':client_male.value,
        'client_female':client_female.value,
        'client_address':client_address.value,
        'client_notes':client_notes.value,
        'client_region':client_region.value,
        'client_district':client_district.value
    }

    $(document).ready(function () {
        $.ajax({
            url:"/../api/client-store",
            type:'POST',
            data:client_info,
            success: function (data) {
                if(data.status == 200){
                    client_id = data.client_id
                }
            }
        })
    })
    setTimeout(function () {
        if(client_id != ''){
            $(document).ready(function () {
                $.ajax({
                    url:`/../api/get-client-by-id/${client_id}`,
                    type:'GET',
                    success: function (data) {
                        if(data.status == 200){

                            client_data = data.client
                            if(Object.keys(client_data).length>0){
                                debitor_client_full_name_html.textContent = client_data_.fullname
                                debt_two_client_id.value = client_data_.id

                                setClientInfoForDebt(client_data_)
                                if(allSum>0){
                                    open_delivery_price_input.setAttribute('max', allSum)
                                    open_delivery_price_input.value = allSum
                                }
                            }else{
                                debt_second_confirm.disabled = true
                                debitor_client_full_name_html.textContent = not_found
                            }
                            if(!delivery_loader.classList.contains("d-none")){
                                delivery_loader.classList.add("d-none")
                            }
                            if(delivery_content_info.classList.contains("d-none")){
                                delivery_content_info.classList.remove("d-none")
                            }
                        }
                    }
                })
            })
        }
    }, 2000)

})


if(client_select_id.value == '') {
    client_select_submit.disabled = true
}
$(document).ready(function () {
    $('#client_select_id').on('change', function () {
        if($(this).val() != '') {
            client_select_id_value = $(this).val()
            client_select_submit.disabled = false
        }
    });
})

client_select_submit.addEventListener('click', function (e) {
    e.preventDefault()
    getOrderAllSum()
    if(open_delivery_price_input.hasAttribute('max')){
        open_delivery_price_input.removeAttribute('max')
    }

    if(delivery_loader.classList.contains("d-none")){
        delivery_loader.classList.remove("d-none")
    }
    if(!delivery_content_info.classList.contains("d-none")){
        delivery_content_info.classList.add("d-none")
    }
    open_delivery_price_input.value = 0
    debt_two_client_id.value = ''
    if(client_select_id_value != ''){
        $.ajax({
            url:`/../api/get-client-by-id/${client_select_id_value}`,
            type:'GET',
            success: function (data) {
                if(data.status == 200){
                    client_data_ = data.client
                    if(Object.keys(client_data_).length>0){
                        debitor_client_full_name_html.textContent = client_data_.fullname
                        debt_two_client_id.value = client_data_.id
                        setClientInfoForDebt(client_data_)
                        if(allSum>0){
                            open_delivery_price_input.setAttribute('max', allSum)
                            open_delivery_price_input.value = allSum
                        }
                    }else{
                        debt_second_confirm.disabled = true
                        debitor_client_full_name_html.textContent = not_found
                    }
                    if(!delivery_loader.classList.contains("d-none")){
                        delivery_loader.classList.add("d-none")
                    }
                    if(delivery_content_info.classList.contains("d-none")){
                        delivery_content_info.classList.remove("d-none")
                    }
                }
            }
        })
    }
})

function setClientInfoForDebt(client_data_){

    let debitor_client_phone = ''
    let debitor_client_image = ''
    let debitor_client_address = ''
    let debitor_client_email = ''
    let debitor_client_gender = ''
    let debitor_client_notes = ''
    if(client_data_.phone != undefined && client_data_.phone != null){
        debitor_client_phone = `<div class="col-5">${phone_text}</div><div class="col-7">${client_data_.phone}</div>`
    }
    if(client_data_.image != undefined && client_data_.image != null){
        debitor_client_image = `<div class="col-5">${image_text}</div><div class="col-7">
                            <img src="${client_data_.image}" alt="" height="44px" onclick="showImage('${client_data_.image}')" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#images-modal">
                        </div>`
    }
    if(client_data_.address != undefined && client_data_.address != null){
        debitor_client_address = `<div class="col-5">${address_text}</div><div class="col-7">${client_data_.address}</div>`
    }
    if(client_data_.email != undefined && client_data_.email != null){
        debitor_client_email = `<div class="col-5">${email_text}</div><div class="col-7">${client_data_.email}</div>`
    }
    if(client_data_.gender != undefined && client_data_.gender != null){
        debitor_client_gender = `<div class="col-5">${gender_text}</div><div class="col-7">${client_data_.gender}</div>`
    }
    if(client_data_.notes != undefined && client_data_.notes != null){
        debitor_client_notes = `<div class="col-5">${notes_text}</div><div class="col-7">${client_data_.notes}</div>`
    }
    let client_info_html_code = `<div class="row">
                                            ${debitor_client_phone}
                                            ${debitor_client_image}
                                            ${debitor_client_address}
                                            ${debitor_client_email}
                                            ${debitor_client_gender}
                                            ${debitor_client_notes}
                                        </div>`
    debitor_client_full_info_html.innerHTML = client_info_html_code

}
