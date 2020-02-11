<?php
    include_once('./conn.php');
    include_once('./check_login.php');
    include_once('./utils.php');

    
    if (isset($_GET['page'])) {
      $page = $_GET['page'];
    } else {
      $page = 1;
    }
?>
<!DOCTYPE html>
<html>

<head>
  <title>Board Comments</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="./style.css">
  <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</head>

<script>

$(document).ready(function(){

  function escapeHtml(text) {
    var map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
  }

  function addCommentsDom(id, parent_id, nickname, content){
    if ($('.board-name').length > 0) {
      let getYourNickname = $('.board-name')[0].textContent;
      if (getYourNickname === nickname) {
      const comments = `<div class="comments margin-top-20 relative" data-id="${id}">
          <div class="comment-id">${nickname}</div>
          <div class="comment-content" value="${id}">${content}</div>
          <form class="none">
            <textarea class="comment-textarea">${content}</textarea>
            <input type="hidden" name="edit_id" value="${id}">
            <input class="comment-submit" type="submit" value="送出">
          </form>
          <div class="comment-edit">
              <input type="hidden" value="${id}"> 
              編輯
          </div>
          <div class="comment-delete">
              <input type="hidden" name="comment_id" value="${id}"> 
              刪除
          </div>
          <button class="none comment-close">關閉</button>
      </div>`
      return comments
      } else {
        const comments = `<div class="comments margin-top-20 relative" data-id="${id}">
            <div class="comment-id">${nickname}</div>
            <div class="comment-content" value="${id}">${content}</div>
        </div>`
        return comments
      }
    } else {
      const comments = `<div class="comments margin-top-20 relative" data-id="${id}">
          <div class="comment-id">${nickname}</div>
          <div class="comment-content" value="${id}">${content}</div>
      </div>`
      return comments
    }
  }

  function addSubCommentsDom(id, parent_id, nickname, content){
    if ($('.board-name').length > 0) {
      let getYourNickname = $('.board-name')[0].textContent;
      if (getYourNickname === nickname) {
      const sub_comments = `<div class="sub_comments margin-top-10 relative">
            <div class="comment-id">${nickname}</div>
            <div class="comment-content" value="${id}">${content}</div>
            <form class="none">
              <textarea class="sub-comment-textarea">${content}</textarea>
              <input type="hidden" name="edit_id" value="${id}">
              <input class="sub-comment-submit" type="submit" value="送出">
            </form>
            <div class="comment-edit-sub">
                <input type="hidden" value="${id}"> 
                編輯
            </div>
            <div class="comment-delete-sub">
                <input type="hidden" name="comment_id" value="${id}"> 
                刪除
            </div>
            <button class="none sub-comment-close">關閉</button>
      </div>`
      return sub_comments
      } else {
        const sub_comments = `<div class="sub_comments margin-top-10 relative">
              <div class="comment-id">${nickname}</div>
              <div class="comment-content" value="${id}">${content}</div>
        </div>`
        return sub_comments
      }
    } else {
      const sub_comments = `<div class="sub_comments margin-top-10 relative">
            <div class="comment-id">${nickname}</div>
            <div class="comment-content" value="${id}">${content}</div>
      </div>`
      return sub_comments
    }
  }

  function addSubCommentWriteBoard(id) {
    if ($('.board-name').length > 0) {
      let getYourNickname = $('.board-name')[0].textContent;
      return `<form class="sub-board-write">
              <div style="margin: 0 20px;">
                <div class="sub-board-name">${getYourNickname}</div>
                <textarea class="sub-board-write-text" name="sub_comment"></textarea>
                <div class="padding-top-15 padding-bottom-15">
                  <input type="hidden" name="parent_id" value="${id}"> 
                  <input class="sub-board-btn" type="button" value="submit">
                </div>
              </div>
            </form>`
    }
  }

  function GetDataComments(page){
    $('.comments_board')[0].innerHTML = '';
    $.ajax({
      method: "POST",
      url: "./get_comments.php",
      data: {
        'page': page
      },
      dataType: "json",
      success: function(res){
        comments = res.data;
        commentsLength = res.data.length;
        // 建立變數
        for (let i = 0; i<res.data.length; i++) {
          let id = comments[i].id;
          let parent_id = comments[i].parent_id;
          let nickname = comments[i].nickname;
          let content = escapeHtml(comments[i].content);
          $('.comments_board').append(addCommentsDom(id, parent_id, nickname, content));
          let comment_DOM = $('.comments')[i];
          GetDataSubComments(id,comment_DOM);
        }
      },
      error: (res)=>{
        // console.log('error');
        // console.log(res);
      }
    })
  }

  function GetYourNewComment(comment, parent_id){
    $.ajax({
      method: "POST",
      url: "./get_your_new_comment.php",
      data: {
        'parent_id': parent_id,
        'comment': comment
      },
      dataType: "json",
      success: function(res){
        let comment = res.data;
        let id = comment[0].id;
        let nickname = comment[0].nickname;
        let content = escapeHtml(comment[0].content);
        $('.comments_board').prepend(addCommentsDom(id, parent_id, nickname, content));
        let comment_DOM = $('.comments')[0];
        $(comment_DOM).append(addSubCommentWriteBoard(id));
      },
      error: (res) => {
        console.log('error');
      }
    })
  }

  function GetYourNewSubComment(comment, parent_id){
    $.ajax({
      method: "POST",
      url: "./get_your_new_subcomment.php",
      data: {
        'parent_id': parent_id,
        'comment': comment
      },
      dataType: "json",
      success: function(res){
        let comments = res.data;
        let length = res.data.length;
        if (length>0) {
          for (let i = 0; i<=10; i++) {
            let parentDOM = $($('.comments')[i]);
            if (parentDOM.data('id') == parent_id) {
              let parentDOMlastchild = $($('.comments')[i]).children().last();
              let id = comments[0].id;
              let nickname = comments[0].nickname;
              let content = escapeHtml(comments[0].content);
              parentDOMlastchild.before(addSubCommentsDom(id, parent_id, nickname, content));
            }
          }
        }
      },
      error: (res) => {
        console.log('error');
        console.log(res);
      }
    })
  }

  function GetDataSubComments(id,comment_DOM){
    $.ajax({
      method: "POST",
      url: "./get_subcomments.php",
      data: {
        'id': id
      },
      dataType: "json",
      success: function(res){
        if (res.data_sub == null) {
          $(comment_DOM).append(addSubCommentWriteBoard(id))
        } else {
          subcomments = res.data_sub;
          subcommentsLength = res.data_sub.length;
          for(let i = 0; i<res.data_sub.length; i++){
            let id = subcomments[i].id;
            let parent_id = subcomments[i].parent_id;
            let nickname = subcomments[i].nickname;
            let content = escapeHtml(subcomments[i].content);
            $(comment_DOM).append(addSubCommentsDom(id, parent_id, nickname, content));
          }
          $(comment_DOM).append(addSubCommentWriteBoard(id))
        }
      },
      error: (res)=>{
        $(comment_DOM).append(addSubCommentWriteBoard(id))
      }
    })
  }

  function HandleComment(comment, parent_id) {
    $('.board-btn')[0].value = '傳送中...';
    $.ajax({
      method: "POST",
      url: "./handle_comment.php",
      data: {
        'parent_id': parent_id,
        'comment': comment
      },
      dataType: "json",
      success: function(res){
        if (res.success === true) {
          GetYourNewComment(comment, parent_id);
          alert('新增留言成功！');
        } else {
          alert('新增留言失敗！');
        }
      },
      error: (res) => {
        console.log('error');
      }
    })
  }

  function HandleSubComment(sub_comment, parent_id) {
    $.ajax({
      method: "POST",
      url: "./handle_sub_comment.php",
      data: {
        'parent_id': parent_id,
        'sub_comment': sub_comment
      },
      dataType: "json",
      success: function(res){
        if (res.success === true) {
          GetYourNewSubComment(sub_comment, parent_id);
          alert('留言新增成功');
        } else {
          alert('留言新增失敗');
        }
      },
      error: (res) => {
        // console.log('error');
        // console.log(res);
      }
    })
  }

  function HandleEditComment(edit_id,comment,old_comment_DOM) {
    $.ajax({
      method: "POST",
      url: "./handle_comment_edit.php",
      data: {
        'id': edit_id,
        'comment': comment
      },
      dataType: "json",
      success: function(res){
        if (res.success === true) {
          alert('修改成功');
          ChangCommentText(comment,old_comment_DOM);
        } else {
          alert('修改失敗');
        }
      },
      error: (res) => {
        console.log('error');
        console.log(res);
      }
    })
  }

  function ChangCommentText(comment,old_comment_DOM) {
    $(old_comment_DOM)[0].textContent = comment;
  }

  $('.board-write').on('click', '.board-btn', () => {
    const nickname = $('.board-name')[0].innerText;
    let comment = $('.board-write-text')[0].value;
    const parent_id = 0;
    if (comment) {
      HandleComment(comment, parent_id);
      $('.board-btn')[0].value = 'submit';
      $('.board-write-text')[0].value = '';
    } else {
      alert('請輸入留言內容！')
    }
  })

  $('.comments_board').on('click', '.comment-delete', (e) => {

    if (!confirm('確定要刪除此留言？')) return
    const comment_id = $(e.target)[0].children[0].value;

    $.ajax({
      method: "POST",
      url: "./handle_comment_delete.php",
      data: {
        comment_id: comment_id
      }
    }).done(function(response){
      const msg = JSON.parse(response)
      alert(msg.message);
      const subComment = $(e.target).parent('.sub_comments');
      if (subComment.length === 0) {
        $(e.target).parent('.comments').hide(500);
      } else {
        subComment.hide(500);
      };
    }).fail(function(){
      alert("網路不穩定，請重新整理頁面");
    })
  })
  
  $('.comments_board').on('click', '.comment-edit', (e) => {
    $(e.target.previousElementSibling).removeClass('none');
    $(e.target.nextElementSibling.nextElementSibling).removeClass('none');
    const form = e.target.previousElementSibling;
    const old_comment_DOM = e.target.previousElementSibling.previousElementSibling;
    $(form).on('submit', function(e){
      e.preventDefault();
      const edit_id = e.target[1].value;
      const comment = e.target[0].value;
      e.target[0].value = '';
      HandleEditComment(edit_id,comment,old_comment_DOM);
      $(e.target).addClass('none');
      $(e.target.nextElementSibling.nextElementSibling.nextElementSibling).addClass('none');
    })
  })

  $('.comments_board').on('click', '.comment-delete-sub', (e) => {

    if (!confirm('確定要刪除此留言？')) return
    const comment_id = $(e.target)[0].children[0].value;
    
    $.ajax({
      method: "POST",
      url: "./handle_comment_delete.php",
      data: {
        comment_id: comment_id
      }
    }).done(function(response){
      const msg = JSON.parse(response)
      alert(msg.message);
      const subComment = $(e.target).parent('.sub_comments');
      if (subComment.length === 0) {
        $(e.target).parent('.comments').hide(500);
      } else {
        subComment.hide(500);
      };
    }).fail(function(){
      alert("網路不穩定，請重新整理頁面");
    })
  })

  $('.comments_board').on('click', '.comment-edit-sub', (e) => {
    $(e.target.previousElementSibling).removeClass('none');
    $(e.target.nextElementSibling.nextElementSibling).removeClass('none');
    const form = e.target.previousElementSibling;
    const old_comment_DOM = e.target.previousElementSibling.previousElementSibling;
    $(form).on('submit', function(e){
      e.preventDefault();
      const edit_id = e.target[1].value;
      const comment = e.target[0].value;
      e.target[0].value = '';
      HandleEditComment(edit_id,comment,old_comment_DOM);
      $(e.target).addClass('none');
      $(e.target.nextElementSibling.nextElementSibling.nextElementSibling).addClass('none');
    })
  })

  $('.comments_board').on('click', '.sub-board-btn', (e) => {
    const sub_comment = e.target.parentNode.previousElementSibling.value;
    const parent_id = e.target.parentNode.children[0].value;
    if (sub_comment) {
      HandleSubComment(sub_comment, parent_id);
      e.target.parentNode.previousElementSibling.value = '';
    } else {
      alert('請輸入留言內容！')
    }
  })
  
  $('.pages').on('click', '.page', (e)=>{
    e.preventDefault();
    GetDataComments(e.target.textContent);
  })

  $('.comments_board').on('click', '.comment-close', (e) => {
    $(e.target).addClass('none');
    $(e.target.previousElementSibling.previousElementSibling.previousElementSibling).addClass('none');
  })

  $('.comments_board').on('click', '.sub-comment-close', (e) => {
    $(e.target).addClass('none');
    $(e.target.previousElementSibling.previousElementSibling.previousElementSibling).addClass('none');
  })

  function GetPagination(page){
    $.ajax({
      method:"POST",
      url:"./get_pagination.php",
      data:{},
      dataType: "json",
      success: function(res){
        GetPagesDOM(res);
      },
      error: function(res){

      }
    })
  }

  function GetPagesDOM(pageNum){
    for(let i=1; i<=pageNum; i++){
      $('.pages').append(eachpageDOM(i));
    }
  }

  function eachpageDOM(i){
    return `<a class="page" href="#">${i}</a>`
  }

  GetDataComments(1);
  GetPagination();

})

</script>

<body>
  <div>
    <div class="navbar">
      <div class="nav-title margin-left-15">Message Board</div>
      <div>
        <ul class="nav-items">
          <?php
            if ($username) {
          ?>
              <a href="./logout.php" class="nav-item">登出</a>
          <?php
            } else {
          ?>
              <a href="./login.php" class="nav-item">登入</a>
              <a href="./register.php" class="nav-item">註冊</a>
          <?php
            }
          ?>
        </ul>
      </div>
    </div>
    <div class="container">
      <div class="title">Comments Board</div>
      <?php if ($username) { ?>
      <form class="board-write">
        <div class="board-name"><?php echo $username ?></div>
        <textarea class="board-write-text" name="comment" id="" cols="30" rows="10"></textarea>
        <div class="padding-top-30 padding-bottom-30">
        <input type="hidden" name="parent_id" value="0">
        <input class="board-btn" type="button" value="submit" />
        </div>
      </form>
      <?php } ?>
      <div class="pages margin-top-10"></div>
      <div class="comments_board"></div>
      <div class="footer">This page is created by - me</div>
    </div>
  </div>
</body>

</html>