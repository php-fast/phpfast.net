function addStatusChangeEvent() {
    $('.status-checkbox').on("change", function(){  
        const jthis = $(this);
        const check_confirm = confirm('Bạn có chắc muốn thay đổi trạng thái?');
        console.log(check_confirm);
        if (!check_confirm){
            jthis.prop('checked', !jthis.prop('checked') );
            return;
        };
        
        const userId = jthis.attr('data-user-id');
        let newStatus = jthis.prop('checked') ? 'active' : 'inactive'; // Trạng thái mới sau khi người dùng thay đổi
        const jstatus = jthis.parent().find('span');
        jstatus.text(newStatus);
        if (newStatus == 'active'){
            jstatus.removeClass('bg-red-500');
            jstatus.removeClass('text-white');
            jstatus.addClass('bg-success-light');
            jstatus.addClass('text-green-800');
        }else{
            jstatus.removeClass('bg-success-light');
            jstatus.removeClass('text-green-800');
            jstatus.addClass('bg-red-500');
            jstatus.addClass('text-white');
        }

        const formData = new FormData();
        formData.append('status', newStatus);

        // Tạm thời vô hiệu hóa checkbox để ngăn thay đổi thêm cho đến khi nhận được phản hồi từ server
        jthis.attr('disabled', true);

        // Gửi yêu cầu AJAX để cập nhật trạng thái người dùng
        $.ajax({
            url: `/admin/users/edit/${userId}`,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function() {
                jthis.removeAttr('disabled');
            },
            error: function(xhr, status, error) {
                jthis.removeAttr('disabled');
                console.log('Error updating status:', status, error);
            },
            complete: function() {
                jthis.removeAttr('disabled');
            }
        });
    });
}
document.addEventListener('DOMContentLoaded', function () {
    addStatusChangeEvent();
});

