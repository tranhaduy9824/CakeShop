var isPageRefreshed = false;
document.addEventListener("DOMContentLoaded", function() {
  checkPageRefresh();
  var previousMessageId;

  // Chọn người dùng
  function selectUser() {
    var userDivs = document.getElementsByClassName("item-user");
    var contentRight = document.querySelector(".content-right");

    for (var i = 0; i < userDivs.length; i++) {
      userDivs[i].addEventListener("click", function() {
        for (var j = 0; j < userDivs.length; j++) {
          userDivs[j].classList.remove('active');
        }

        this.classList.add('active');

        var useridInput = this.querySelector("input[name='userid']");
        var userid = useridInput.value;

        var xhr = new XMLHttpRequest();
        xhr.open("GET", "contact.php?userid=" + encodeURIComponent(userid), true);
        xhr.onreadystatechange = function() {
          if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var responseHTML = document.createElement('div');
            responseHTML.innerHTML = xhr.responseText;

            var updatedContentRight = responseHTML.querySelector('.content-right');
            if (updatedContentRight) {
              contentRight.innerHTML = updatedContentRight.innerHTML;

              var boxChat = contentRight.querySelector('.box-chat');
              setTimeout(function() {
                boxChat.scrollTo(0, boxChat.scrollHeight);
              }, 0);
            }

            getPreviousMessageId(function(lastMessageId) {
              previousMessageId = lastMessageId;
              sendMessage();
            });
          }
        };
        xhr.send();

        var urlParams = new URLSearchParams(window.location.search);
        urlParams.set('userid', userid);
        var newUrl = window.location.origin + window.location.pathname + '?' + urlParams.toString();
        history.pushState(null, null, newUrl);
      });
    }
  }

  // Gửi tin nhắn
  function sendMessage() {
    var form = document.querySelector('.box-message');

    form.addEventListener('submit', function(event) {
      event.preventDefault();

      var formData = new FormData(form);

      var xhr = new XMLHttpRequest();

      var urlParams = new URLSearchParams(window.location.search);
      var userid = urlParams.get('userid');

      urlParams.set('userid', userid);
      var newUrl = window.location.origin + window.location.pathname + '?' + urlParams.toString();
      history.pushState(null, null, newUrl);

      xhr.open('POST', '/CuoiKiWeb/admin/page/partials/handleContact.php?userid=' + userid, true);
      xhr.responseType = 'json';

      xhr.onload = function() {
        if (xhr.status === 200) {
          var response = xhr.response;
          if (response.success) {
            var center = document.querySelector('.content-chat');
            var sendDiv = document.createElement('div');
            sendDiv.classList.add('send');
            sendDiv.textContent = response.message;
            center.appendChild(sendDiv);

            document.querySelector('input[name="content"]').value = '';

            sendDiv.scrollIntoView({ behavior: 'smooth' });
          } else {
            console.log('Lỗi: ' + response.message);
          }
        } else {
          console.log('Lỗi Ajax: ' + xhr.status);
        }
      };

      xhr.onerror = function() {
        console.log('Lỗi Ajax: ' + xhr.status);
      };

      xhr.send(formData);
    });
  }

  function getPreviousMessageId(callback) {
    var urlParams = new URLSearchParams(window.location.search);
    var userid = urlParams.get('userid');

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "/CuoiKiWeb/admin/page/partials/getLastMessageId.php?userid=" + userid, true);
    xhr.responseType = "json";

    xhr.onload = function() {
      if (xhr.status === 200) {
        var response = xhr.response;
        if (response.success) {
          callback(response.lastMessageId);
        } else {
          console.log("Lỗi: " + response.message);
        }
      } else {
        console.log("Lỗi Ajax: " + xhr.status);
      }
    };

    xhr.onerror = function() {
      console.log("Lỗi Ajax: " + xhr.status);
    };

    xhr.send();
  }
  
  // Nhận tin nhắn mới
  function receiveMessages() {
    var urlParams = new URLSearchParams(window.location.search);
    var userid = urlParams.get('userid');

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "/CuoiKiWeb/admin/page/partials/getMessage.php?userid=" + userid, true);
    xhr.responseType = "json";

    xhr.onload = function() {
      if (xhr.status === 200) {
        var response = xhr.response;
        if (response.success) {
          var message = response.message;
          var messageId= response.messageId;
          if (message && messageId !== previousMessageId) {
            displayNewMessage(message);
            previousMessageId = messageId;
          }
        } else {
          console.log("Lỗi: " + response.message);
        }
      } else {
        console.log("Lỗi Ajax: " + xhr.status);
      }
    };

    xhr.onerror = function() {
      console.log("Lỗi Ajax: " + xhr.status);
    };

    xhr.send();
  }

  // Hiển thị tin nhắn mới
  function displayNewMessage(message) {
    var center = document.querySelector(".content-chat");

    var messageDiv = document.createElement("div");
    messageDiv.classList.add("receive");
    messageDiv.textContent = message;
    center.appendChild(messageDiv);

    messageDiv.scrollIntoView({ behavior: "smooth" });
  }

  setInterval(receiveMessages, 1000);

  function start() {
    selectUser();
  }

  start();
});

function checkPageRefresh() {
  if (performance.navigation.type === 1) {
      isPageRefreshed = true;
      var boxChat = document.querySelector('.box-chat');
      setTimeout(function() {
        boxChat.scrollTo(0, boxChat.scrollHeight);
      }, 0);
  }
}

window.addEventListener("pageshow", function(event) {
  if (event.persisted || isPageRefreshed) {
      isPageRefreshed = false;
  }
});

document.addEventListener('keydown', function(event) {
  if (event.keyCode === 116) { 
    event.preventDefault(); 
  }
});