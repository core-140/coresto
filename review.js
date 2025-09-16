// review.js
const modal = document.getElementById('reviewModal');
const reviewForm = document.getElementById('reviewForm');
const reviewList = document.getElementById('reviewList');
const foodIdInput = document.getElementById('foodid');

function openReviewModal(foodId) {
    foodIdInput.value = foodId;
    modal.style.display = 'flex';
    loadReviews(foodId);
}

function closeModal() {
    modal.style.display = 'none';
    reviewList.innerHTML = '';
    reviewForm.reset();
}

window.onclick = function(event) {
    if (event.target === modal) closeModal();
}

function loadReviews(foodId) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "load_reviews.php?foodid=" + foodId, true);
    xhr.onload = function() {
        if (this.status === 200) {
            reviewList.innerHTML = this.responseText;
        }
    }
    xhr.send();
}

reviewForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(reviewForm);
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "submit_review.php", true);
    xhr.onload = function() {
        if (this.status === 200) {
            reviewForm.reset();
            loadReviews(foodIdInput.value);
            alert(this.responseText); // Optional: confirmation
        }
    }
    xhr.send(formData);
});
