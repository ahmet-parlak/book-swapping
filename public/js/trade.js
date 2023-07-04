const booksTake = document.querySelectorAll(".add-book.take");
const booksGive = document.querySelectorAll(".add-book.give");
const updateOfferSpan = document.querySelector("span.update-offer");
const updateOfferSubmitDiv = document.querySelector("div.update-offer-submit");
const refuseBtn = document.getElementById("refuseBtn");
const acceptBtn = document.getElementById("acceptBtn");
const doneBtn = document.getElementById("doneBtn");
const giveupBtn = document.getElementById("giveupBtn");
const offerBtn = document.getElementById("make-offer");
const updaetOfferBtn = document.getElementById("updateOfferBtn");

const inputs = document.querySelectorAll("input.form-check-input");
booksTake.forEach(element => {
    element.addEventListener("click", function addTake() {

        alert(element.getAttribute("book"))
    })
});

booksGive.forEach(element => {
    element.addEventListener("click", function addTake() {

        alert(element.getAttribute("book"))
    })
});

if (offerBtn) {
    offerBtn.addEventListener("click", function (e) {
        e.preventDefault();
        //Selected books control
        let cg = 0;
        let ct = 0;
        document.querySelectorAll(".form-check-input.give").forEach(element => {
            if (element.checked) {
                cg = 1;
            }
        });
        document.querySelectorAll(".form-check-input.take").forEach(element => {
            if (element.checked) {
                ct = 1;
            }
        });

        if (cg) {
            if (ct) {
                form = document.getElementById("tradeOfferForm");
                swal({
                    title: "Takas Teklifi Yapmak Üzeresiniz",
                    text: "Karşı taraf teklifi kabul edebilir, reddedebilir ya da karşı teklifte bulunabilir.\n\nTeklifiniz kabul edilirse; seçtiğiniz kitaplar kitaplığınızdan geçici olarak kaldırılır ve eğer varsa bu kitapları içeren aktif takas işlemleri otomatik olarak iptal edilir.",
                    icon: "warning",
                    buttons: {
                        cancel: "İptal",
                        confirm: "Onayla",
                    },
                }).then((confirm) => {
                    if (confirm) {
                        form.submit();
                    }
                });
            } else {
                swal({
                    title: "Kitap Seçmediniz",
                    text: "Takasta alacağınız kitabı seçin!",
                    icon: "warning",
                    button: "Tamam",
                })
            }
        } else {
            swal({
                title: "Kitap Seçmediniz",
                text: "Takasta vereceğiniz kitabı seçin!",
                icon: "warning",
                button: "Tamam",
            })
        }

    })
}

if (updaetOfferBtn) {
    updaetOfferBtn.addEventListener("click", function (e) {
        e.preventDefault();
        //Selected books control
        let cg = 0;
        let ct = 0;
        document.querySelectorAll(".form-check-input.give").forEach(element => {
            if (element.checked) {
                cg = 1;
            }
        });
        document.querySelectorAll(".form-check-input.take").forEach(element => {
            if (element.checked) {
                ct = 1;
            }
        });

        if (cg) {
            if (ct) {
                form = document.getElementById("tradeOfferForm");
                swal({
                    title: "Takas Teklifi Yap",
                    text: "",
                    icon: "warning",
                    buttons: {
                        cancel: "İptal",
                        confirm: "Onayla",
                    },
                }).then((confirm) => {
                    if (confirm) {
                        form.submit();
                    }
                });
            } else {
                swal({
                    title: "Kitap Seçmediniz",
                    text: "Takasta alacağınız kitabı seçin!",
                    icon: "warning",
                    button: "Tamam",
                })
            }
        } else {
            swal({
                title: "Kitap Seçmediniz",
                text: "Takasta vereceğiniz kitabı seçin!",
                icon: "warning",
                button: "Tamam",
            })
        }

    })
}

if (refuseBtn) {
    refuseBtn.addEventListener("click", function (e) {
        e.preventDefault();
        form = document.getElementById("tradeofferrefuse");
        tradeNumberInput = document.getElementById("refuseNumberInput");
        const url = window.location.href.split("/");
        const urlTradeNumber = url[url.length - 1];


        if (tradeNumberInput.value == urlTradeNumber) {
            let title;
            if (typeof refuseBtnTxt !== 'undefined') {
                if (refuseBtnTxt == 'cancel') {
                    title = "Takas İptal Edilecek";
                } else if (refuseBtnTxt == 'refuse') {
                    title = "Takas Reddedilecek";
                }
            } else {
                title = "Takas İptal Edilecek";
            }
            swal({
                title: title,
                text: "Bu işlem geri alınamaz.",
                icon: "warning",
                buttons: {
                    cancel: "İptal",
                    confirm: "Onayla",
                },
            }).then((confirm) => {
                if (confirm) {
                    form.submit();
                }
            });
        }
    })
}

if (acceptBtn) {
    acceptBtn.addEventListener("click", function (e) {

        e.preventDefault();
        form = document.getElementById("tradeofferaccept");
        acceptNumberInput = document.getElementById("acceptNumberInput");
        const url = window.location.href.split("/");
        const urlTradeNumber = url[url.length - 1];


        if (acceptNumberInput.value == urlTradeNumber) {
            swal({
                title: "Takas Teklifini Kabul Etmek Üzeresiniz",
                text: "Takasta vereceğiniz kitaplar kitaplığınızdan geçici olarak kaldırılacak.\nEğer varsa bu kitapların olduğu diğer aktif takas işlemleri otomatik olarak iptal edilecek.",
                icon: "warning",
                buttons: {
                    cancel: "İptal",
                    confirm: "Onayla",
                },
            }).then((confirm) => {
                if (confirm) {
                    form.submit();
                }
            });

        }
    })
}
if (doneBtn) {
    doneBtn.addEventListener("click", function (e) {

        e.preventDefault();
        form = document.getElementById("tradeofferdone");
        acceptNumberInput = document.getElementById("doneNumberInput");
        const url = window.location.href.split("/");
        const urlTradeNumber = url[url.length - 1];


        if (acceptNumberInput.value == urlTradeNumber) {
            swal({

                title: "DİKKAT",
                text: "Takasta alacağınız kitapların elinize ulaştığını kabul ediyor musunuz? \n\n (Karşı taraf onaylamadan takas tamamlanmaz!)",
                icon: "warning",
                buttons: {
                    cancel: "İptal",
                    confirm: "Onayla",
                },
            }).then((confirm) => {
                if (confirm) {
                    form.submit();
                }
            });

        }
    })
}
if (giveupBtn) {
    giveupBtn.addEventListener("click", function (e) {
        e.preventDefault();
        form = document.getElementById("tradeoffergiveup");
        tradeNumberInput = document.getElementById("giveupNumberInput");
        const url = window.location.href.split("/");
        const urlTradeNumber = url[url.length - 1];


        if (tradeNumberInput.value == urlTradeNumber) {
            swal({
                title: "Takas İptal Edilecek",
                text: "Bu işlem geri alınamaz.",
                icon: "warning",
                buttons: {
                    cancel: "İptal",
                    confirm: "Onayla",
                },
            }).then((confirm) => {
                if (confirm) {
                    form.submit();
                }
            });
        }
    })
}

if (typeof trade !== 'undefined') {
    if (trade == "active") {
        const checkedInputs = document.querySelectorAll("input.form-check-input[checked]");
        checkedInputs.forEach(element => {
            element.parentNode.parentNode.classList.add("selected");
        });

        inputs.forEach(element => {
            element.setAttribute('disabled', '');
        });
    } else {
        inputs.forEach(element => {
            element.remove();
        });
    }
}


if (updateOfferSpan) {
    updateOfferSpan.addEventListener("click", function (element) {
        inputs.forEach(element => {
            element.removeAttribute('disabled')
            element.removeAttribute('hidden')
        });
        const checkedInputs = document.querySelectorAll("input.form-check-input[checked]");
        checkedInputs.forEach(element => {
            element.parentNode.parentNode.classList.remove("selected");
        });

        updateOfferSubmitDiv.removeAttribute('hidden');

        updateOfferSpan.remove();
    });
}