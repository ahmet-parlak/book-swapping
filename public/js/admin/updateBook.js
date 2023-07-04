const bookImg = document.getElementById("bookImg");
const bookImgInput = document.getElementById("imageInput");
bookImg.addEventListener("click", function () {
    bookImgInput.click();
})

bookImgInput.addEventListener("change", function () {
    const imgName = bookImgInput.value.split("\\");
    document.getElementById("imageInputLabel").textContent = imgName[imgName.length - 1];
})




const isbnInput = document.getElementById("isbnInput");
const editIsbnLabel = document.getElementById("editIsbnLabel");
editIsbnLabel.addEventListener("click", function () {
    isbnInput.removeAttribute("disabled");
});
editIsbnLabel.addEventListener("mouseover", function () {
    editIsbnLabel.style.textDecoration = "underline";
});
editIsbnLabel.addEventListener("mouseout", function () {
    editIsbnLabel.style.textDecoration = "none";
});

/* Book Id Change Control */
const bookidInput = document.getElementById("book_id");
const form = document.getElementById("form");
form.addEventListener("submit", function (event) {
    let params = (new URL(document.location)).searchParams;
    let bookId = params.get('book');
    if (bookId != bookidInput.value) {
        event.preventDefault();
        swal("Hata", "", "error").then(() => {
            location.reload();
        });
    }
})