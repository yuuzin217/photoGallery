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