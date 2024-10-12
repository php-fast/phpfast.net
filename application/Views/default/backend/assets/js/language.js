function setDefaultLanguage(url) {
   if (confirm('Bạn có chắc muốn đặt ngôn ngữ này làm mặc định không?')) {
       window.location.href = url;
   }
}

function changeStatus(url) {
   if (confirm('Bạn có chắc chắn muốn thay đổi ngôn ngữ này không?')) {
      window.location.href = url;
   }
}


function showForm() {
   const form = document.getElementById('language-form');
   const toggleButton = document.getElementById('toggle-button');
   
   if (form.style.display === 'none' || form.style.display === '') {
       form.style.display = 'block';
       toggleButton.innerText = 'Thu gọn';
   } else {
       form.style.display = 'none';
       toggleButton.innerText = 'Thêm ngôn ngữ';
   }
}