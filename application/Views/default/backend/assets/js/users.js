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
                tr.innerHTML = `
                    <td class="p-4 text-sm text-gray-900">${user.username}</td>
                    <td class="p-4 text-sm text-gray-900">${user.fullname}</td>
                    <td class="p-4 text-sm text-gray-900">${user.phone}</td>
                    <td class="p-4 text-sm text-gray-900">${user.email}</td>
                    <td class="p-4 text-sm text-gray-900">${user.role}</td>
                    <td class="p-4 text-sm">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" ${user.status === 'active' ? 'checked' : ''} data-user-id="${user.id}" onclick="return confirm('Bạn có chắc muốn thay đổi trạng thái?');"  class="status-checkbox sr-only peer">
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
    const checkboxes = document.querySelectorAll('.status-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const userId = this.getAttribute('data-user-id');
            let newStatus = this.checked ? 'active' : 'inactive'; // Trạng thái mới sau khi người dùng thay đổi

            const formData = new FormData();
            formData.append('user_id', userId);
            formData.append('status', newStatus);

            // Tạm thời vô hiệu hóa checkbox để ngăn thay đổi thêm cho đến khi nhận được phản hồi từ server
            this.disabled = true;

            // Gửi yêu cầu AJAX để cập nhật trạng thái người dùng
            $.ajax({
                url: '/admin/users/update_status', // URL endpoint để cập nhật trạng thái
                type: 'POST', // method post
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    loadUsers();
                },
                error: function(xhr, status, error) {
                    console.log('Error updating status:', status, error);
                    checkbox.checked = !checkbox.checked;
                },
                complete: function() {
                    checkbox.disabled = false;
                }
            });
        });
    });
}

document.addEventListener('DOMContentLoaded', function () {
    loadUsers();
});

