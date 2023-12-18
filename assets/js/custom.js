jQuery(function ($) {
    $('.tax_location_select').select2({ 
        allowClear: false
    });
    $('.tax_location_select').on('change', function(e) {
        e.preventDefault();
        let val = $(this).val();
        let productID = $(this).data('product_id');
        if (val !== '') {
            $.ajax({
                method: 'POST',
                url: ajax_object.ajax_url,
                dataType: 'JSON',
                data: {
                    action: 'show_store_locations',
                    location: val,
                    product_id: productID
                },
                beforeSend: function(e) {
                    $('.loader-content').addClass('active');
                },
                success: function(response) {
                    $('.loader-content').removeClass('active');
                    if (response.success) {
                        $('#result_location').html(response.data);
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            })
        } else {
            $('.loader-content').removeClass('active');
            $('#result_location').html('<p class="warning-notice">Bạn chưa chọn địa điểm</p>');
        }
    })
})