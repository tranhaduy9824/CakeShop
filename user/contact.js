function handleOpenCloseChat() {
  // Contact
  const contactbtns = document.querySelectorAll('.contact')
  const contact = document.querySelector('#logo-chat')
  const closeContacts = document.querySelectorAll('.close-contact')

  function showContact() {
      contact.classList.add('open')
  }

  function outContact() {
      contact.classList.remove('open')
  }

  for (const contactbtn of contactbtns) {
  contactbtn.addEventListener('click', showContact)
  contactbtn.addEventListener('click', showBoxchat)
  }

  for (const closeContact of closeContacts) {
  closeContact.addEventListener('click', outContact),
  closeContact.addEventListener('click', outBoxchat)
  }
  // Box chat
  const boxchatbtn = document.querySelector('.box-chat')
  const boxchat = document.querySelector('#box-chat')
  const closeBoxchat =document.querySelector('.close-boxchat')

  function showBoxchat() {
  boxchat.classList.add('open')
  }

  function outBoxchat() {
  boxchat.classList.remove('open')
  }

  closeBoxchat.addEventListener('click', outBoxchat)
  boxchatbtn.addEventListener('click', showBoxchat)
}

handleOpenCloseChat();

var isPageRefreshed = false;
document.addEventListener("DOMContentLoaded", function() {
    checkPageRefresh();
    var previousMessageId;

    function selectUser() {
        var userDivs = document.getElementsByClassName("contact");
        var contentRight = document.querySelector("#box-chat");

        for (var i = 0; i < userDivs.length; i++) {
        userDivs[i].addEventListener("click", function() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "/CuoiKiWeb/user/contact.php", true);
            xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                var responseHTML = document.createElement('div');
                responseHTML.innerHTML = xhr.responseText;

                var updatedContentRight = responseHTML.querySelector('#box-chat');
                if (updatedContentRight) {
                    contentRight.innerHTML = updatedContentRight.innerHTML;

                    var center = contentRight.querySelector('.center');
                    setTimeout(function() {
                        center.scrollTo(0, center.scrollHeight);
                    }, 0);

                    handleOpenCloseChat();
                }

                getPreviousMessageId(function(lastMessageId) {
                    previousMessageId = lastMessageId;
                    // sendMessage();
                });
                sendMessage();
            }
            };
            xhr.send();
        });
        }
    }

    function sendMessage() {
        var form = document.querySelector('.box-message');

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            var formData = new FormData(form);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/CuoiKiWeb/user/handle/handleContact.php', true);
            xhr.responseType = 'json';

            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = xhr.response;
                    if (response.success) {
                        var center = document.querySelector('.center');
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
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "/CuoiKiWeb/user/handle/getLastMessageId.php", true);
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
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "/CuoiKiWeb/user/handle/getMessage.php", true);
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
        var center = document.querySelector(".center");
    
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
      var center = document.querySelector('.center');
      setTimeout(function() {
          center.scrollTo(0, center.scrollHeight);
      }, 0);           
      handleOpenCloseChat();
  }
}

window.addEventListener("pageshow", function(event) {
  if (event.persisted || isPageRefreshed) {
      isPageRefreshed = false;
  }
});