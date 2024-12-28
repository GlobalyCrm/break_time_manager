let subcategory_exists = document.getElementById('subcategory_exists')
let product_exists = document.getElementById('product_exists')

let category_id = document.getElementById('category_id')
let subcategory_id = document.getElementById('subcategory_id')
let product_id = document.getElementById('product_id')

function discountAddOption(item, index){
    let option = document.createElement('option')
    option.value = item.id
    option.text = item.name
    if(discount_subcategory_id != 'two'){
        if(item.id == discount_subcategory_id){
            option.selected = true
        }
    }
    subcategory_id.add(option)
}

if(discount_subcategory_id != '' && discount_category_id != 'two' && discount_category_id != ''){
    subcategory_id.innerHTML = ""
    product_id.innerHTML = ""
    $(document).ready(function () {
        $.ajax({
            url:`/../api/subcategory/${discount_category_id}`,
            type:'GET',
            success: function (data) {
                if(subcategory_exists.classList.contains('d-none')){
                    subcategory_exists.classList.remove('d-none')
                }
                subcategory_id.required = true
                let disabled_option = document.createElement('option')
                disabled_option.text = text_select_product
                disabled_option.disabled = true
                disabled_option.value = ''
                subcategory_id.add(disabled_option)
                let all_subcategories = document.createElement('option')
                all_subcategories.text = text_all_subcategory_products
                all_subcategories.value = "all"
                subcategory_id.add(all_subcategories)
                data.data.forEach(discountAddOption)
            },
            error: function (e) {
                if(!subcategory_exists.classList.contains('display-none')){
                    subcategory_exists.classList.add('display-none')
                }
                if(subcategory_id.getAttribute('required') != null && subcategory_id.getAttribute('required') != undefined){
                    subcategory_id.removeAttribute('required')
                }
            }
        })
    })
}


function discountAddOptionToProduct(item, index){
    let option = document.createElement('option')
    option.value = item.id
    option.text = item.name
    if(discount_product_id != 'two'){
        if(item.id == discount_product_id){
            option.selected = true
        }
    }
    product_id.add(option)
}
if(discount_product_id != undefined && discount_product_id != '' && discount_product_id != null
    && discount_subcategory_id != 'two' && discount_subcategory_id != '' && discount_subcategory_id != 'all'){
    product_id.innerHTML = ""
    $(document).ready(function () {
        $.ajax({
            url:`/../api/get-products-by-category?category_id=${discount_subcategory_id}`,
            type:'GET',
            success: function (data) {
                if(product_exists.classList.contains('d-none')){
                    product_exists.classList.remove('d-none')
                }
                product_id.required = true
                let disabled_option = document.createElement('option')
                disabled_option.text = text_select_product
                disabled_option.disabled = true
                disabled_option.value = ''
                product_id.add(disabled_option)
                let all_products = document.createElement('option')
                all_products.text = text_all_products
                all_products.value = "all"
                product_id.add(all_products)
                data.data[0].products.forEach(discountAddOptionToProduct)
            },
            error: function (e) {
                if(!product_exists.classList.contains('d-none')){
                    product_exists.classList.add('d-none')
                }
                if(product_id.getAttribute('required') != null && product_id.getAttribute('required') != undefined){
                    product_id.removeAttribute('required')
                }
            }
        })
    })
}else{
    let all_products = document.createElement('option')
    all_products.text = text_all_products
    all_products.value = "all"
    all_products.selected = true
    product_id.add(all_products)
}

function addOption(item, index){
    let option = document.createElement('option')
    option.value = item.id
    option.text = item.name
    subcategory_id.add(option)
}

category_id.addEventListener('change', function () {
    subcategory_id.innerHTML = ""
    product_id.innerHTML = ""
    if(!product_exists.classList.contains('d-none')){
        product_exists.classList.add('d-none')
    }
    $(document).ready(function () {
        if(category_id.value != '') {
            $.ajax({
                url: `/../api/subcategory/${category_id.value}`,
                type: 'GET',
                success: function (data) {
                    if (subcategory_exists.classList.contains('d-none')) {
                        subcategory_exists.classList.remove('d-none')
                    }
                    subcategory_id.required = true
                    let disabled_option = document.createElement('option')
                    disabled_option.text = text_select_sub_category
                    disabled_option.selected = true
                    disabled_option.disabled = true
                    disabled_option.value = ''
                    subcategory_id.add(disabled_option)
                    let all_products = document.createElement('option')
                    all_products.text = text_all_products
                    all_products.value = "all"
                    subcategory_id.add(all_products)
                    data.data.forEach(addOption)
                },
                error: function (e) {
                    console.log(e)
                    if (!subcategory_exists.classList.contains('d-none')) {
                        subcategory_exists.classList.add('d-none')
                    }
                    if (!product_exists.classList.contains('d-none')) {
                        product_exists.classList.add('d-none')
                    }
                    if(subcategory_id.getAttribute('required') != null && subcategory_id.getAttribute('required') != undefined){
                        subcategory_id.removeAttribute('required')
                    }
                }
            })
        }else{
            if (!subcategory_exists.classList.contains('d-none')) {
                subcategory_exists.classList.add('d-none')
            }
            if (!product_exists.classList.contains('d-none')) {
                product_exists.classList.add('d-none')
            }
            if(subcategory_id.getAttribute('required') != null && subcategory_id.getAttribute('required') != undefined){
                subcategory_id.removeAttribute('required')
            }
        }
    })
})

function addOptionToProduct(item, index){
    let option = document.createElement('option')
    option.value = item.id
    option.text = item.name
    product_id.add(option)
}
subcategory_id.addEventListener('change', function () {
    product_id.innerHTML = ""
    $(document).ready(function () {
        if(subcategory_id.value != 'all' && subcategory_id.value != ''){
            $.ajax({
                url:`/../api/get-products-by-category?category_id=${subcategory_id.value}`,
                type:'GET',
                success: function (data) {
                    if(product_exists.classList.contains('d-none')){
                        product_exists.classList.remove('d-none')
                    }
                    product_id.required = true
                    let disabled_option = document.createElement('option')
                    disabled_option.text = text_select_product
                    disabled_option.selected = true
                    disabled_option.disabled = true
                    disabled_option.value = ''
                    product_id.add(disabled_option)
                    let all_products = document.createElement('option')
                    all_products.text = text_all_products
                    all_products.value = "all"
                    product_id.add(all_products)
                    data.data[0].products.forEach(addOptionToProduct)
                },
                error: function (e) {
                    console.log(e)
                    if(!product_exists.classList.contains('d-none')){
                        product_exists.classList.add('d-none')
                    }
                    if(product_id.getAttribute('required') != null && product_id.getAttribute('required') != undefined){
                        product_id.removeAttribute('required')
                    }
                }
            })
        }else{
            if(!product_exists.classList.contains('d-none')){
                product_exists.classList.add('d-none')
            }
            if(product_id.getAttribute('required') != null && product_id.getAttribute('required') != undefined){
                product_id.removeAttribute('required')
            }
        }
    })
})
