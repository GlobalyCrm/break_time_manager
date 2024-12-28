
var myVar;
window.onload = function () {
    myVar = setTimeout(function () {
        let datatable_buttons_filter_label_ = document.querySelectorAll('.dataTables_filter label')
        let dataTables_wrapper = document.querySelectorAll('.dataTables_wrapper')
        let buttons_pdf = document.querySelector('.buttons-pdf')
        let buttons_copy = document.querySelector('.buttons-copy')
        // Check if the label contains the old text
        let svg_search_icon = document.createElement('div')
        svg_search_icon.innerHTML = `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 21L16.657 16.657M16.657 16.657C17.3998 15.9141 17.9891 15.0322 18.3912 14.0615C18.7932 13.0909 19.0002 12.0506 19.0002 11C19.0002 9.9494 18.7932 8.90908 18.3912 7.93845C17.9891 6.96782 17.3998 6.08589 16.657 5.343C15.9141 4.60011 15.0321 4.01082 14.0615 3.60877C13.0909 3.20673 12.0506 2.99979 11 2.99979C9.94936 2.99979 8.90905 3.20673 7.93842 3.60877C6.96779 4.01082 6.08585 4.60011 5.34296 5.343C3.84263 6.84333 2.99976 8.87821 2.99976 11C2.99976 13.1218 3.84263 15.1567 5.34296 16.657C6.84329 18.1573 8.87818 19.0002 11 19.0002C13.1217 19.0002 15.1566 18.1573 16.657 16.657Z" stroke="#606368" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>`
        for(let k=0; k<datatable_buttons_filter_label_.length; k++){
            datatable_buttons_filter_label_[k].append(svg_search_icon.cloneNode(true))
        }
        for(let n=0; n<dataTables_wrapper.length; n++){
            if(dataTables_wrapper[n].firstChild != undefined && dataTables_wrapper[n].firstChild != null){
                if(dataTables_wrapper[n].firstChild.firstChild != undefined && dataTables_wrapper[n].firstChild.firstChild != null){
                    dataTables_wrapper[n].firstChild.firstChild.classList.add('mb-4')
                }
                if(dataTables_wrapper[n].firstChild.lastChild != undefined && dataTables_wrapper[n].firstChild.lastChild != null){
                    dataTables_wrapper[n].firstChild.lastChild.classList.add('mb-4')
                }
            }
        }
        if(search_client_text == undefined && search_client_text == null) {
            search_client_text = ''
        }
        if(buttons_pdf != undefined && buttons_pdf != null){
            buttons_pdf.innerHTML = `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18.6667 18H19.9167C20.4687 17.9983 20.9976 17.7695 21.388 17.3635C21.7783 16.9575 21.9984 16.4074 22 15.8333V7.16667C21.9984 6.59256 21.7783 6.04245 21.388 5.6365C20.9976 5.23054 20.4687 5.00171 19.9167 5H4.08333C3.53131 5.00171 3.00236 5.23054 2.61202 5.6365C2.22167 6.04245 2.00165 6.59256 2 7.16667V15.8333C2.00165 16.4074 2.22167 16.9575 2.61202 17.3635C3.00236 17.7695 3.53131 17.9983 4.08333 18H5.33333" stroke="#121212" stroke-width="1.5" stroke-linejoin="round"/>
                <path d="M17.67 11H6.33C5.59546 11 5 11.5758 5 12.2862V20.7138C5 21.4242 5.59546 22 6.33 22H17.67C18.4045 22 19 21.4242 19 20.7138V12.2862C19 11.5758 18.4045 11 17.67 11Z" stroke="#121212" stroke-width="1.5" stroke-linejoin="round"/>
                <path d="M19 5V3.875C18.9983 3.37818 18.7672 2.90212 18.3574 2.55081C17.9475 2.1995 17.3921 2.00148 16.8125 2H7.1875C6.60787 2.00148 6.05248 2.1995 5.64262 2.55081C5.23276 2.90212 5.00173 3.37818 5 3.875V5" stroke="#121212" stroke-width="1.5" stroke-linejoin="round"/>
                <path d="M19 10C19.5523 10 20 9.32843 20 8.5C20 7.67157 19.5523 7 19 7C18.4477 7 18 7.67157 18 8.5C18 9.32843 18.4477 10 19 10Z" fill="#121212"/>
            </svg>`
        }
        if(buttons_copy != undefined && buttons_copy != null){
            buttons_copy.innerHTML = `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8.66667 17.1429C8.66667 16.9155 8.75446 16.6975 8.91074 16.5368C9.06702 16.376 9.27899 16.2857 9.5 16.2857H14.5C14.721 16.2857 14.933 16.376 15.0893 16.5368C15.2455 16.6975 15.3333 16.9155 15.3333 17.1429C15.3333 17.3702 15.2455 17.5882 15.0893 17.7489C14.933 17.9097 14.721 18 14.5 18H9.5C9.27899 18 9.06702 17.9097 8.91074 17.7489C8.75446 17.5882 8.66667 17.3702 8.66667 17.1429ZM5.33333 12C5.33333 11.7727 5.42113 11.5547 5.57741 11.3939C5.73369 11.2332 5.94565 11.1429 6.16667 11.1429H17.8333C18.0543 11.1429 18.2663 11.2332 18.4226 11.3939C18.5789 11.5547 18.6667 11.7727 18.6667 12C18.6667 12.2273 18.5789 12.4453 18.4226 12.6061C18.2663 12.7668 18.0543 12.8571 17.8333 12.8571H6.16667C5.94565 12.8571 5.73369 12.7668 5.57741 12.6061C5.42113 12.4453 5.33333 12.2273 5.33333 12ZM2 6.85714C2 6.62981 2.0878 6.4118 2.24408 6.25105C2.40036 6.09031 2.61232 6 2.83333 6H21.1667C21.3877 6 21.5996 6.09031 21.7559 6.25105C21.9122 6.4118 22 6.62981 22 6.85714C22 7.08447 21.9122 7.30249 21.7559 7.46323C21.5996 7.62398 21.3877 7.71429 21.1667 7.71429H2.83333C2.61232 7.71429 2.40036 7.62398 2.24408 7.46323C2.0878 7.30249 2 7.08447 2 6.85714Z" fill="#121212"/>
            </svg>`
        }
        let loader = document.getElementById("loader")
        let myDiv = document.getElementById("myDiv")
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
    }, 1000);
}
