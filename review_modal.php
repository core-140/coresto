<!-- review_modal.php -->
<div id="reviewModal" class="review-modal">
    <div class="modal-content">
        <span onclick="closeModal()" class="close-btn">✖</span>
        <h3>Reviews</h3>
        <div id="reviewList"></div>
        <form id="reviewForm">
            <input type="hidden" name="foodid" id="foodid">
            <input type="email" name="email" placeholder="Your email" required>
            <textarea name="review_text" placeholder="Write your review" required></textarea>
            <button type="submit">Submit Review</button>
        </form>
    </div>
</div>
