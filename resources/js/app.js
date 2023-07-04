import './bootstrap';

Echo.private('notification.' + sessionStorage.getItem('notification_number'))
    .listen('.notification', (e) => {
        if (window.location.href.split("/")[3] != "message" || window.location != e.link) {
            const notsMenu = document.querySelector(".notifications-menu");
            const li = document.createElement("li");
            li.classList.add('row', 'not-dropdown-item', 'align-middle', 'align-items-center', 'notification-item', 'mb-2');
            li.innerHTML = `<div class="col-2 not-user-pp text-end">
            <img src="http://localhost:8000/${e.sender_photo}" alt="">
            </div>
            <div class="col-10 ms-0 ps-0"><a href="${e.link}"
                class="notification-link"
                notification="${e.notification_id}"><strong>${e.sender}</strong>
                ${e.message} </a>
            </div>`;

            notsMenu.insertBefore(li, notsMenu.firstChild);
            const notsEmptyDiv = document.querySelector(".notifications-empty");
            const clearNotsDiv = document.querySelector(".clear-notifications");
            const bellBadge = document.querySelector("span.bell-badge");

            if (clearNotsDiv) {
                clearNotsDiv.style.display = "block";
            }
            if (notsEmptyDiv) {
                notsEmptyDiv.style.display = "none";
            }
            if (bellBadge) {
                bellBadge.style.display = "block";
            }
            new Audio('http://localhost:8000/media/sounds/software-interface-start-2574.wav').play()
        } else {
            const data = {
                request: "markAsRead",
                notification: e.notification_id,
            }

            request(JSON.stringify(data), "http://localhost:8000/markasread", function () {

                const response = JSON.parse(this.responseText);
                if (response.state == "success") {

                }

            })
        }
    });

function request(data, url, onloadFunction) {
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onload = onloadFunction;
    xmlhttp.open("POST", url);
    xmlhttp.setRequestHeader("X-CSRF-TOKEN", token);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(data);
}
