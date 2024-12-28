delete_form = document.getElementById('delete_form')
delete_modal = document.getElementById('delete_modal')
delete_buttons = document.getElementsByClassName('delete_button')
for (let i = 0; i < delete_buttons.length; i++) {
    if (delete_buttons[i] != null && delete_buttons[i] != undefined) {
        // Add an event listener in a closure to preserve 'i' value
        delete_buttons[i].addEventListener('click', (function(button) {
            return function() {
                delete_form.setAttribute('action', button.getAttribute('data-url'))
            }
        })(delete_buttons[i]))
    }
}

let image_content_ = document.getElementById('image_content_')
function showImage(image) {
    let images_content =
        `<div class="carousel-item active">
                <img class="d-block img-fluid" src="${image}" alt="no image">
            </div>`
    if(image_content_ != undefined && image_content_ != null){
        image_content_.innerHTML = images_content
    }
}


let default_image_content = document.querySelector('.default_image_content')
let image_input = document.getElementById('image_input')
let images_quantity = document.getElementById('images_quantity')
if(default_image_content != undefined && default_image_content != null && image_input != undefined && image_input != null &&
    images_quantity != undefined && images_quantity != null && items_selected_text != undefined && items_selected_text != null){
    default_image_content.addEventListener('click', function () {
        image_input.click();
    });
    image_input.addEventListener('change', function (e) {
        let image_quantities = e.target.files.length
        if(image_quantities>0 && image_quantities<2){
            images_quantity.innerHTML = e.target.files[0].name
        }else if(image_quantities>1){
            images_quantity.innerHTML = image_quantities + ' '+items_selected_text
        }
    });
}


let default_image_content_ = document.querySelector('.default_image_content_')
let image_input_ = document.getElementById('image_input_')
let images_quantity_ = document.getElementById('images_quantity_')
if(default_image_content_ != undefined && default_image_content_ != null && image_input_ != undefined && image_input_ != null &&
    images_quantity_ != undefined && images_quantity_ != null && items_selected_text != undefined && items_selected_text != null){
    default_image_content_.addEventListener('click', function () {
        image_input_.click();
    });
    image_input_.addEventListener('change', function (e) {
        let image_quantities_ = e.target.files.length
        if(image_quantities_>0 && image_quantities_<2){
            images_quantity_.innerHTML = e.target.files[0].name
        }else if(image_quantities_>1){
            images_quantity_.innerHTML = image_quantities_ + ' '+items_selected_text
        }
    });
}


let default_image_content__ = document.querySelector('.default_image_content__')
let image_input__ = document.getElementById('image_input__')
let images_quantity__ = document.getElementById('images_quantity__')
if(default_image_content__ != undefined && default_image_content__ != null && image_input__ != undefined && image_input__ != null &&
    images_quantity__ != undefined && images_quantity__ != null && items_selected_text != undefined && items_selected_text != null){
    default_image_content__.addEventListener('click', function () {
        image_input__.click()
    });
    image_input__.addEventListener('change', function (e) {
        let image_quantities__ = e.target.files.length
        if(image_quantities__>0 && image_quantities__<2){
            images_quantity__.innerHTML = e.target.files[0].name
        }else if(image_quantities__>1){
            images_quantity__.innerHTML = image_quantities__ + ' '+items_selected_text
        }
    });
}


let dropdownMenuButton = document.getElementById('dropdownMenuButton')
let language_flag = document.getElementById('language_flag')
let background_transparent = document.querySelector('.background_transparent')

dropdownMenuButton.addEventListener('click', function() {
    if (language_flag.classList.contains('display-none')) {
        language_flag.classList.remove('display-none')
    } else {
        language_flag.classList.add('display-none')
    }
    if (background_transparent.classList.contains('display-none')) {
        background_transparent.classList.remove('display-none')
    } else {
        background_transparent.classList.add('display-none')
    }
});

background_transparent.addEventListener('click', function() {
    if (!language_flag.classList.contains('display-none')) {
        language_flag.classList.add('display-none')
    }
    if (!background_transparent.classList.contains('display-none')) {
        background_transparent.classList.add('display-none')
    }
});

