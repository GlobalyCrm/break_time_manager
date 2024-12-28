let client_subcategory_exists = document.getElementById('client_subcategory_exists')
let client_product_exists = document.getElementById('client_product_exists')

let client_category_id = document.getElementById('client_category_id')
let client_subcategory_id = document.getElementById('client_subcategory_id')
let client_product_id = document.getElementById('client_product_id')

function clientDiscountAddOption(item, index){
    let client_option = document.createElement('option')
    client_option.value = item.id
    client_option.text = item.name
    if(client_discount_subcategory_id != 'two'){
        if(item.id == client_discount_subcategory_id){
            client_option.selected = true
        }
    }
    client_subcategory_id.add(client_option)
}

if(client_discount_subcategory_id != '' && client_discount_category_id != 'two' && client_discount_category_id != ''){
    client_subcategory_id.innerHTML = ""
    client_product_id.innerHTML = ""
    $(document).ready(function () {
        $.ajax({
            url:`/../api/subcategory/${client_discount_category_id}`,
            type:'GET',
            success: function (data) {
                if(client_subcategory_exists.classList.contains('d-none')){
                    client_subcategory_exists.classList.remove('d-none')
                }
                client_subcategory_id.required = true
                let client_disabled_option = document.createElement('option')
                client_disabled_option.text = text_select_product
                client_disabled_option.disabled = true
                client_disabled_option.value = ''
                client_subcategory_id.add(client_disabled_option)
                let client_all_subcategories = document.createElement('option')
                client_all_subcategories.text = text_all_subcategory_products
                client_all_subcategories.value = "all"
                client_subcategory_id.add(client_all_subcategories)
                data.data.forEach(clientDiscountAddOption)
            },
            error: function (e) {
                if(!client_subcategory_exists.classList.contains('display-none')){
                    client_subcategory_exists.classList.add('display-none')
                }
                if(client_subcategory_id.getAttribute('required') != null && client_subcategory_id.getAttribute('required') != undefined){
                    client_subcategory_id.removeAttribute('required')
                }
            }
        })
    })
}


function clientDiscountAddOptionToProduct(item, index){
    let client_option = document.createElement('option')
    client_option.value = item.id
    client_option.text = item.name
    if(client_discount_product_id != 'two'){
        if(item.id == client_discount_product_id){
            client_option.selected = true
        }
    }
    client_product_id.add(client_option)
}
if(client_discount_product_id != undefined && client_discount_product_id != '' && client_discount_product_id != null
    && client_discount_subcategory_id != 'two' && client_discount_subcategory_id != '' && client_discount_subcategory_id != 'all'){
    client_product_id.innerHTML = ""
    $(document).ready(function () {
        $.ajax({
            url:`/../api/get-products-by-category?category_id=${client_discount_subcategory_id}`,
            type:'GET',
            success: function (data) {
                if(client_product_exists.classList.contains('d-none')){
                    client_product_exists.classList.remove('d-none')
                }
                client_product_id.required = true
                let client_disabled_option = document.createElement('option')
                client_disabled_option.text = text_select_product
                client_disabled_option.disabled = true
                client_disabled_option.value = ''
                client_product_id.add(client_disabled_option)
                let client_all_products = document.createElement('option')
                client_all_products.text = text_all_products
                client_all_products.value = "all"
                client_product_id.add(client_all_products)
                data.data[0].products.forEach(clientDiscountAddOptionToProduct)
            },
            error: function (e) {
                if(!client_product_exists.classList.contains('d-none')){
                    client_product_exists.classList.add('d-none')
                }
                if(client_product_id.getAttribute('required') != null && client_product_id.getAttribute('required') != undefined){
                    client_product_id.removeAttribute('required')
                }
            }
        })
    })
}else{
    let client_all_products = document.createElement('option')
    client_all_products.text = text_all_products
    client_all_products.value = "all"
    client_all_products.selected = true
    client_product_id.add(client_all_products)
}

function clientAddOption(item, index){
    let client_option = document.createElement('option')
    client_option.value = item.id
    client_option.text = item.name
    client_subcategory_id.add(client_option)
}

client_category_id.addEventListener('change', function () {
    client_subcategory_id.innerHTML = ""
    client_product_id.innerHTML = ""
    if(!client_product_exists.classList.contains('d-none')){
        client_product_exists.classList.add('d-none')
    }
    $(document).ready(function () {
        if(client_category_id.value != '') {
            $.ajax({
                url: `/../api/subcategory/${client_category_id.value}`,
                type: 'GET',
                success: function (data) {
                    if (client_subcategory_exists.classList.contains('d-none')) {
                        client_subcategory_exists.classList.remove('d-none')
                    }
                    client_subcategory_id.required = true
                    let client_disabled_option = document.createElement('option')
                    client_disabled_option.text = text_select_sub_category
                    client_disabled_option.selected = true
                    client_disabled_option.disabled = true
                    client_disabled_option.value = ''
                    client_subcategory_id.add(client_disabled_option)
                    let client_all_products = document.createElement('option')
                    client_all_products.text = text_all_products
                    client_all_products.value = "all"
                    client_subcategory_id.add(client_all_products)
                    data.data.forEach(clientAddOption)
                },
                error: function (e) {
                    console.log(e)
                    if (!client_subcategory_exists.classList.contains('d-none')) {
                        client_subcategory_exists.classList.add('d-none')
                    }
                    if (!client_product_exists.classList.contains('d-none')) {
                        client_product_exists.classList.add('d-none')
                    }
                    if(client_subcategory_id.getAttribute('required') != null && client_subcategory_id.getAttribute('required') != undefined){
                        client_subcategory_id.removeAttribute('required')
                    }
                }
            })
        }else{
            if (!client_subcategory_exists.classList.contains('d-none')) {
                client_subcategory_exists.classList.add('d-none')
            }
            if (!client_product_exists.classList.contains('d-none')) {
                client_product_exists.classList.add('d-none')
            }
            if(client_subcategory_id.getAttribute('required') != null && client_subcategory_id.getAttribute('required') != undefined){
                client_subcategory_id.removeAttribute('required')
            }
        }
    })
})

function clientAddOptionToProduct(item, index){
    let client_option = document.createElement('option')
    client_option.value = item.id
    client_option.text = item.name
    client_product_id.add(client_option)
}
client_subcategory_id.addEventListener('change', function () {
    client_product_id.innerHTML = ""
    $(document).ready(function () {
        if(client_subcategory_id.value != 'all' && client_subcategory_id.value != ''){
            $.ajax({
                url:`/../api/get-products-by-category?category_id=${client_subcategory_id.value}`,
                type:'GET',
                success: function (data) {
                    if(client_product_exists.classList.contains('d-none')){
                        client_product_exists.classList.remove('d-none')
                    }
                    client_product_id.required = true
                    let client_disabled_option = document.createElement('option')
                    client_disabled_option.text = text_select_product
                    client_disabled_option.selected = true
                    client_disabled_option.disabled = true
                    client_disabled_option.value = ''
                    client_product_id.add(client_disabled_option)
                    let client_all_products = document.createElement('option')
                    client_all_products.text = text_all_products
                    client_all_products.value = "all"
                    client_product_id.add(client_all_products)
                    data.data[0].products.forEach(clientAddOptionToProduct)
                },
                error: function (e) {
                    console.log(e)
                    if(!client_product_exists.classList.contains('d-none')){
                        client_product_exists.classList.add('d-none')
                    }
                    if(client_product_id.getAttribute('required') != null && client_product_id.getAttribute('required') != undefined){
                        client_product_id.removeAttribute('required')
                    }
                }
            })
        }else{
            if(!client_product_exists.classList.contains('d-none')){
                client_product_exists.classList.add('d-none')
            }
            if(client_product_id.getAttribute('required') != null && client_product_id.getAttribute('required') != undefined){
                client_product_id.removeAttribute('required')
            }
        }
    })
})
