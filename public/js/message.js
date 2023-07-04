const messageBox = document.getElementById('message-box');
const sendBtn = document.getElementById('send-button');
const messagesDiv = document.getElementById('messages-box');
let lock = false;
sendBtn.addEventListener('click', function () {
    if (!lock) {
        lock = true;
        const message = messageBox.value;
        if (message != "") {
            const user = document.getElementById('userInput').value;
            const token = document.querySelector('input[name="_token"]').value;
            const data = {
                user: user,
                message: message,
            }


            const options = {
                method: 'post',
                url: url,
                data: data,
            }

            axios(options)
                .then(function (response) {
                    if (response.data.success) {
                        messageBox.value = "";
                        messageBox.focus();
                        messageBox.style.outlineColor = "#030303";

                        const date = response.data.date.split("T")[0];
                        const clock = response.data.date.split("T")[1].split(":");

                        const div = document.createElement('div');
                        div.innerHTML =
                            `<div class="message mt-2 px-3">
            <div class="row"> 
                <div class="col-7"></div>
                <div class="col-5 text-box p-2">
                    <div class="message fw-bold">${response.data.message}
                        <div class="date text-end fw-normal"> <span
                        class="ms-2"></span></div>
                    </div>
                </div>
            </div>
         </div>`;
                        messagesDiv.append(div);
                        messagesDiv.scrollTop = messagesDiv.scrollHeight;
                        if (document.querySelectorAll('.message.mt-2.px-3').length >= 5) {
                            /* document.querySelector('.footer').style.position = "relative";
                            document.querySelector('.footer').style.marginTop = "100px"; */
                        }

                        if (document.querySelectorAll('.message.mt-2.px-3').length >= 7) {
                            messagesDiv.style.overflowY = "scroll";
                        }

                    }
                }).finally(() => lock = false);

        } else {
            messageBox.focus();
            messageBox.style.outlineColor = "#cc0621";
            lock = false;
        }

    }
})

messageBox.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        sendBtn.click();
    }
})

window.onload = function () {
    messagesDiv.scrollTop = messagesDiv.scrollHeight
};


function request(data, url, onloadFunction, token) {
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onload = onloadFunction;
    xmlhttp.open("POST", url);
    xmlhttp.setRequestHeader("X-CSRF-TOKEN", token);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(data);
}