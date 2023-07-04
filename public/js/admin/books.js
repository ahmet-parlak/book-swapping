/* Search */
const searchForm = document.getElementById("search-form");
const searchBtn = document.getElementById("searchButton");
const searchInput = document.getElementById("searchInput");


searchForm.addEventListener("submit", function (event) {
    event.preventDefault();
    search();
})

searchBtn.addEventListener("click", search);

function search() {
    if (searchInput.value.length >= 3) {
        location.href = searchUrl + "?search=" + searchInput.value;
    } else {
        searchInput.value = "";
        searchInput.placeholder = "En az üç karakter girin...";
        searchInput.focus();
    }

}

/* Dashboard */
document.getElementById("passive-icon").addEventListener("mouseover", function () {
    document.getElementById("passive-info").classList.remove("d-none");
});

document.getElementById("passive-icon").addEventListener("mouseout", function () {
    document.getElementById("passive-info").classList.add("d-none");
});