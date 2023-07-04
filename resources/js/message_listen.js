import './bootstrap';

const messageBoxDiv = document.getElementById('messages-box');

Echo.private('chat.' + contact_number)
    .listen('.message', (e) => {
        const div = document.createElement('div');
        const date = e.date.split("T")[0];
        const clock = e.date.split("T")[1].split(":");
        div.innerHTML =
            `<div class="message mt-2 px-3">
                <div class="row"> 
                    <div class="col-5 text-box text-start p-2">
                        <div class="message fw-bold">${e.message}
                            <div class="date text-end fw-normal"><span
                            class="ms-2"></span></div>
                        </div>
                    </div>
                </div>
             </div>`;


        messageBoxDiv.append(div);
        new Audio('http://localhost:8000/media/sounds/message-pop-alert.mp3').play()

        messageBoxDiv.scrollTop = messageBoxDiv.scrollHeight;

        if (document.querySelectorAll('.message.mt-2.px-3').length >= 5) {
            /* document.querySelector('.footer').style.position = "relative";
            document.querySelector('.footer').style.marginTop = "100px"; */
        }

        if (document.querySelectorAll('.message.mt-2.px-3').length >= 7) {
            messageBoxDiv.style.overflowY="scroll";
        }

    });

