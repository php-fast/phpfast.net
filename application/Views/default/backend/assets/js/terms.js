function urlSlug(str) {
    str = str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    str = str.toLowerCase().trim();
    str = str.replace(/[^a-z0-9\s-]/g, ''); 
    str = str.replace(/[\s-]+/g, '-'); 
    str = str.replace(/^-+|-+$/g, '');

    return str;
}   

$(function() {
    const nameInput = $('#name');
    const slugInput = $('#slug');

    // Sử dụng phương thức .on() thay cho addEventListener
    nameInput.on('input', function() {
        // Kiểm tra nếu trường slug hiện tại không có giá trị, thì cập nhật
        if (slugInput.val().trim() === '') {
            const slugValue = urlSlug(nameInput.val());
            console.log(slugValue);
            slugInput.val(slugValue);
        }
    });

    slugInput.on('input', function() {
        // Khi người dùng chỉnh sửa trường slug, đặt giá trị đã được sửa thành cố định
        if (slugInput.val().trim() !== '') {
            slugInput.data('userModified', true);
        }
    });

    nameInput.on('input', function() {
        // Nếu người dùng chưa chỉnh sửa slug thủ công, tiếp tục tự động cập nhật slug từ name
        if (!slugInput.data('userModified')) {
            const slugValue = urlSlug(nameInput.val());
            slugInput.val(slugValue);
        }
    });
});
