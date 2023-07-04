
if (isBooks) {
    /* Info */
    document.getElementById("info-click").addEventListener("click", function () {
        Array.from(document.getElementsByClassName("tooltip")).forEach(element => {
            element.remove();
        });
    })

    /* Activate */

    Array.from(document.getElementsByClassName("activate")).forEach(element => {
        element.addEventListener("click", function () {
            data = {
                _token: token, //token blade sayfasında belirleniyor
                request: "activate",
                book: this.getAttribute("book"),
            }
            request(JSON.stringify(data), activateUrl, function () {
                const response = JSON.parse(this.responseText);

                if (response.state == "success") {
                    //Remove Tooltip  
                    Array.from(document.getElementsByClassName("tooltip")).forEach(element => {
                        element.remove();
                    });

                    location.reload();
                } else {
                    location.reload();
                }
            })
        })
    });

    /* Disable */

    Array.from(document.getElementsByClassName("disable")).forEach(element => {
        element.addEventListener("click", function () {
            data = {
                _token: token, //token blade sayfasında belirleniyor
                request: "disable",
                book: this.getAttribute("book"),
            }
            request(JSON.stringify(data), disableUrl, function () {
                const response = JSON.parse(this.responseText);

                if (response.state == "success") {
                    //Remove Tooltip  
                    Array.from(document.getElementsByClassName("tooltip")).forEach(element => {
                        element.remove();
                    });
                    location.reload();
                } else {
                    location.reload();
                }
            })
        })
    });

    /* Remove */

    Array.from(document.getElementsByClassName("remove-book")).forEach(element => {
        element.addEventListener("click", function () {
            data = {
                _token: token, //token blade sayfasında belirleniyor
                request: "remove",
                book: this.getAttribute("book"),
            }
            request(JSON.stringify(data), removeUrl, function () {
                const response = JSON.parse(this.responseText);

                if (response.state == "success") {
                    //Remove Tooltip  
                    Array.from(document.getElementsByClassName("tooltip")).forEach(element => {
                        element.remove();
                    });
                    location.reload();
                } else if (response.state == "inTrade") {
                    Array.from(document.getElementsByClassName("tooltip")).forEach(element => {
                        element.remove();
                    });
                    swal('Bu kitap aktif bir takas işlemide yer aldığı için kitaplıktan kaldırılamaz.', 'Diğer kullanıcıların bu kitaba takas teklifinde bulunmasını istemiyorsanız pasif duruma getirin.', 'warning').then(() => {
                        location.reload();
                    });
                }
                else {
                    location.reload();
                }
            })
        })
    });
}