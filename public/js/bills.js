let bill_info_table = document.getElementsByClassName('bill_info_table')

let bills_history_subtotal = document.getElementById('bills_history_subtotal')
let bills_history_service = document.getElementById('bills_history_service')
let bills_history_discount = document.getElementById('bills_history_discount')
let bills_history_total = document.getElementById('bills_history_total')

let opened_bills_subtotal = document.getElementById('opened_bills_subtotal')
let opened_bills_service = document.getElementById('opened_bills_service')
let opened_bills_discount = document.getElementById('opened_bills_discount')
let opened_bills_total = document.getElementById('opened_bills_total')

let closed_bills_subtotal = document.getElementById('closed_bills_subtotal')
let closed_bills_service = document.getElementById('closed_bills_service')
let closed_bills_discount = document.getElementById('closed_bills_discount')
let closed_bills_total = document.getElementById('closed_bills_total')

let pay_off_debt_subtotal = document.getElementById('pay_off_debt_subtotal')
let pay_off_debt_service = document.getElementById('pay_off_debt_service')
let pay_off_debt_discount = document.getElementById('pay_off_debt_discount')
let pay_off_debt_total = document.getElementById('pay_off_debt_total')

let bills_history_code = document.getElementById('bills_history_code')
let opened_bills_code = document.getElementById('opened_bills_code')
let closed_bills_code = document.getElementById('closed_bills_code')
let pay_off_debt_code = document.getElementById('pay_off_debt_code')

let bills_history_data = document.getElementById('bills_history_data')
let opened_bills_data = document.getElementById('opened_bills_data')
let closed_bills_data = document.getElementById('closed_bills_data')
let pay_off_debt_data = document.getElementById('pay_off_debt_data')

let bills_history_tab = document.getElementById('bills_history-tab')
let opened_bills_tab = document.getElementById('opened_bills-tab')
let closed_bills_tab = document.getElementById('closed_bills-tab')
let pay_off_debt_tab = document.getElementById('pay_off_debt-tab')

let pay_bill_input = document.getElementById('pay_bill_input')
let pay_off_debt_input = document.getElementById('pay_off_debt_input')

let opened_bills_open_debt = document.getElementById('opened_bills_open_debt')
let opened_bills_pay_bill = document.getElementById('opened_bills_pay_bill')

let remaining_amount_content = document.getElementById('remaining_amount_content')
let remaining_amount_sum = document.getElementById('remaining_amount')

let remaining_amount_pay_off_content = document.getElementById('remaining_amount_pay_off_content')
let remaining_amount_pay_off_sum = document.getElementById('remaining_amount_pay_off')

let closed_bills_pay_bill = document.getElementById('closed_bills_pay_bill')
let pay_off_debt_button = document.getElementById('pay_off_debt_button')

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
let bill_id_input = document.getElementById('bill_id')
let bill_id_input_ = document.getElementById('bill_id_')
let bill_id_input__ = document.getElementById('bill_id__')

let open_debt_input = document.getElementById('open_debt_input')

let client_select_id = document.getElementById('client_select_id')
let client_select_submit = document.getElementById('client_select_submit')

let client_create_submit = document.getElementById('client_create_submit')

let client_full_name_html = document.getElementById('client_full_name')
let debitor_client_full_name_html = document.getElementById('debitor_client_full_name')
let client_full_info_html = document.getElementById('client_full_info')
let debitor_client_full_info_html = document.getElementById('debitor_client_full_info')

let open_debt_modal = document.querySelector('#open_debt_modal')

let client_max_payment = 0
let bill_id = ''
$(document).ready(function () {
    if($('#client_select_id') != undefined && $('#client_select_id') != null){
        $('#client_select_id').select2({
            dropdownParent: $('#select_client') // modal ID ni kiriting
        });
    }
})


let bills_history_html = ''
let opened_bills_html = ''
let closed_bills_html = ''
let pay_off_debt_html = ''

if(client_name.value == '' || client_surname.value == '' || client_phone.value == ''){
    client_create_submit.disabled = true
}
open_debt_modal.addEventListener('scroll', function () {
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
let debt_loader = document.getElementById("debt_loader")
let debt_content = document.getElementById("debt_content")

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
    if(open_debt_input.hasAttribute('max')){
        open_debt_input.removeAttribute('max')
    }
    open_debt_input.value = 0
    debt_two_client_id.value = ''
    bill_id_input.value = bill_id
    bill_id_input_.value = bill_id
    if(debt_loader.classList.contains("d-none")){
        debt_loader.classList.remove("d-none")
    }
    if(!debt_content.classList.contains("d-none")){
        debt_content.classList.add("d-none")
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
                                bill_id_input.value = bill_id
                                bill_id_input_.value = bill_id
                                if(client_max_payment>0){
                                    open_debt_input.setAttribute('max', client_max_payment)
                                    open_debt_input.value = client_max_payment
                                }
                            }else{
                                debt_second_confirm.disabled = true
                                debitor_client_full_name_html.textContent = not_found
                            }
                            if(!debt_loader.classList.contains("d-none")){
                                debt_loader.classList.add("d-none")
                            }
                            if(debt_content.classList.contains("d-none")){
                                debt_content.classList.remove("d-none")
                            }
                        }
                    }
                })
            })
        }
    }, 2000)

})
open_debt_input.addEventListener('mousemove', function () {
    if(parseInt(open_debt_input.value)<=0){
        debt_second_confirm.disabled = true
    }else{
        debt_second_confirm.disabled = false
    }
})
open_debt_input.addEventListener('change', function () {
    if(parseInt(open_debt_input.value)<=0){
        debt_second_confirm.disabled = true
    }else{
        debt_second_confirm.disabled = false
    }
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
    if(open_debt_input.hasAttribute('max')){
        open_debt_input.removeAttribute('max')
    }

    if(debt_loader.classList.contains("d-none")){
        debt_loader.classList.remove("d-none")
    }
    if(!debt_content.classList.contains("d-none")){
        debt_content.classList.add("d-none")
    }
    open_debt_input.value = 0
    debt_two_client_id.value = ''
    bill_id_input.value = bill_id
    bill_id_input_.value = bill_id
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

                        if(client_max_payment>0){
                            open_debt_input.setAttribute('max', client_max_payment)
                            open_debt_input.value = client_max_payment
                        }
                    }else{
                        debt_second_confirm.disabled = true
                        debitor_client_full_name_html.textContent = not_found
                    }
                    if(!debt_loader.classList.contains("d-none")){
                        debt_loader.classList.add("d-none")
                    }
                    if(debt_content.classList.contains("d-none")){
                        debt_content.classList.remove("d-none")
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

function setItem(item, index, type, code){
    switch(type){
        case 'bills_history':
            if(parseInt(item.price) > parseInt(item.all_price)){
                bills_history_html = bills_history_html  + `<div class="bill_info d-flex justify-content-between align-items-center">
                                                <div class="width_30_percent">
                                                    <span class="me-2">${index+1}.</span>
                                                    <img onclick="showImage('${item.items.product_image}')"  data-bs-toggle="modal" data-bs-target="#images-modal" src="${item.items.product_image}" alt="" height="44px">
                                                </div>
                                                <div class="width_45_percent d-flex flex-column justify-content-center">
                                                    <span>${item.items.product_name}</span>
                                                    <span>${item.quantity} ${items_text}</span>
                                                </div>
                                                <div class="width_25_percent text-end bill_info_sum d-flex flex-column">
                                                    <span>${item.all_price} ${sum_text}</span>
                                                    <del class="opacity_1">${item.price} ${sum_text}</del>
                                                </div>
                                            </div>`
            }else{
                bills_history_html = bills_history_html  + `<div class="bill_info d-flex justify-content-between align-items-center">
                                                <div class="width_30_percent">
                                                    <span class="me-2">${index+1}.</span>
                                                    <img onclick="showImage('${item.items.product_image}')" data-bs-toggle="modal" data-bs-target="#images-modal" src="${item.items.product_image}" alt="" height="44px">
                                                </div>
                                                <div class="width_45_percent d-flex flex-column justify-content-center">
                                                    <span>${item.items.product_name}</span>
                                                    <span>${item.quantity} ${items_text}</span>
                                                </div>
                                                <div class="width_25_percent text-end bill_info_sum">${item.price} ${sum_text}</div>
                                            </div>`
            }
            break;
        case 'opened_bills':
            if(parseInt(item.price) > parseInt(item.all_price)){
                opened_bills_html = opened_bills_html  + `<div class="bill_info d-flex justify-content-between align-items-center">
                                                <div class="width_30_percent">
                                                    <span class="me-2">${index+1}.</span>
                                                    <img onclick="showImage('${item.items.product_image}')" data-bs-toggle="modal" data-bs-target="#images-modal" src="${item.items.product_image}" alt="" height="44px">
                                                </div>
                                                <div class="width_45_percent d-flex flex-column justify-content-center">
                                                    <span>${item.items.product_name}</span>
                                                    <span>${item.quantity} ${items_text}</span>
                                                </div>
                                                <div class="width_25_percent text-end bill_info_sum d-flex flex-column">
                                                    <span>${item.all_price} ${sum_text}</span>
                                                    <del class="opacity_1">${item.price} ${sum_text}</del>
                                                </div>
                                            </div>`
            }else{
                opened_bills_html = opened_bills_html  + `<div class="bill_info d-flex justify-content-between align-items-center">
                                                <div class="width_30_percent">
                                                    <span class="me-2">${index+1}.</span>
                                                    <img onclick="showImage('${item.items.product_image}')" data-bs-toggle="modal" data-bs-target="#images-modal" src="${item.items.product_image}" alt="" height="44px">
                                                </div>
                                                <div class="width_45_percent d-flex flex-column justify-content-center">
                                                    <span>${item.items.product_name}</span>
                                                    <span>${item.quantity} ${items_text}</span>
                                                </div>
                                                <div class="width_25_percent text-end bill_info_sum">${item.price} ${sum_text}</div>
                                            </div>`
            }
            break;
        case 'closed_bills':
            if(parseInt(item.price) > parseInt(item.all_price)){
                closed_bills_html = closed_bills_html  + `<div class="bill_info d-flex justify-content-between align-items-center">
                                                <div class="width_30_percent">
                                                    <span class="me-2">${index+1}.</span>
                                                    <img onclick="showImage('${item.items.product_image}')" data-bs-toggle="modal" data-bs-target="#images-modal" src="${item.items.product_image}" alt="" height="44px">
                                                </div>
                                                <div class="width_45_percent d-flex flex-column justify-content-center">
                                                    <span>${item.items.product_name}</span>
                                                    <span>${item.quantity} ${items_text}</span>
                                                </div>
                                                <div class="width_25_percent text-end bill_info_sum d-flex flex-column">
                                                    <span>${item.all_price} ${sum_text}</span>
                                                    <del class="opacity_1">${item.price} ${sum_text}</del>
                                                </div>
                                            </div>`
            }else{
                closed_bills_html = closed_bills_html  + `<div class="bill_info d-flex justify-content-between align-items-center">
                                                <div class="width_30_percent">
                                                    <span class="me-2">${index+1}.</span>
                                                    <img onclick="showImage('${item.items.product_image}')" data-bs-toggle="modal" data-bs-target="#images-modal" src="${item.items.product_image}" alt="" height="44px">
                                                </div>
                                                <div class="width_45_percent d-flex flex-column justify-content-center">
                                                    <span>${item.items.product_name}</span>
                                                    <span>${item.quantity} ${items_text}</span>
                                                </div>
                                                <div class="width_25_percent text-end bill_info_sum">${item.price} ${sum_text}</div>
                                            </div>`
            }
            break;
        case 'pay_off_debt':
            if(parseInt(item.price) > parseInt(item.all_price)){
                pay_off_debt_html = pay_off_debt_html  + `<div class="bill_info d-flex justify-content-between align-items-center">
                                                <div class="width_30_percent">
                                                    <span class="me-2">${index+1}.</span>
                                                    <img onclick="showImage('${item.items.product_image}')" data-bs-toggle="modal" data-bs-target="#images-modal" src="${item.items.product_image}" alt="" height="44px">
                                                </div>
                                                <div class="width_45_percent d-flex flex-column justify-content-center">
                                                    <span>${item.items.product_name}</span>
                                                    <span>${item.quantity} ${items_text}</span>
                                                </div>
                                                <div class="width_25_percent text-end bill_info_sum d-flex flex-column">
                                                    <span>${item.all_price} ${sum_text}</span>
                                                    <del class="opacity_1">${item.price} ${sum_text}</del>
                                                </div>
                                            </div>`
            }else{
                pay_off_debt_html = pay_off_debt_html  + `<div class="bill_info d-flex justify-content-between align-items-center">
                                                <div class="width_30_percent">
                                                    <span class="me-2">${index+1}.</span>
                                                    <img onclick="showImage('${item.items.product_image}')" data-bs-toggle="modal" data-bs-target="#images-modal" src="${item.items.product_image}" alt="" height="44px">
                                                </div>
                                                <div class="width_45_percent d-flex flex-column justify-content-center">
                                                    <span>${item.items.product_name}</span>
                                                    <span>${item.quantity} ${items_text}</span>
                                                </div>
                                                <div class="width_25_percent text-end bill_info_sum">${item.price} ${sum_text}</div>
                                            </div>`
            }
            break;
    }
}

function setData(item, index, type, code) {
    switch(type){
        case 'bills_history':
            if(item.code == code){
                bills_history_code.innerHTML = '#'+item.code
                if(item.products_data != null && item.products_data != undefined){
                    item.products_data.forEach((item_, index_) =>{
                        setItem(item_, index_, type, code)
                    });
                }
            }
            bills_history_data.innerHTML = bills_history_html
            break;
        case 'opened_bills':
            if(item.code == code){
                opened_bills_code.innerHTML = '#'+item.code
                if(item.products_data != null && item.products_data != undefined){
                    item.products_data.forEach((item_, index_) =>{
                        setItem(item_, index_, type, code)
                    });
                }
            }
            opened_bills_data.innerHTML = opened_bills_html
            break;
        case 'closed_bills':
            if(item.code == code){
                closed_bills_code.innerHTML = '#'+item.code
                if(item.products_data != null && item.products_data != undefined){
                    item.products_data.forEach((item_, index_) =>{
                        setItem(item_, index_, type, code)
                    });
                }
            }
            closed_bills_data.innerHTML = closed_bills_html
            break;
        case 'pay_off_debt':
            if(item.code == code){
                pay_off_debt_code.innerHTML = '#'+item.code
                if(item.products_data != null && item.products_data != undefined){
                    item.products_data.forEach((item_, index_) =>{
                        setItem(item_, index_, type, code)
                    });
                }
            }
            pay_off_debt_data.innerHTML = pay_off_debt_html
            break;
    }
}

function showBillInfo(this_element, bill_data, type, code, price, discount_price, total_amount, remaining_amount, service_price, billId, client_info, client_full_name){
    bill_id = billId
    let pay_bill_total_amount = parseInt(total_amount.split(' ').join(''))
    let pay_bill_remaining_amount = parseInt(remaining_amount.split(' ').join(''))
    if(pay_bill_remaining_amount>0){
        client_max_payment = pay_bill_remaining_amount
    }else if(pay_bill_total_amount>0){
        client_max_payment = pay_bill_total_amount
    }else{
        client_max_payment = 0
    }

    for(let j=0; j<bill_info_table.length; j++){
        if(bill_info_table[j].classList.contains('active')){
            bill_info_table[j].classList.remove('active')
        }
    }
    if(!this_element.classList.contains('active')){
        this_element.classList.add('active')
    }
    bills_history_html = ''
    opened_bills_html = ''
    closed_bills_html = ''
    pay_off_debt_html = ''
    switch(type){
        case 'bills_history':
            bills_history_subtotal.textContent = price +' '+ sum_text
            bills_history_service.textContent = service_price +' '+ sum_text
            bills_history_discount.textContent = discount_price +' '+ sum_text
            bills_history_total.textContent = total_amount +' '+ sum_text
            break;
        case 'opened_bills':
            opened_bills_subtotal.textContent = price +' '+ sum_text
            opened_bills_service.textContent = service_price +' '+ sum_text
            opened_bills_discount.textContent = discount_price +' '+ sum_text
            opened_bills_total.textContent = total_amount +' '+ sum_text
            if(pay_bill_remaining_amount>0){
                remaining_amount_sum.textContent = remaining_amount +' '+ sum_text
            }
            if(pay_bill_total_amount>0){
                bill_id_input.value = bill_id
                bill_id_input_.value = bill_id
                if(pay_bill_remaining_amount>0){
                    pay_bill_input.value = pay_bill_remaining_amount
                    pay_bill_input.setAttribute('max', pay_bill_remaining_amount)
                }else{
                    pay_bill_input.value = pay_bill_total_amount
                    pay_bill_input.setAttribute('max', pay_bill_total_amount)
                }
            }else{
                if(pay_bill_input.hasAttribute('max')){
                    pay_bill_input.removeAttribute('max')
                }
            }
            if(opened_bills_open_debt.disabled == true){
                opened_bills_open_debt.disabled = false
            }
            if(opened_bills_pay_bill.disabled == true){
                opened_bills_pay_bill.disabled = false
            }
            if(pay_bill_remaining_amount>0){
                if(remaining_amount_content.classList.contains('d-none')){
                    remaining_amount_content.classList.remove('d-none')
                }
            }
            break;
        case 'closed_bills':
            closed_bills_subtotal.textContent = price +' '+ sum_text
            closed_bills_service.textContent = service_price +' '+ sum_text
            closed_bills_discount.textContent = discount_price +' '+ sum_text
            closed_bills_total.textContent = total_amount +' '+ sum_text
            if(closed_bills_pay_bill.classList.contains('d-none')){
                closed_bills_pay_bill.classList.remove('d-none')
            }
            break;
        case 'pay_off_debt':
            pay_off_debt_subtotal.textContent = price +' '+ sum_text
            pay_off_debt_service.textContent = service_price +' '+ sum_text
            pay_off_debt_discount.textContent = discount_price +' '+ sum_text
            pay_off_debt_total.textContent = total_amount +' '+ sum_text
            if(pay_off_debt_button.disabled == true){
                pay_off_debt_button.disabled = false
            }
            client_full_name_html.textContent = client_full_name
            if(pay_bill_total_amount>0){
                bill_id_input__.value = bill_id
                if(pay_bill_remaining_amount>0){
                    pay_off_debt_input.value = pay_bill_remaining_amount
                    pay_off_debt_input.setAttribute('max', pay_bill_remaining_amount)
                }else{
                    pay_off_debt_input.value = pay_bill_total_amount
                    pay_off_debt_input.setAttribute('max', pay_bill_total_amount)
                }
            }else{
                if(pay_off_debt_input.hasAttribute('max')){
                    pay_off_debt_input.removeAttribute('max')
                }
            }
            let client_phone = ''
            let client_image = ''
            let client_address = ''
            let client_email = ''
            let client_gender = ''
            let client_notes = ''
            if(client_info.phone != undefined && client_info.phone != null){
                client_phone = `<div class="col-5">${phone_text}</div><div class="col-7">${client_info.phone}</div>`
            }
            if(client_info.image != undefined && client_info.image != null){
                client_image = `<div class="col-5">${image_text}</div><div class="col-7">
                    <img src="${client_info.image}" alt="" height="44px" onclick="showImage('${client_info.image}')" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#images-modal">
                </div>`
            }
            if(client_info.address != undefined && client_info.address != null){
                client_address = `<div class="col-5">${address_text}</div><div class="col-7">${client_info.address}</div>`
            }
            if(client_info.email != undefined && client_info.email != null){
                client_email = `<div class="col-5">${email_text}</div><div class="col-7">${client_info.email}</div>`
            }
            if(client_info.gender != undefined && client_info.gender != null){
                client_gender = `<div class="col-5">${gender_text}</div><div class="col-7">${client_info.gender}</div>`
            }
            if(client_info.notes != undefined && client_info.notes != null){
                client_notes = `<div class="col-5">${notes_text}</div><div class="col-7">${client_info.notes}</div>`
            }
            let client_info_html_code = `<div class="row">
                                            ${client_phone}
                                            ${client_image}
                                            ${client_address}
                                            ${client_email}
                                            ${client_gender}
                                            ${client_notes}
                                        </div>`
            client_full_info_html.innerHTML = client_info_html_code

            if(pay_bill_remaining_amount>0){
                remaining_amount_pay_off_sum.textContent = remaining_amount +' '+ sum_text
            }
            if(pay_bill_remaining_amount>0){
                if(remaining_amount_pay_off_content.classList.contains('d-none')){
                    remaining_amount_pay_off_content.classList.remove('d-none')
                }
            }
            break;
    }

    bill_data.forEach((item, index) => {
        setData(item, index, type, code);
    });
}

function removeActive(){
    for(let j=0; j<bill_info_table.length; j++){
        if(bill_info_table[j].classList.contains('active')){
            bill_info_table[j].classList.remove('active')
        }
    }
}
bills_history_tab.addEventListener('click', function () {
    opened_bills_code.textContent = ''
    opened_bills_data.innerHTML= ''
    closed_bills_code.textContent = ''
    closed_bills_data.innerHTML= ''
    pay_off_debt_code.textContent = ''
    pay_off_debt_data.innerHTML= ''
    if(opened_bills_open_debt.disabled == false){
        opened_bills_open_debt.disabled = true
    }
    if(!remaining_amount_content.classList.contains('d-none')){
        remaining_amount_content.classList.add('d-none')
    }
    if(opened_bills_pay_bill.disabled == false){
        opened_bills_pay_bill.disabled = true
    }
    if(!closed_bills_pay_bill.classList.contains('d-none')){
        closed_bills_pay_bill.classList.add('d-none')
    }
    if(pay_off_debt_button.disabled == false){
        pay_off_debt_button.disabled = true
    }
    bills_history_html = ''
    opened_bills_html = ''
    closed_bills_html = ''
    pay_off_debt_html = ''
    removeActive()
})
opened_bills_tab.addEventListener('click', function () {
    bills_history_code.textContent = ''
    bills_history_data.innerHTML= ''
    closed_bills_code.textContent = ''
    closed_bills_data.innerHTML= ''
    pay_off_debt_code.textContent = ''
    pay_off_debt_data.innerHTML= ''
    if(!closed_bills_pay_bill.classList.contains('d-none')){
        closed_bills_pay_bill.classList.add('d-none')
    }
    if(pay_off_debt_button.disabled == false){
        pay_off_debt_button.disabled = true
    }
    bills_history_html = ''
    opened_bills_html = ''
    closed_bills_html = ''
    pay_off_debt_html = ''
    removeActive()
})
closed_bills_tab.addEventListener('click', function () {
    bills_history_code.textContent = ''
    bills_history_data.innerHTML= ''
    opened_bills_code.textContent = ''
    opened_bills_data.innerHTML= ''
    pay_off_debt_code.textContent = ''
    pay_off_debt_data.innerHTML= ''
    if(opened_bills_open_debt.disabled == false){
        opened_bills_open_debt.disabled = true
    }
    if(!remaining_amount_content.classList.contains('d-none')){
        remaining_amount_content.classList.add('d-none')
    }
    if(opened_bills_pay_bill.disabled == false){
        opened_bills_pay_bill.disabled = true
    }
    if(pay_off_debt_button.disabled == false){
        pay_off_debt_button.disabled = true
    }
    bills_history_html = ''
    opened_bills_html = ''
    closed_bills_html = ''
    pay_off_debt_html = ''
    removeActive()
})
pay_off_debt_tab.addEventListener('click', function () {
    bills_history_code.textContent = ''
    bills_history_data.innerHTML= ''
    opened_bills_code.textContent = ''
    opened_bills_data.innerHTML= ''
    closed_bills_code.textContent = ''
    closed_bills_data.innerHTML= ''
    if(opened_bills_open_debt.disabled == false){
        opened_bills_open_debt.disabled = true
    }
    if(!remaining_amount_content.classList.contains('d-none')){
        remaining_amount_content.classList.add('d-none')
    }
    if(opened_bills_pay_bill.disabled == false){
        opened_bills_pay_bill.disabled = true
    }
    if(!closed_bills_pay_bill.classList.contains('d-none')){
        closed_bills_pay_bill.classList.add('d-none')
    }
    bills_history_html = ''
    opened_bills_html = ''
    closed_bills_html = ''
    pay_off_debt_html = ''
    removeActive()
})
