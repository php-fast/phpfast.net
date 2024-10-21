function loadUsers() {
    $.ajax({
        url: '/api/users/index',
        type: 'GET',
        dataType: 'json',
        success: function(response, status, xhr) {
            console.log(response);
            const userTableBody = document.querySelector('#user-table-body');
            userTableBody.innerHTML = '';

            response.data.forEach(user => {
                const tr = document.createElement('tr');
                $(tr).attr('id', `user-row-${user.id}`);
                tr.innerHTML = `
                    <td class="p-4 text-sm text-gray-900">${user.username}</td>
                    <td class="p-4 text-sm text-gray-900">${user.fullname}</td>
                    <td class="p-4 text-sm text-gray-900">${user.phone}</td>
                    <td class="p-4 text-sm text-gray-900">${user.email}</td>
                    <td class="p-4 text-sm text-gray-900">${user.role}</td>
                    <td class="p-4 text-sm">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" ${user.status === 'active' ? 'checked' : ''} data-user-id="${user.id}" class="status-checkbox sr-only peer">
                            <div
                                class="mr-3 relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all  peer-checked:bg-blue-600">
                            </div>
                            <span class="status complete rounded-full py-1 px-2 ${user.status === 'active' ? 'bg-success-light text-green-800' : 'bg-red-500 text-white'} text-center text-xs font-medium leading-4">
                                ${user.status}
                            </span>
                        </label>
                    </td>
                    <td class="p-4 text-sm text-gray-900">
                        <a href="/admin/users/edit/${user.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                    </td>
                `;
                userTableBody.appendChild(tr);
            });

            addStatusChangeEvent();
        },
        error: function(xhr, status, error) {
            console.error('Error fetching users:', status, error);
        }
    });
}

function addStatusChangeEvent() {
    $('.status-checkbox').on("change", function(){  
        const jthis = $(this);
        const check_confirm = confirm('Bạn có chắc muốn thay đổi trạng thái?');
        console.log(check_confirm);
        if (!check_confirm){
            jthis.prop('checked', !jthis.prop('checked') );
            return;
        };
        
        //const userId = this.getAttribute('data-user-id');
        const userId = jthis.attr('data-user-id');
        let newStatus = jthis.prop('checked') ? 'active' : 'inactive'; //this.checked ? 'active' : 'inactive'; // Trạng thái mới sau khi người dùng thay đổi
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
            url: `/admin/users/edit/${userId}`, // URL endpoint để cập nhật trạng thái
            type: 'POST', // method post
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                //loadUsers();
                jthis.removeAttr('disabled');
            },
            error: function(xhr, status, error) {
                jthis.removeAttr('disabled');
                console.log('Error updating status:', status, error);
                //checkbox.checked = !checkbox.checked;
            },
            complete: function() {
                jthis.removeAttr('disabled');
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', function () {
    addStatusChangeEvent();
    //loadUsers();
});

