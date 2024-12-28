function addOption(subcategoryId, item, index){
    let option = document.createElement('option')
    option.value = item.id
    option.text = item.name
    subcategoryId.add(option)
}
function getSubcategory(subcategoryExists, categoryId, subcategoryId, disabledText){
    subcategoryId.innerHTML = ""
    $(document).ready(function () {
        $.ajax({
            url:`/../api/subcategory/${categoryId.value}`,
            type:'GET',
            success: function (data) {
                if(data.status == true){
                    if(subcategoryExists.classList.contains('d-none')){
                        subcategoryExists.classList.remove('d-none')
                    }
                }else{
                    if(!subcategoryExists.classList.contains('d-none')){
                        subcategoryExists.classList.add('d-none')
                    }
                }
                let disabled_option = document.createElement('option')
                disabled_option.text = disabledText
                disabled_option.selected = true
                disabled_option.value = ""
                disabled_option.disabled = true
                subcategoryId.add(disabled_option)
                data.data.forEach((item, index) => addOption(subcategoryId, item, index))
            }
        })
    })
}
