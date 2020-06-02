'use strict';

{
  let del = document.getElementsByClassName('delete');
  for (let i = 0; i < del.length; i++) {
    del[i].addEventListener('click', e => {
      if (confirm('削除します。よろしいですか？')) {
        $test = document.getElementById('f_' + del[i].dataset.id).submit();
      }
    });
  }
}

{
  const menuItems = document.querySelectorAll('.menu li a');
  const contents = document.querySelectorAll('.container');
  menuItems.forEach(clickedItem => {
    clickedItem.addEventListener('click', e => {

      e.preventDefault();

      if (clickedItem.dataset.id === 'setting') {
        document.getElementById('uploadForm').classList.add('none');
      } else {
        document.getElementById('uploadForm').classList.remove('none');
      }

      menuItems.forEach(item => {
        item.classList.remove('active');
      });
      clickedItem.classList.add('active');

      contents.forEach(content => {
        content.classList.remove('active');
      });
      document.getElementById(clickedItem.dataset.id).classList.add('active');
    });
  });
}