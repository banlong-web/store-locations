function createAddStore(location, qtyBase) {
    let htmlFormStore = `
    <div class="ui formcontent form">
        <div class="delete-form">
            <button class="ui btn-deleted button">
                <i class="close icon"></i>
            </button>
        </div>
        <input type="hidden" class="tax_location" name="tax_location_${location}" value="${location}">
        <div class="two fields">
            <div class="field">
                <label>Tên cửa hàng</label>
                <input type="text" class="store_location_name" name="store_location_name" value="" placeholder="Tên cửa hàng">
            </div>
            <div class="field">
                <label>Địa chỉ</label>
                <input type="text" class="store_location_address" name="store_location_address" value="" placeholder="Địa chỉ">
            </div>
        </div>
        <div class="two fields">
            <div class="field">
                <label>Số điện thoại</label>
                <input type="text" class="store_location_phone" name="store_location_phone" value="" placeholder="0345678912">
            </div>
            <div class="field">
                <label>Số lượng trong kho</label>
                <input type="number" class="store_location_stock" name="store_location_stock" value="${qtyBase}">
            </div>
        </div>
    </div>
`;
return htmlFormStore;
}
jQuery(function ($) {
    $('.location-accordion').accordion();
    $('.add-store').on('click', function (e) {
        e.preventDefault();
        let qtyBase = $('.qty_base').val();
        let location = $(this).data('location');
        let html = createAddStore(location, qtyBase);
        let formID = $(e.target).parent().find('#form-store');
        formID.append(html);
    });
    $('.btn-deleted').on('click', function(e) {
        e.preventDefault();
        let currentParent = $(e.target).parent();
        let parentBtn = $(currentParent).parent();
        let parentForm = $(parentBtn).parent();
        $(parentForm).parent().remove();
    });
    let dataStoreLocation = [];
    $('form').on('submit', function (e) {
        // e.preventDefault();
        $(window).unbind('beforeunload');
        $('.formcontent').each((i, elem) => {
            var storeFields = {};
            var elemDivs = $(elem);
            let location = elemDivs.find('.tax_location').val();
            let storeName = elemDivs.find('.store_location_name').val();
            let storeAddress = elemDivs.find('.store_location_address').val();
            let storePhone = elemDivs.find('.store_location_phone').val();
            let storeStock = elemDivs.find('.store_location_stock').val();
            storeFields.location = location;
            storeFields.name = storeName;
            storeFields.address = storeAddress;
            storeFields.phone = storePhone;
            storeFields.stock = storeStock;
            dataStoreLocation.push(storeFields);
        });
        let arrData = [];
        for (let i = 0; i < dataStoreLocation.length; i++) {
            const element = dataStoreLocation[i];
            arrData.push(element);
        }
        let value = JSON.stringify(arrData);
        $("<input/>").attr({
            type: 'hidden',
            id: 'data_store_location',
            name: 'data_store_location',
            value: value
        }).appendTo("form#post");
        return true;
    });
})