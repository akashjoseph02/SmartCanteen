document.addEventListener('DOMContentLoaded', () => {
    attachRemoveEventListeners();
});

function attachRemoveEventListeners() {
    document.querySelectorAll('.remove-item').forEach(link => {
        link.addEventListener('click', handleRemoveClick);
    });
}

function handleRemoveClick(e) {
    e.preventDefault();

    const link = e.target;
    const itemName = link.getAttribute('href').split('=')[1];

    if (confirm('Are you sure you want to remove this item from the cart?')) {
        // Send a request to the server to remove the item
        removeItemFromCart(itemName)
            .then(responseText => {
                if (responseText.trim() === 'success') {
                    link.closest('tr').remove(); // Remove the row from the table
                    showAlert('Item removed successfully!');
                } else {
                    showAlert('Failed to remove item. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while removing the item.');
            });
    }
}

function removeItemFromCart(itemName) {
    return fetch(`remove_from_cart.php?id=${encodeURIComponent(itemName)}`, {
        method: 'GET',
    })
    .then(response => response.text());
}

function showAlert(message) {
    const alertBox = document.createElement('div');
    alertBox.className = 'alert-box';
    alertBox.innerText = message;
    document.body.appendChild(alertBox);

    setTimeout(() => {
        alertBox.remove();
    }, 3000);
}
